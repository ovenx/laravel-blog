import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)
import Layout from './layouts/Home.vue'
import {getToken} from "./utils/auth";
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

export const constantRoutes = [
    {
        path: '/login',
        component: () => import(/* webpackChunkName: "group-index" */ './views/login/index'),
        hidden: true
    },
    {
        path: '/logout',
        component: () => import(/* webpackChunkName: "group-index" */ './views/login/index'),
        hidden: true
    },
    {
        path: '/',
        component: Layout,
        redirect: '/dashboard',
        hidden: true,
        children: [
            {
                path: 'dashboard',
                component: () => import(/* webpackChunkName: "group-index" */ './views/dashboard/index'),
                name: 'dashboard',
                meta: {title: 'Dashboard', icon: 'dashboard'}
            }
        ]
    },
    {
        path: '/post',
        component: Layout,
        children: [
            {
                path: '',
                component: () => import(/* webpackChunkName: "group-index" */ './views/post/index'),
                name: 'post',
                meta: {title: 'Post', icon: 'profile'}
            },
            {
                path: 'add',
                hidden: true,
                component: () => import(/* webpackChunkName: "group-index" */ './views/post/add'),
                name: 'post',
                meta: {title: 'New Post'}
            },
            {
                path: 'edit/:id',
                hidden: true,
                component: () => import(/* webpackChunkName: "group-index" */ './views/post/add'),
                name: 'editPost',
                meta: {title: 'Edit Post'}
            }
        ]
    },
    {
        path: '/category',
        component: Layout,
        children: [
            {
                path: '',
                component: () => import(/* webpackChunkName: "group-index" */ './views/category/index'),
                name: 'category',
                meta: {title: 'Category', icon: 'folder'}
            }
        ]
    },
    {
        path: '/tag',
        component: Layout,
        children: [
            {
                path: '',
                component: () => import(/* webpackChunkName: "group-index" */ './views/tag/index'),
                name: 'tag',
                meta: {title: 'Tag', icon: 'tag'}
            }
        ]
    },
    {
        path: '/setting',
        component: Layout,
        children: [
            {
                path: '',
                component: () => import(/* webpackChunkName: "group-index" */ './views/setting/index'),
                name: 'setting',
                meta: {title: 'Setting', icon: 'setting'}
            }
        ]
    }
]

export const asyncRoutes = []

const createRouter = () => new Router({
    scrollBehavior: () => ({y: 0}),
    routes: constantRoutes
})

const router = createRouter()

export function resetRouter() {
    const newRouter = createRouter()
    router.matcher = newRouter.matcher // reset router
}

NProgress.configure({ showSpinner: false }) // NProgress Configuration
const whiteList = ['/login']
router.beforeEach(async (to, from, next) => {
    NProgress.start()
    const hasToken = getToken()
    if (hasToken) {
        if (to.path == '/login') {
            next({path: '/'})
        } else {
            next()
        }
    } else {
        if (whiteList.indexOf(to.path) !== -1) {
            next()
        } else {
            next(`/login?redirect=${to.path}`)
            NProgress.done()
        }
    }

})

router.afterEach(() => {
    NProgress.done()
})

export default router