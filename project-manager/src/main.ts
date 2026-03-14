import {createApp} from 'vue';
import {createPinia} from 'pinia';
import './style.css';
import App from './App.vue';
import router from './router';

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);


router.isReady().then(() => {
    import('../src/store/auth').then(({useAuthStore}) => {
        const authStore = useAuthStore();
        authStore.initAuth().then(r => console.log(r));
    });
});

app.mount('#app');