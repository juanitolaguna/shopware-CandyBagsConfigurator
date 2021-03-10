<template>
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
</template>

<script>

export default {
  props: ["child", "assets"]
}

</script>