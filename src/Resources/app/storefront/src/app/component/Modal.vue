<template>
  <transition name="fade">
  <div
      v-if="showModal"
      class="modal"
      v-bind:class="{show: showModal, 'eccb-show-front-modal': showModal}"
      tabindex="-1" role="dialog"
      aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 v-if="finishModalTitle !== '%%'" class="modal-title">{{ finishModalTitle }}</h5>
            <button v-on:click.prevent="onOk" type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body" v-html="finishModal"></div>
          <div class="modal-progress" v-bind:style="{width: progress+'%'}"></div>
          <div class="modal-footer" style="justify-content: space-between;  padding-right: 3em; padding-left: 3em;">
            <div>
              <div v-if="finishModalCheckbox">
                <input style="display: none;" type="checkbox" id="checkbox" v-model="checked">
                <label for="checkbox">{{ finishModalCheckbox }}</label>
              </div>

              <!-- show only on mobile -->
              <button v-on:click.prevent="onOk" style="min-width: min-content" type="button"
                      class="btn btn-primary d-block d-sm-none">
                <span v-if="finishModalCTA">{{ finishModalCTA }}</span>
                <span v-else>Ok</span>
              </button>
            </div>

            <button v-on:click.prevent="onOk" style="min-width: min-content" type="button"
                    class="btn btn-primary d-none d-sm-block">
              <span v-if="finishModalCTA">{{ finishModalCTA }}</span>
              <span v-else>Ok</span>
            </button>

          </div>
        </div>
    </div>
  </div>
  </transition>

</template>

<script>
import {bus} from "../../main";


export default {
  props: ['finishModalTitle', 'finishModal', 'finishModalActive', 'finishModalCTA', 'finishModalCheckbox', 'progress'],

  data() {
    return {
      checked: false
    }
  },

  methods: {
    onOk() {
      this.$emit('close-modal');
      if (this.checked) {
        sessionStorage.finishModalClicked = true;
      }
    }
  },

  computed: {
    showModal() {
      const storageNull = sessionStorage['finishModalClicked'] === undefined;
      return storageNull && this.finishModalActive;
    }
  }


}
</script>
