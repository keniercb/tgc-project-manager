import {createRouter, createWebHistory} from 'vue-router';
import type {RouteRecordRaw} from 'vue-router';
import {useAuthStore} from '../store/auth';

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        name: 'Projects',
        component: () => import('../pages/ProjectList.vue'),
        meta: {public: false}
    },
    {
        path: '/project-detail',
        name: 'Project Details',
        component: () => import('../pages/ProjectView.vue'),
        meta: {public: false}
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('../pages/LoginPage.vue'),
        meta: {public: true},
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});
let authInitialized = false;
router.beforeEach(async (to) => {
    const auth = useAuthStore();
    if (!authInitialized) {
        await auth.initAuth();
        authInitialized = true;
    }
    if (!to.meta.public && !auth.isAuthenticated) {
        return {name: 'Login'};
    }
    if (to.name === 'Login' && auth.isAuthenticated) {
        return {name: 'Projects'};
    }
});
export default router;