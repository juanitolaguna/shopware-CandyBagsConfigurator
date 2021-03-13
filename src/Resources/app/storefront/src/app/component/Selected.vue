<template>
  <div>

    <h3 class="mb-2">Deine Auswahl</h3>
    <p v-if="!selected.length" style="font-size: 2rem">...</p>

    <div v-for="item in selected" class="card">

      <template v-if="treeNode(item)">
        <div class="row no-gutters ec-selection_card pb-1">
          <div class="w-25">
            <img :src="image(item)" class="img-fluid" alt="">
          </div>
          <div class="col">
            <div class="card-block px-2 mt-2">
              <h4>{{ translate(item.item.itemCard, 'name') }}</h4>
              <p class="card-text">Description</p>
            </div>
          </div>
        </div>
      </template>

      <template v-else>
        <div class="row no-gutters ec-selection_card pb-1">
          <div class="w-25">
            <img :src="image(item)" class="img-fluid" alt="">
          </div>
          <div class="col">
            <div class="card-block px-2 mt-2">
              <h4>{{ translate(item.itemCard, 'name') }}</h4>
              <p class="card-text">Description</p>
            </div>
          </div>
        </div>
      </template>

    </div>

    <button @click.prevent="addToCart" type="button" class="btn btn-primary" :disabled="!buttonEnabled">
      In den Warenkorb
    </button>

  </div>
</template>

<script>
import {translate} from "../utils/utils.js"

export default {
  props: ["treeNodes"],

  computed: {
    buttonEnabled() {
      if (!this.selected.length) {
        return false;
      }

      const last = this.selected[this.selected.length - 1];
      if (this.treeNode(last)) {
        return this.selected[this.selected.length - 1]['item']['purchasable'];
      } else {
        return this.selected[this.selected.length - 1]['purchasable'];
      }
    },

    selected() {
      const selected = [];
      this.treeNodes.forEach((node) => {

        const children = node.children.filter((child) => child.item.selected === true);
        if (children.length) {
          selected.push(children[0]);
        }

        node.itemSets.forEach((itemSet) => {
          const items = itemSet.items.filter((item) => item.selected === true);
          if (items.length) {
            selected.push(items[0]);
          }
        });

      });
      return selected;
    }
  },

  methods: {
    translate,

    image(item) {
      let itemCard = null;

      if(this.treeNode(item)) {
        itemCard = item.item.itemCard;
      } else {
        itemCard = item.itemCard;
      }

      if (itemCard === null) {
        return window.img.placeholder;
      }

      if (itemCard.media && itemCard.media.thumbnails) {
        const image = itemCard.media.thumbnails.filter((tb) => tb.width === 800);
        if (image.length) {
          return image[0].url;
        } else {
          return window.img.placeholder;
        }
      } else {
        return window.img.placeholder;
      }
    },


    treeNode(item) {
      return !!(item.item && item.item.itemCard);
    },

    addToCart() {
      if (!this.buttonEnabled) return;
      window.alert('Noch nicht implementiert!');
    }
  }
}
</script>