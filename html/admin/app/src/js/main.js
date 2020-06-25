import Vue from 'vue';
import FeaturedProductsEditor from './components/FeaturedProductsEditor.vue';
import MediaBrowser from './components/MediaBrowser.vue';
import ProductImageSelector from './components/ProductImageSelector.vue';
import ProductReticleSelector from './components/ProductReticleSelector.vue';
import CategoryEditor from './components/Category/Edit.vue';
import CategoryImageSelector from './components/Category/ImageSelector.vue';
import FormBuilder from './components/FormBuilder.vue';
import CategoryTreeView from './components/CategoryTreeView.vue';
import FileSelector from './components/FileSelector.vue';
import { TabsPlugin, ToastPlugin } from 'bootstrap-vue';
import '../css/main.scss';
import { filter } from 'lodash';
import { EventBus } from './EventBus.js';

Vue.use(TabsPlugin);
Vue.use(ToastPlugin);

Vue.component('media-browser', require('./components/MediaBrowser.vue').default);
Vue.component('featured-products-editor', FeaturedProductsEditor);
Vue.component('product-image-selector', ProductImageSelector);
Vue.component('product-reticle-selector', ProductReticleSelector);
Vue.component('category-editor', CategoryEditor);
Vue.component('category-image-selector', CategoryImageSelector);
Vue.component('category-tree-view', CategoryTreeView);
Vue.component('form-builder', FormBuilder);
Vue.component('file-selector', FileSelector);

window.EventBus = EventBus;

$(document).ready(function(){
	if($('#app').length) {
		new Vue({
		    el: '#app',
		    // components: {
		    // 	MediaBrowser
		    // }
		});
	}
});


$(document).ready(function(){
	const currentUrl = new URL(window.location.href);
	const currentPathName = currentUrl.pathname;
	const currentFirstPath = filter(currentPathName.split('/'))[0];
	$('.sub-menu a').each((idx, linkEl) => {
		const url = new URL(linkEl.getAttribute('href'));
		const linkFirstPath = filter(url.pathname.split('/'))[0];
		if(url.pathname == currentPathName || currentFirstPath == linkFirstPath) {
			$(linkEl).parent('li').addClass('font-weight-bold');
			const parentEl = $(linkEl).parents('.menu-item-has-children').find('> a');
			$(parentEl).click();
		}
	});
});
