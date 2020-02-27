/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import { InertiaApp } from '@inertiajs/inertia-vue'
window.Vue = require('vue');
Vue.use(InertiaApp)
import axios from 'axios'
import VueAxios from 'vue-axios'
Vue.use(VueAxios, axios)

//Vue.component('RecordList', require('./Pages/RecordList.vue').default);
var fields = ['Scaffold','Text','Select','Image','Repeater','ManySelect','Textarea','Wysiwyg'];
fields.forEach(f=>{
  Vue.component(f+'Field',require('./fields/'+f+'Field.vue').default);  
})

import VueTrix from "vue-trix";
Vue.component('VueTrix',VueTrix);
//Vue.component('VueTrix',import('vue-trix'));  


Vue.prototype.window = window


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/*const app = new Vue({
    el: '#app',
});*/
const app = document.getElementById('app')


window.scaffoldApp = new Vue({
    render: h => h(InertiaApp, {
      props: {
        initialPage: JSON.parse(app.dataset.page),
        resolveComponent: name => require(`./Pages/${name}`).default,
      },
    }),
  }).$mount(app)

  window.axios = axios;