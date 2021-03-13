// import Vue from 'vue/dist/vue.min.js';
import Vue from 'vue/dist/vue.js';
import EccbApp from "./app/EccbApp.vue";


export const bus = new Vue()


const el = document.getElementById('eccb_storefront_app')

if (el !== null) {
    new Vue({
        el: '#eccb_storefront_app',
        template: '<EccbApp />',
        components: {EccbApp}
    });
}



