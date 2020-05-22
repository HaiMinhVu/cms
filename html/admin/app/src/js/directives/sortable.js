import Vue, {DirectiveOptions} from 'vue';
// import {Vue} from "vue-property-decorator";
import { Sortable } from '@shopify/draggable';
// console.log(Vue)
// const sortableDirective = (DirectiveOptions) => {
//    inserted: (el, node, vNode) => {
//       new Sortable(el, node.value);
//    }
// };

Vue.directive('sortable', DirectiveOptions => {
   inserted: (el, node, vNode) => {
      new Sortable(el, node.value);
   }
});

// export default sortableDirective;