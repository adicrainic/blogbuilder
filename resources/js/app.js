require('./bootstrap');

// import Vue from 'vue'
import Vue from 'vue';
import router from "./router";
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
import common from "./common";
import store from "./store";
import editor from "vue-editor-js/src/index";


Vue.mixin(common);
Vue.use(ViewUI);
Vue.component('mainapp',require('./components/mainapp.vue').default)
Vue.use(editor)

const app = new Vue ({
    el: '#app',
    router,
    store,
    editor
})
