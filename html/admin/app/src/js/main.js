import Vue from 'vue';
import FeaturedProductsEditor from './components/FeaturedProductsEditor.vue';
import MediaBrowser from './components/MediaBrowser.vue';
import ProductImageSelector from './components/ProductImageSelector.vue';
import ProductReticleSelector from './components/ProductReticleSelector.vue';
import { TabsPlugin, ToastPlugin } from 'bootstrap-vue';
import '../css/main.css';
import { filter } from 'lodash';

Vue.use(TabsPlugin);
Vue.use(ToastPlugin);

Vue.component('media-browser', require('./components/MediaBrowser.vue').default);
Vue.component('featured-products-editor', FeaturedProductsEditor);
Vue.component('product-image-selector', ProductImageSelector);
Vue.component('product-reticle-selector', ProductReticleSelector);

$(document).ready(function(){
	if($('#app').length) {
		new Vue({
		    el: '#app',
		    components: {
		    	MediaBrowser
		    }
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
