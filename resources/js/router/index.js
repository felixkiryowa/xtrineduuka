import VueRouter from "vue-router";
import ProductsComponent from '../components/ProductsPage/ProductsComponent.vue';
import TransactionsComponent from '../components/TransactionsPage/TransactionsComponent.vue';
import ExpenseComponent from '../components/ExpensePage/ExpenseComponent.vue';

let routes = [
   {
       path:'/products',
       component:ProductsComponent
   },
   {
       path:'/transactions',
       component:TransactionsComponent 
   },
   {
    path:'/expenses',
    component:ExpenseComponent
}
];

const router = new VueRouter({
    mode:'history',
    routes
})

export default  router;