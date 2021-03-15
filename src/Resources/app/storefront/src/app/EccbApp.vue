<template>
  <div class="ec-configurator">
    <div class="row">
      <div class="col-lg-9 col-md-8 col-12">
        <div id="accordion">
          <div v-for="(parent, parentIndex) in treeNodes" class="card">
            <div :ref="parent.id" class="card-header" @click="toggleAccordeon(parent)">
              <h4 style="margin-bottom: 0">
                {{ translate(parent, 'stepDescription') }}
              </h4>
              <img :class="['ec-icon-header', {expanded: parent.active}]" style="width: 24px; height: 24px;"
                   :src="assets.arrowRight">
            </div>

            <div :class="['ec-cards', {active: parent.active}]">
              <div class="ec-card-deck">
                <TreeNodeCard
                    v-for="(child, childIndex) in parent.children"
                    :key="child.id"
                    :assets="assets"
                    :child="child"
                    :parentNode="parent"
                    @next-root-node="getTreeNode(nextRootNode(parent).id, nextRootNode(parent), {parentIndex, childIndex}, 'treeNode')"
                    @next-node="getTreeNode(child.id, parent, {parentIndex, childIndex}, 'treeNode')"
                    @terminal-node="selectTerminal({parentIndex, childIndex}, 'treeNode')"
                />

                <template v-for="(itemSet, itemSetIndex) in parent.itemSets">
                  <template v-for="(itemSetItem, itemSetItemIndex) in itemSet.items">

                    <ItemCard
                        v-if="itemSetItem"
                        :key="itemSetItem.id"
                        :assets="assets"
                        :child="itemSetItem"
                        :childNode="itemSet.childNode"
                        :parentNode="parent"
                        @next-root-node="getTreeNode(nextRootNode(parent).id, nextRootNode(parent), {parentIndex, itemSetIndex, itemSetItemIndex}, 'itemSet')"
                        @next-node="getTreeNode(itemSet.childNode.id, parent, {parentIndex, itemSetIndex, itemSetItemIndex}, 'itemSet')"
                        @terminal-node="selectTerminal({parentIndex, itemSetIndex, itemSetItemIndex}, 'itemSet')"
                    />

                  </template>
                </template>
                <div class="spacer"></div>
              </div>
            </div>

          </div>
        </div>
        <Spinner v-if="isLoading"/>
      </div>

      <div class="col-lg-3 col-md-4 col-12">
        <Selected
            @remove-item="removeItem($event)"
            :treeNodes="treeNodes"
        />
      </div>
    </div>
  </div>
</template>

<script>
import Debouncer from 'src/helper/debouncer.helper'
import TreeNodeCard from "./component/TreeNodeCard.vue";
import ItemCard from "./component/ItemCard.vue";
import Spinner from "./component/Spinner.vue";
import Selected from "./component/Selected.vue";
import {translate} from "./utils/utils.js"

export default {
  components: {
    Selected,
    Spinner,
    TreeNodeCard,
    ItemCard
  },

  data() {
    return {
      rootNodes: null,
      treeNodes: [],
      lastView: null,
      isLoading: false,
      context: null,
      product: null
    }
  },

  mounted() {
    this.mountedComponent();
  },

  updated() {
    if (this.lastView !== null) {
      this.$refs[this.lastView][0].scrollIntoView({behavior: 'smooth', block: 'start'});
      this.lastView = null;
    }
  },


  computed: {
    headers() {
      const headers = new Headers();
      headers.append("sw-access-key", window.accessKey);
      headers.append("sw-context-token", window.contextToken);
      headers.append("Content-Type", "application/json");
      return headers;
    },

    assets() {
      return window.img;
    }
  },


  methods: {
    translate,

    async mountedComponent() {
      this.isLoading = true;
      this.shortcuts();
      await this.setLanguage();
      const result = await this.getRootNodes();
      this.getTreeNode(result);
    },

    setLanguage() {
      const raw = JSON.stringify({"languageId": window.languageId});
      const requestOptions = {
        method: 'PATCH',
        headers: this.headers,
        body: raw,
        redirect: 'follow'
      };
      return fetch("/store-api/v3/context", requestOptions)
    },


    async getRootNodes() {
      const raw = JSON.stringify({
        includes: {
          eccb_tree_node: ["id", "children", "itemSets"]
        }
      });

      const requestOptions = {
        method: 'POST',
        headers: this.headers,
        body: raw,
        redirect: 'follow'
      };

      return await new Promise((resolve, reject) => {
        return fetch(`/store-api/v{version}/tree-node-listing/${window.stepSetEntityId}`, requestOptions)
            .then(result => result.text())
            .then(response => {
              try {
                const parsed = JSON.parse(response);
                const {elements = []} = parsed ? parsed : {};
                this.rootNodes = elements.map((element, index) => ({...element, index}));
                resolve(this.rootNodes[0].id);
              } catch (error) {
                reject(error);
              }
            });
      });
    },

    /**
     * @property {object} payload                   - optional, not used on mounted call
     * @property {number} payload.parentIndex       - itemSetType, treeNodeType
     * @property {number} payload.childIndex        - itemSetType
     * @property {number} payload.itemSetIndex      - treeNodeType
     * @property {number} payload.itemSetItemIndex  - treeNodeType
     */
    getTreeNode(treeNodeId, parent = null, payload = null, selectedType = '') {

      this.markSelectedItems(payload, selectedType);
      this.chopOffFollowingSteps(payload);

      this.isLoading = true

      const raw = JSON.stringify({});

      const requestOptions = {
        method: 'POST',
        headers: this.headers,
        body: raw,
        redirect: 'follow'
      };

      fetch(`/store-api/v{version}/tree-node/${treeNodeId}`, requestOptions)
          .then(result => result.text())
          .then(response => {
            const res = JSON.parse(response);
            res['active'] = true;
            res['rootNodeIndex'] = this.setRootNodeIndex(parent, treeNodeId);
            this.toggleAccordeon(parent);
            this.treeNodes.push(res);

            // scroll to new header on update hook
            this.lastView = res.id;
            this.isLoading = false;
          })
          .catch(error => console.log('error', error));
    },

    chopOffFollowingSteps(payload) {
      if ((payload !== null) && payload.hasOwnProperty('parentIndex')) {
        this.treeNodes = this.treeNodes.slice(0, payload.parentIndex + 1);
      }
    },

    markSelectedItems(payload, selectedType) {
      if (payload === null) return;
      if (selectedType === 'treeNode') {
        this.deselectTreeNodes(payload.parentIndex)
        this.deselectItemSets(payload.parentIndex);
        //deselect itemSets
        this.treeNodes[payload.parentIndex]['children'][payload.childIndex]['item']['selected'] = true;
      }

      if (selectedType === 'itemSet') {
        this.deselectTreeNodes(payload.parentIndex)
        this.deselectItemSets(payload.parentIndex);

        this.treeNodes[payload.parentIndex]['itemSets'][payload.itemSetIndex]['items'][payload.itemSetItemIndex]['selected'] = true;
      }
    },

    deselectItemSets(parentIndex) {
      this.treeNodes[parentIndex]['itemSets'].map((itemSet) => {
        itemSet['items'] = itemSet.items.map((item) => {
          item['selected'] = false;
          return item;
        });
        return itemSet;
      });
    },

    deselectTreeNodes(parentIndex) {
      this.treeNodes[parentIndex]['children'] = this.treeNodes[parentIndex]['children'].map((el) => {
        el['item']['selected'] = false;
        return el;
      });
    },

    selectTerminal(payload, selectedType) {
      this.markSelectedItems(payload, selectedType);
      this.chopOffFollowingSteps(payload);
    },

    toggleAccordeon(item) {
      if (item == null) return;
      this.treeNodes = this.treeNodes.map(node => {
        if (node.id === item.id) {
          node['active'] = !node.active;
        } else {
          node['active'] = false;
        }
        return node;
      })
    },

    setRootNodeIndex(parent, treeNodeId) {
      if (parent && parent.hasOwnProperty('rootNodeIndex')) {
        return parent.rootNodeIndex;
      } else {
        return this.rootNodes.filter((node) => node.id === treeNodeId)[0].index;
      }
    },

    nextRootNode(parent) {
      return this.rootNodes[parent.rootNodeIndex + 1];
    },

    shortcuts() {
      document.addEventListener('keydown', Debouncer.debounce(async (event) => {
        if (event.ctrlKey && event.key === 'r') {
          this.rootNodes = null;
          this.treeNodes = [];
          const result = await this.getRootNodes();
          this.getTreeNode(result);
        }
      }, 250))
    },

    removeItem(index) {
      console.log(index);
      this.treeNodes = this.treeNodes.slice(0, index + 1);
      this.deselectTreeNodes(index)
      this.deselectItemSets(index);
      this.treeNodes[index]['active'] = true;
    }
  }
}
</script>