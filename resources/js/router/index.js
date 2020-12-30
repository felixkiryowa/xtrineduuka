import VueRouter from "vue-router";
import ProductsComponent from '../components/ProductsPage/ProductsComponent.vue';
import TransactionsComponent from '../components/TransactionsPage/TransactionsComponent.vue';

let routes = [
   {
       path:'/products',
       component:ProductsComponent
   },
   {
       path:'/transactions',
       component:TransactionsComponent 
   }
];

const router = new VueRouter({
    mode:'history',
    routes
})

export default  router;