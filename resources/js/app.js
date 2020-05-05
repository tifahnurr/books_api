
require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VueAxios from 'vue-axios';
import axios from 'axios';
import Vue from 'vue'
import VueCookies from 'vue-cookies'
import App from './app.vue';

Vue.use(VueAxios, axios);
Vue.use(VueCookies)

import HomeComponent from './components/HomeComponent.vue';
import LoginComponent from './components/LoginComponent.vue';
import BooksComponent from './components/BooksComponent.vue';
import BookDetailComponent from './components/BookDetailComponent.vue';
import CategoryComponent from './components/CategoryComponent.vue';
import CommentsComponent from './components/AllCommentComponent.vue';

const routes = [
  {
      name: 'home',
      path: '/',
      component: HomeComponent
  },
  {
      name: 'login',
      path: '/login',
      component: LoginComponent
  },
  {
      name: 'books',
      path: '/books',
      component: BooksComponent
  },
  {
      name: 'categories',
      path: '/categories',
      component: CategoryComponent
  },
  {
      name: 'book_detail',
      path: '/books/:book_id',
      component: BookDetailComponent,
      props: true
  },
  {
      name: 'comments',
      path: '/comments',
      component: CommentsComponent
  }
];

const router = new VueRouter({ mode: 'history', routes: routes});
const app = new Vue(Vue.util.extend({ router }, App)).$mount('#app');

router.beforeEach((to, from, next) => {
    // console.log(to.name);
    if (to.name=="login" || to.name=="home"){
        next();
    } else {
        console.log('beforeEach');
        if (Vue.$cookies.get('token') != null) {
            next();
        } else {
            next({
                name: 'login'
            });
        }
    }
});