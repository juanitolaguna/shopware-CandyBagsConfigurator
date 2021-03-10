<template>
  <div class="ec-configurator">
    <h1>{{ entityName }}</h1>

    <div id="accordion">
      <div v-for="(item, index) in treeNodes" class="card">
        <div :ref="item.id" class="card-header" @click="toggleAccordeon(item)">
          <h3 class="mb-0">
            <button class="btn btn-link">
              {{ item.stepDescription }}
            </button>
          </h3>
        </div>

          <div :class="['ec-cards', {active: item.active}]">
            <div class="ec-card-deck">
              <div v-for="child in item.children" :class="['card ec-card']" :id="child.id"
                   @click="getTreeNode(child.id, index, item.id)">
                <div class="card-image-top-wrapper" style="width: inherit">
                  <img class="card-img-top" :src="child.item.itemCard.media.url" :alt="child.item.itemCard.name">
                </div>
                <div class="card-body">
                  <h5 class="card-title"
                      :style="{paddingBottom: '0px'}">
                    {{ child.item.itemCard.name }}
                  </h5>
                  <img class="ec-icon" v-if="child.children.length" :src="assets.next" alt="next">
                  <img class="ec-icon" v-else :src="assets.last" alt="last">
                </div>
              </div>
              <div class="spacer"></div>
            </div>
          </div>

      </div>
    </div>
  </div>
</template>

<script>
import StoreApiClient from 'src/service/store-api-client.service';
import TreeNodeCard from "./component/TreeNodeCard";

export default {
  components: {
    TreeNodeCard
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
    mountedComponent() {
      this.getTreeNodeListing();
    },

    getTreeNode(id, index = null, parentId = null) {

      if (index !== null) {
        this.treeNodes = this.treeNodes.slice(0, index + 1);
      }

      let data = JSON.stringify({});
      this.httpClient.post(`/store-api/v{version}/tree-node/${id}`, data, (response) => {
        const res = JSON.parse(response);
        res['active'] = true;
        this.toggleAccordeon(parentId)
        this.treeNodes.push(res);

        // scroll to new header on update
        this.lastView = res.id;

      });
    },

    getTreeNodeListing() {
      let data = {
        includes: {eccb_tree_node: ["id"]}
      }

      this.httpClient.post(`/store-api/v{version}/tree-node-listing/${window.stepSetEntityId}`, JSON.stringify(data), (response) => {
        this.rootNodes = JSON.parse(response).elements;
        this.getTreeNode(this.rootNodes[0].id)
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
    }
  }
}
</script>