import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import firstPage from './components/pages/myFirstVuepage'
import newRoutePage from './components/pages/newRoutePage'
import hooks from './components/pages/basic/hooks'

import home from './components/pages/home'
import tags from './admin/pages/tags'
import category from './admin/pages/category'
import adminusers from "./admin/pages/adminusers";

const routes = [
    //admin project routes
    {
        path: '/',
        component: home,
    },
    {
        path: '/tags',
        component: tags
    },
    {
        path: '/category',
        component: category
    },
    {
        path: '/adminusers',
        component: adminusers
    },
    //test routes
    {
        path: '/my-new-vue-route',
        component: firstPage,
    },
    {
        path: '/new-route',
        component: newRoutePage
    },
    //vue hooks
    {
        path: '/hooks',
        component: hooks
    }
]

export default new Router({
    mode: 'history',   // remove the hashtag from url
    routes
})
