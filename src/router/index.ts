import { createRouter, createWebHashHistory, RouteRecordRaw } from 'vue-router';

const routes: Array<RouteRecordRaw> = [
    { path: '/', component: import('@/views/Index.vue'), meta: { title: '首页' } },
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
];

const router = createRouter({
    history: createWebHashHistory(),
    routes
});

export default router;
