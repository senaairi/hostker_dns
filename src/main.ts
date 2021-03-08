import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import { VueCookieNext } from 'vue-cookie-next';
import ElementPlus from 'element-plus';
import locale from 'element-plus/lib/locale/lang/zh-cn';
import 'element-plus/lib/theme-chalk/index.css';

router.beforeEach((to, from, next) => {
    document.title = to.meta.title as string;
    next();
});

createApp(App)
.use(store)
.use(router)
.use(VueCookieNext)
.use(ElementPlus, { locale })
.mount('#app');
