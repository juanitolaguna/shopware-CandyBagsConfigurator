<template>
  <div>

    <h3 class="mb-2">{{ snippet.selected }}</h3>
    <p v-if="!selected.length" style="font-size: 2rem">...</p>

    <div v-for="(item, index) in selected" class="card">

      <template v-if="treeNode(item)">
        <div class="row no-gutters ec-selection_card pb-1">
          <div class="w-25">
            <img :src="image(item)" class="img-fluid" alt="">
          </div>
          <div class="col">
            <div class="card-block px-2 mt-2">
              <div class="selected-list-item-header">
                <h4>{{ translate(item.item.itemCard, 'name') }}</h4>
                <img @click="removeItem(index)" :class="['ec-icon-selected']" style="width: 24px; height: 24px;" :src="assets.close">
              </div>

              <div class="ec-price-selected" style="display: inline;" v-if="price(item.item)">
                {{ price(item.item) }} {{ currency }}
              </div>
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

              <div class="selected-list-item-header">
                <h4>{{ translate(item.itemCard, 'name') }}</h4>
                <img @click="removeItem(index)" :class="['ec-icon-selected']" style="width: 24px; height: 24px;" :src="assets.close">
              </div>

              <div class="ec-price-selected" style="display: inline;" v-if="price(item)">
                {{ price(item) }} {{ currency }}
              </div>
            </div>
          </div>
        </div>
      </template>

    </div>

    <button @click.prevent="addToCart" type="button" class="btn btn-primary" :disabled="!buttonEnabled">
      {{ snippet.addToCart }}
    </button>

    <div class="ec-price-selected" style="display: inline;" v-if="calculatedPrice !== 0"><strong>{{ calculatedPrice }}
      {{ currency }}</strong></div>


  </div>
</template>

<script>
import {translate, price} from "../utils/utils.js"

export default {
  props: ["treeNodes"],

  computed: {
    assets() {
      return window.img;
    },

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

    calculatedPrice() {
      let price = 0;
      this.selected.forEach((item) => {
        if (item.itemCard) {
          price += this.price(item);
        } else {
          price += this.price(item.item);
        }
      });
      return price;
    },

    currency() {
      return window.currencySymbol;
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
    },

    snippet() {
      return window.snippets;
    }
  },

  methods: {
    price,
    translate,

    removeItem(item) {
      this.$emit('remove-item', item);
    },

    image(item) {
      let itemCard = null;

      if (this.treeNode(item)) {
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