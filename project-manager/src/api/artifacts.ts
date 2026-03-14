import type {ApiResponse, PaginatedResponse} from "@/types";
import type {Artifact, ArtifactFormData} from "../types/artifact.ts";
import apiClient from "../api/axios.ts";

interface ArtifactsParams {
    search: string;
    projectId: number;
    perPage: number;
    page: number;
}

export const artifactApi = {
    getAllArtifacts: async (params: ArtifactsParams): Promise<PaginatedResponse<Artifact>> => {
        const response = await apiClient.get<PaginatedResponse<Artifact>>(`/artifact/list`, {
            params: params,
        });
        return response.data;
    },
    createArtifact: async (params: ArtifactFormData): Promise<ApiResponse<Artifact>> => {
        const response = await apiClient.post(`/artifact/create`, params);
        return response.data;
    },
    updateArtifact: async (params: ArtifactFormData): Promise<ApiResponse<Artifact>> => {
        const response = await apiClient.put(`/artifact/update/${params.id}`, params);
        return response.data;
    },
    deleteArtifact: async (id: number): Promise<void> => {
        await apiClient.delete(`/artifact/${id}`);
    }
}