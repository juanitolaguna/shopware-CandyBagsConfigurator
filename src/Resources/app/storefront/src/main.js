// import Vue from 'vue/dist/vue.min.js';
import Vue from 'vue/dist/vue.js';
import EccbApp from "./app/EccbApp.vue";

export const bus = new Vue()


new Vue({
    el: '#eccb_storefront_app',
    template: '<EccbApp />',
    components: {EccbApp},
});



