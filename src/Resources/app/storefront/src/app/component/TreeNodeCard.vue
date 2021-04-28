<template>
  <div @click="onSelect" :class="['card ec-card ', {selected: child.item.selected}]">
    <div class="card-image-top-wrapper" style="width: inherit">
      <img class="card-img-top" :src="image" :alt="child.item.itemCard.name">
    </div>
    <div class="card-body">
      <h5 class="card-title"
          :style="{paddingBottom: '0px'}">
        {{ translate(child.item.itemCard, 'name') }} [tnc]
      </h5>
      <div class="ec-price" v-if="_price"><strong>{{_price}} {{currency}}</strong></div>
      <img class="ec-icon" v-if="displayNext" :src="assets.next" alt="next" title="next">
    </div>
  </div>
</template>

<script>
import {translate, price} from "../utils/utils.js"

export default {
  props: ["child", "assets", "parentNode"],

  computed: {
    displayNext() {
      return !this.terminal() && (this.nextStep() || this.hasNextRootNodeChildren())
    },

    _price() {
      return this.price(this.child.item);
    },

    currency() {
      return window.currencySymbol;
    },

    testHasRootNodeChildren() {
      return this.hasNextRootNodeChildren();
    },

    image() {
      const itemCard = this.child.item.itemCard;
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
      return this.child.item.terminal;
    },

    nextStep() {
      const childNodeHasChildren = !!this.child.children.length;
      const childNodeHasItemSets = !!this.child.itemSets.length;

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