import apiClient from "../api/axios.ts";
import type {LoginCredentials,  AuthResponse} from "../types/auth";
import type {ApiResponse} from "@/types";

export const authApi = {
    login: async (credentials: LoginCredentials) => {
        const response = await apiClient.post<ApiResponse<AuthResponse>>('/login', credentials)
        return response.data;
    },

    me: async (): Promise<AuthResponse> => {
        const response = await apiClient.get<ApiResponse<AuthResponse>>('/user/me');
        return response.data.data;
    }
};