import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)
import Layout from './layouts/Home.vue'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

export const constantRoutes = [
    {
        path: '/',
        component: Layout,
        children: [
            {
                path: '',
                component: () => import(/* webpackChunkName: "group-index" */ './views/index'),
                name: 'index',
                meta: {title: 'index'}
            }
        ],
        hidden: true
    },
    {
        path: '/posts/:id',
        component: Layout,
        children: [
            {
                path: '',
                component: () => import(/* webpackChunkName: "group-index" */ './views/post'),
                name: 'post',
                meta: {title: 'post'}
            }
        ],
    },
    {
        path: '/categories',
        component: Layout,
        children: [
            {
                path: '',
                component: () => import(/* webpackChunkName: "group-index" */ './views/categories'),
                name: 'categories',
                meta: {title: 'categories'}
            },
            {
                path: ':id',
                component: () => import(/* webpackChunkName: "group-index" */ './views/postCategory'),
                name: 'postCategory',
                meta: {title: 'category posts'}
            }
        ],
    },
    {
        path: '/tags',
        component: Layout,
        children: [
            {
                path: '',
                component: () => import(/* webpackChunkName: "group-index" */ './views/tags'),
                name: 'tags',
                meta: {title: 'tags'}
            },
            {
                path: ':id',
                component: () => import(/* webpackChunkName: "group-index" */ './views/postTag'),
                name: 'postTag',
                meta: {title: 'tag posts'}
            }
        ],
    },
    {
        path: '/archives',
        component: Layout,
        children: [
            {
                path: '',
                component: () => import(/* webpackChunkName: "group-index" */ './views/archives'),
                name: 'archives',
                meta: {title: 'archives'}
            }
        ],
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
    next()
})

router.afterEach(() => {
    NProgress.done()
})

export default router
