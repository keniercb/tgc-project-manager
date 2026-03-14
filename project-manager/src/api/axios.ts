import axios from "axios";

const apiClient = axios.create(
    {
        baseURL: import.meta.env.VITE_API_URL,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }
    }
);

apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            console.warn('No autorizado, redirigiendo...');
        }
        return Promise.reject(error);
    }
);

export default apiClient;