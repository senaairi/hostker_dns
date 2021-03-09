import { createRouter, createWebHashHistory, RouteRecordRaw } from 'vue-router';
import Index from '@/views/Index.vue';
import Login from '@/views/Login.vue';
import Dns from '@/views/Dns.vue';

const routes: Array<RouteRecordRaw> = [
    { path: '/', component: Index, meta: { title: '首页' } },
    { path: '/login', name: 'Login', meta: { title: '登录' }, component: Login },
    { path: '/dns', name: 'Dns', meta: { title: 'DNS解析管理' }, component: Dns }
];

const router = createRouter({
    history: createWebHashHistory(),
    routes
});

export default router;
