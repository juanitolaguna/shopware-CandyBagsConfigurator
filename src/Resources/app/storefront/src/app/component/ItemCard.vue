<template>
  <div @click="onSelect" :class="['card ec-card', {selected: child.selected}]">
    <div class="card-image-top-wrapper" style="width: inherit">
      <img class="card-img-top" :src="child.itemCard.media.url" :alt="child.itemCard.name">
    </div>
    <div class="card-body">
      <h5 class="card-title"
          :style="{paddingBottom: '0px'}">
        {{ child.itemCard.name }}
      </h5>
      <img class="ec-icon" v-if="nextStep" :src="assets.next" alt="next">
      <img class="ec-icon" v-else :src="assets.last" alt="last">
    </div>
  </div>
</template>

<script>
export default {
  props: ["child", "assets", "childNode"],

  computed: {
    nextStep() {
      const childNodeHasChildren = !!this.childNode.children.length;
      const childNodeHasItemSets = !!this.childNode.itemSets.length;
      return childNodeHasChildren || childNodeHasItemSets;
    }
  },

  methods: {
    onSelect() {
      this.$emit('on-select');
    }
  }
}
</script>