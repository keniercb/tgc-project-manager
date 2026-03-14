import type {Module, ModuleFormData} from "@/types/modules.ts";
import apiClient from "./axios.ts";
import type {ApiResponse, PaginatedResponse} from "@/types";

interface FetchModuleParams {
    perPage: number,
    page: number,
    projectId: number,
}

export const moduleApi = {
        createModule: async function (params: ModuleFormData) {
            const response = await apiClient.post<ApiResponse<Module>>("/modules", params);
            return response.data;
        },
        fetchModules: async function (params: FetchModuleParams) {
            const response = await apiClient.get<PaginatedResponse<Module>>('/modules', {
                params: params,
            });
            return response.data;
        },
        updateModule: async function (params: ModuleFormData) {
            const response = await apiClient.put<ApiResponse<Module>>(`/modules/${params.id}`, params);
            return response.data;
        },
        validate: async (id: number) => {
            const response = await apiClient.post<ApiResponse<Module>>(`/modules/validate/${id}`);
            return response.data;
        },
        deploy: async (id: number) => {
            const response = await apiClient.post<ApiResponse<Module>>(`/modules/deploy/${id}`);
            return response.data;
        }
    }
;