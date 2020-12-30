/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import router from './router';
import moment from "moment";
import VueProgressBar from "vue-progressbar";
import { Form, HasError, AlertError } from "vform";
import Swal from "sweetalert2";
import {VTable,VPagination} from 'vue-easytable';
import VueRouter from 'vue-router';


window.Form = Form;
window.Swal = Swal;
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: toast => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    }
});
window.Toast = Toast;
window.Fire = new Vue();
//ADDING LOGIC TO HANDLE THE FORMS
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.use(VueProgressBar, {
    color: "rgb(102, 153, 0)",
    failedColor: "red",
    height: "2px"
});
//TELL VIEW TO USE VUE router
Vue.use(VueRouter);


//ADDING A DATE FILTER
Vue.filter("customDate", function(created_at) {
    return moment(created_at).format("MMMM Do YYYY");
});
//CHANGE TEXT TO UPPER CASE
Vue.filter("upText", function(text) {
    return text.toUpperCase();
});
//CONVERT TO NUMBER FORMAT
Vue.filter("formatNumber", function(value) {
    let nf = new Intl.NumberFormat();

    return nf.format(value);
})

//ADDING TABLE LIST HANDLER
Vue.component(VTable.name, VTable)
Vue.component(VPagination.name, VPagination)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    router,
    el: '#app',
});
