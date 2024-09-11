import './bootstrap';
import 'bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';
import Dashboard from './components/Dashboard.vue';
import Game from './components/Game.vue';
import Gacha from './components/Gacha.vue';
import axios from 'axios';


const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', redirect: '/top' },
        { path: '/top', component: Dashboard, meta: { requiresAuth: true } },
        { path: '/game', component: Game, meta: { requiresAuth: true } },
        { path: '/gacha', component: Gacha, meta: { requiresAuth: true } },
    ]
});

router.beforeEach(async (to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        try {
            await axios.get('/api/user');
            next(); // 認証成功時
        } catch (error) {
            console.error('認証エラー:', error);
            next('/'); // 認証失敗時はルートページにリダイレクト
        }
    } else {
        next(); // 認証不要のルートの場合
    }
});
const app = createApp(App);
app.use(router);
app.mount('#app');