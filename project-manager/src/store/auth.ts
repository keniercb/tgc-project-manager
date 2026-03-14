import {defineStore} from 'pinia';
import {ref, computed} from 'vue';
import {authApi} from '../api/auth';
import type {User, LoginCredentials} from '../types/auth';

const TOKEN_KEY = 'auth_token';
const USER_KEY = 'auth_user';

export const useAuthStore = defineStore('auth', () => {
    // Estado
    const user = ref<User | null>(null);
    const token = ref<string | null>(localStorage.getItem(TOKEN_KEY));
    const loading = ref<boolean>(false);
    const authenticated = ref<boolean>(false);
    const error = ref<string | null>(null);

    // Getters
    const isAuthenticated = computed(() => {
        console.log(authenticated.value)
        return !!token.value && !!user.value
    });
    const userName = computed(() => user.value ?? 'Guest user');
    const userRole = computed(() => user.value?.role ?? 'guest');

    // Actions
    const initAuth = async () => {
        const storedToken = localStorage.getItem(TOKEN_KEY);
        const storedUser = localStorage.getItem(USER_KEY);

        if (storedToken && storedUser) {
            token.value = storedToken;
            user.value = JSON.parse(storedUser);
            authenticated.value = true;

            import('../api/axios').then(({default: apiClient}) => {
                apiClient.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
            });
            return;
        }
        await logout();
    };


    const login = async (credentials: LoginCredentials) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await authApi.login(credentials);

            token.value = response.token;
            user.value = response.user;

            localStorage.setItem(TOKEN_KEY, response.token);
            localStorage.setItem(USER_KEY, JSON.stringify(response.user));

            const apiClient = (await import('../api/axios')).default;
            apiClient.defaults.headers.common['Authorization'] = `Bearer ${response.token}`;
            authenticated.value = true;
            return {success: true};
        } catch (err: any) {
            authenticated.value = false;
            error.value = err.response?.data?.message || 'Error al iniciar sesión';
            return {success: false, error: error.value};
        } finally {
            loading.value = false;
        }
    };


    const logout = async () => {
        token.value = null;
        user.value = null;
        error.value = null;

        localStorage.removeItem(TOKEN_KEY);
        localStorage.removeItem(USER_KEY);
        authenticated.value = false;
        const apiClient = (await import('../api/axios')).default;
        delete apiClient.defaults.headers.common['Authorization'];
    };

    const clearError = () => {
        error.value = null;
    };

    return {
        user,
        token,
        loading,
        error,
        isAuthenticated,
        userName,
        userRole,
        initAuth,
        login,
        logout,
        clearError
    };
});