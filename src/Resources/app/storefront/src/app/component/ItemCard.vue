<template>
  <div @click="onSelect" :class="['card ec-card', {selected: child.selected}]">
    <div class="card-image-top-wrapper" style="width: inherit">
      <img class="card-img-top" :src="image" :alt="child.itemCard.name">
    </div>
    <div class="card-body">
      <h5 class="card-title"
          :style="{paddingBottom: '0px'}">
        {{ translate(child.itemCard, 'name') }}
      </h5>
      <div class="ec-price" v-if="_price"><strong>{{_price}} {{currency}}</strong></div>
      <img class="ec-icon" v-if="displayNext" :src="assets.next" alt="next" title="next">
    </div>
  </div>
</template>

<script>
import {translate, price} from "../utils/utils.js"

export default {
  props: ["child", "assets", "childNode", "parentNode"],

  computed: {
    displayNext() {
      return !this.terminal() && (this.nextStep() || this.hasNextRootNodeChildren())
    },

    _price() {
      return this.price(this.child);
    },

    currency() {
      return window.currencySymbol;
    },

    image() {
      const itemCard = this.child.itemCard;
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
    }
  },

  methods: {
    price,
    translate,
    terminal() {
      return this.child.terminal;
    },

    nextStep() {
      const childNodeHasChildren = !!this.childNode.children.length;
      const childNodeHasItemSets = !!this.childNode.itemSets.length;
      return childNodeHasChildren || childNodeHasItemSets;
    },

    hasNextRootNodeChildren() {
      const rootNodes = this.$parent.rootNodes;
      const next = typeof rootNodes[this.parentNode.rootNodeIndex + 1] !== "undefined";
      return next &&
          (rootNodes[this.parentNode.rootNodeIndex + 1].children.length
              || rootNodes[this.parentNode.rootNodeIndex + 1].itemSets.length)
    },


    onSelect() {
      if (!this.displayNext) {
        return this.$emit('terminal-node');
      }

      if (this.nextStep()) {
        return this.$emit('next-node');
      }

      if (this.hasNextRootNodeChildren()) {
        return this.$emit('next-root-node');
      }
    }
  }
}
</script>