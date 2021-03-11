<template>
  <div class="ec-configurator">
    <h1>{{ entityName }}</h1>

    <div id="accordion">
      <div v-for="(parent, parentIndex) in treeNodes" class="card">
        <div :ref="parent.id" class="card-header" @click="toggleAccordeon(parent)">
          <h3 class="mb-0">
            <button class="btn btn-link">
              {{ parent.stepDescription }}
            </button>
          </h3>
        </div>

        <div :class="['ec-cards', {active: parent.active}]">
          <div class="ec-card-deck">
            <TreeNodeCard
                v-for="(child, childIndex) in parent.children"
                :key="child.id"
                :assets="assets"
                :child="child"
                @on-select="getTreeNode(child.id, parent.id, {parentIndex, childIndex}, 'treeNode')"
            />

            <template v-for="(itemSet, itemSetIndex) in parent.itemSets">
              <template v-for="(itemSetItem, itemSetItemIndex) in itemSet.items">

                <ItemCard
                    v-if="itemSetItem"
                    :key="itemSetItem.id"
                    :assets="assets"
                    :child="itemSetItem"
                    :childNode="itemSet.childNode"
                    @on-select="getTreeNode(itemSet.childNode.id, parent.id, {parentIndex, itemSetIndex, itemSetItemIndex}, 'itemSet')"
                />

              </template>
            </template>
            <div class="spacer"></div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import StoreApiClient from 'src/service/store-api-client.service';
import TreeNodeCard from "./component/TreeNodeCard.vue";
import ItemCard from "./component/ItemCard.vue";

export default {
  components: {
    TreeNodeCard,
    ItemCard
  },

  data() {
    return {
      rootNodes: null,
      treeNodes: [],
      lastView: null
    }
  },

  computed: {
    httpClient() {
      return new StoreApiClient();
    },

    entityName() {
      return window.entityName;
    },

    assets() {
      return window.img;
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

  methods: {
    async mountedComponent() {
      this.shortcuts();
      this.getRootNodes()

      // async await...
      //const result =  await this._getRootNodes();
      //this.getTreeNode(result);

    },


    getRootNodes() {
      let data = {
        includes: {eccb_tree_node: ["id"]}
      }

      this.httpClient.post(`/store-api/v{version}/tree-node-listing/${window.stepSetEntityId}`, JSON.stringify(data), (response) => {
        this.rootNodes = JSON.parse(response).elements.map((el, index) => {
          return {
            index: index,
            ...el
          }
        })
        this.getTreeNode(this.rootNodes[0].id);
      });
    },

    // async await alternative
    async _getRootNodes() {
      const data = {
        includes: { eccb_tree_node: ["id"] }
      };

      await new Promise((resolve, reject) => {
        const callback = (response) => {
          // Parsing JSON can fail.
          try {
            const parsed = JSON.parse(response);
            const { elements = [] } = parsed ?? {};
            this.rootNodes = elements.map((element, index) => ({ ...element, index }));
            resolve(this.rootNodes[0].id);
          } catch (error) {
            reject(error);
          }
        }
      
        this.httpClient.post(`/store-api/v{version}/tree-node-listing/${window.stepSetEntityId}`, JSON.stringify(data), callback)
      });
    },


    /**
     * @property {object} payload                   - optional, not used on mounted call
     * @property {number} payload.parentIndex       - itemSetType, treeNodeType
     * @property {number} payload.childIndex        - itemSetType
     * @property {number} payload.itemSetIndex      - treeNodeType
     * @property {number} payload.itemSetItemIndex  - treeNodeType
     */
    getTreeNode(treeNodeId, parentId = null, payload = null, selectedType = '') {

      this.markSelectedItems(payload, selectedType);
      this.chopOffFollowingSteps(payload);

      this.httpClient.post(`/store-api/v{version}/tree-node/${treeNodeId}`, '{}', (response) => {
        const res = JSON.parse(response);
        res['active'] = true;

        if (parentId === null) {
          res['rootNode'] = treeNodeId
        }

        this.toggleAccordeon(parentId)
        this.treeNodes.push(res);

        // scroll to new header on update (update hook)
        this.lastView = res.id;

      });
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

    shortcuts() {
      document.addEventListener('keydown', (event) => {
        if (event.ctrlKey && event.key === 'r') {
          this.rootNodes = null;
          this.treeNodes = [];
          this.getTreeNodeListing();
        }
      });
    }

  }
}
</script>