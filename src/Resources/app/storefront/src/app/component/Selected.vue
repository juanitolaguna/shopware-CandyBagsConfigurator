<template>
  <div>

    <h3 class="mb-2">{{ snippet.selected }}</h3>

    <div class="card" v-if="stepSetThumbnail">
      <div class="row no-gutters ec-selection_card pb-1">
        <div class="w-25">
          <img :src="stepSetThumbnail" class="img-fluid" alt="">
        </div>
        <div class="col">
          <div class="card-block px-2 mt-2">
            <div class="selected-list-item-header">
              <h4>{{ translate(stepSet, 'name') }}</h4>
            </div>

            <div class="ec-price-selected" style="display: inline;" v-if="basePrice && basePrice > 0">
              {{ snippet.basePrice }}: {{ basePrice }} {{ currency }}
            </div>
          </div>
        </div>
      </div>
    </div>

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
                <img @click="removeItem(index)" :class="['ec-icon-selected']" style="width: 24px; height: 24px;"
                     :src="assets.close">
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
                <img @click="removeItem(index)" :class="['ec-icon-selected']" style="width: 24px; height: 24px;"
                     :src="assets.close">
              </div>

              <div class="ec-price-selected" style="display: inline;" v-if="price(item)">
                {{ price(item) }} {{ currency }}
              </div>
            </div>
          </div>
        </div>
      </template>

    </div>

    <div>
    <span
        v-if="hasProductDetails"
        :class="[config.pdButtonType]"
        style="cursor: pointer;"
        :style="config.pdButtonInlineStyle"
        @click.prevent="openProductData"
    >
      {{ snippet.productDetails }}
    </span><br><br>
    </div>

    <button @click.prevent="addToCart" type="button" class="btn btn-primary" :disabled="!buttonEnabled">
      {{ snippet.addToCart }}
    </button>

    <div class="ec-price-selected" style="display: inline;" v-if="calculatedPrice !== 0"><strong>{{ calculatedPrice }}
      {{ currency }}</strong></div>

    <ProductDataModal
        :modalActive="modalActive"
        :data="productData"
        @close-product-modal="closeProducDataModal"
    />


  </div>

</template>

<script>
import {translate, price} from "../utils/utils.js"
import ProductDataModal from "./ProductDataModal.vue";

export default {
  components: {
    ProductDataModal,
  },
  props: ["treeNodes", "stepSet", "config", "productData", "headers"],

  data() {
    return {
      modalActive: false,
    }
  },

  computed: {

    hasProductDetails() {
      return this.config.showProductDetailsInSelection && (this.productData.length > 0);
    },

    stepSetThumbnail() {
      if (this.stepSet.selectionBaseImage == null) return false;
      const thumbnail = this.stepSet.selectionBaseImage.thumbnails.filter((el) => el.width <= 800);
      return thumbnail.length ? thumbnail[0].url : window.img.placeholder;
    },

    basePrice() {
      return this.stepSet.price && this.stepSet.price[0].gross;
    },

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
      if (this.basePrice) price = this.basePrice;
      this.selected.forEach((item) => {
        if (item.itemCard) {
          price += this.price(item);
        } else {
          price += this.price(item.item);
        }
      });
      return (price !== 0) ? (price).toFixed(2) : 0;
    },

    currency() {
      return window.currencySymbol;
    },

    selected() {
      const selected = [];
      this.treeNodes.forEach((node) => {

        const children = node.children.filter((child) => child.item.selected === true);
        if (children.length) {
          children[0]['cardType'] = 'treeNodeCard'
          selected.push(children[0]);
        }

        node.itemSets.forEach((itemSet) => {
          const items = itemSet.items.filter((item) => item.selected === true);
          if (items.length) {
            items[0]['cardType'] = 'itemCard'
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
        const image = itemCard.media.thumbnails.filter((tb) => tb.width < 800);
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

    openProductData() {
      this.modalActive = true;
    },

    closeProducDataModal() {
      this.modalActive = false;
    },

    addToCart() {
      if (!this.buttonEnabled) return;


      const selected = {
            'selected' : this.selected,
            'price' : this.calculatedPrice,
            'stepSet' : this.stepSet
          }

      const requestOptions = {
        method: 'POST',
        headers: this.headers,
        body: JSON.stringify(selected),
        redirect: 'follow'
      };

      fetch(`/store-api/v{version}/eccb/add-line-item`, requestOptions)
          .then(result => result.text())
          .then(() => {
            this.openCartWidget();
          })
          .catch(error => console.log('error', error));
    },

    openCartWidget() {
      const offCanvasCart = document.querySelectorAll('[data-offcanvas-cart=true]')[0];
      const offCanvasCartPLugin = window.PluginManager.getPluginInstanceFromElement(offCanvasCart, 'OffCanvasCart');
      offCanvasCartPLugin.openOffCanvas(window.router['frontend.cart.offcanvas'], false);
    }
  }
}
</script>