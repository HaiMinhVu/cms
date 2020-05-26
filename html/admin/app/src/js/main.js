import Vue from 'vue';
import FeaturedProductsEditor from './components/FeaturedProductsEditor.vue';
import { ToastPlugin } from 'bootstrap-vue';
import '../css/main.css';

Vue.use(ToastPlugin);

new Vue({
    el: '#app',
    render: h => h(FeaturedProductsEditor)
});