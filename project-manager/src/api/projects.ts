import apiClient from "./axios.ts";
import type {ApiResponse, PaginatedResponse} from "@/types";
import type {Project, ProjectFormData} from "../types/project.ts";

interface ProjectParams {
    perPage: number;
    page: number;
    search: string;
    status: string;
}

export const projectsApi = {
    getAllProjects: async (params : ProjectParams): Promise<PaginatedResponse<Project>> => {
        const response = await apiClient.get<PaginatedResponse<Project>>('/projects', {
            params: params,
        });
        return response.data;
    },
    deleteProject: async (id: number): Promise<ApiResponse<void>> => {
        const response = await apiClient.delete<ApiResponse<void>>(`/projects/archive/${id}`);
        return response.data;
    },
    createProject: async (payload: ProjectFormData): Promise<ApiResponse<Project>> => {
        const response = await apiClient.post<ApiResponse<Project>>('/projects', payload);
        return response.data;
    },
    updateProject: async (id: number, payload: Partial<ProjectFormData>): Promise<ApiResponse<Project>> => {
        const response = await apiClient.put<ApiResponse<Project>>(`/projects/${id}`, payload);
        return response.data;
    },
    getProjectInfo : async (id: number): Promise<ApiResponse<Project>> => {
        const response = await apiClient.get<ApiResponse<Project>>(`/projects/show/${id}`);
        return response.data;
    }
}