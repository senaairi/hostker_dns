import { createRouter, createWebHashHistory, RouteRecordRaw } from 'vue-router';

const routes: Array<RouteRecordRaw> = [
    {
        path: '/login',
        name: 'Login',
        meta: { title: '登录' },
        component: () => import('@/views/Login.vue')
    },
    {
        path: '/dns',
        name: 'Dns',
        meta: { title: 'DNS解析管理' },
        component: () => import('@/views/Dns.vue')
    }
    // {
    //     path: '/about',
    //     name: 'About',
    //     // route level code-splitting
    //     // this generates a separate chunk (about.[hash].js) for this route
    //     // which is lazy-loaded when the route is visited.
    //     component: () => import(/* webpackChunkName: "about" */ '')
    // }
];

const router = createRouter({
    history: createWebHashHistory(),
    routes
});

export default router;
