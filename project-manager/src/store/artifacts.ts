import {defineStore} from "pinia";
import {ref} from "vue";
import type {Artifact, ArtifactFormData} from "../types/artifact.ts";
import {artifactApi} from "../api/artifacts.ts";

export const useArtifactsStore = defineStore('artifacts', () => {
    const loading = ref<boolean>(false);
    const artifacts = ref<Artifact[]>([]);
    const error = ref<string | null>(null);
    const pagination = ref({
        currentPage: 1,
        lastPage: 1,
        perPage: 10,
        total: 0
    });
    const fetchArtifacts = async (projectId: number, page: number = 1, perPage: number = 5, search: string = '') => {
        loading.value = true;
        error.value = null;
        try {
            const resp = await artifactApi.getAllArtifacts({
                search,
                projectId,
                perPage,
                page
            });
            artifacts.value = resp.data;
            pagination.value = {
                currentPage: resp.meta.current_page,
                lastPage: resp.meta.last_page,
                perPage: resp.meta.per_page,
                total: resp.meta.total
            };
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error loading artifacts';
        } finally {
            loading.value = false;
        }

    }
    const createArtifacts = async (payload: ArtifactFormData) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await artifactApi.createArtifact(payload);
            return {success: true, data: response.data};
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error creating artifacts';
        } finally {
            loading.value = false;
        }
    }
    const updateArtifacts = async (payload: ArtifactFormData) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await artifactApi.updateArtifact(payload);
            return {success: true, data: response.data};
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error updating artifacts';
            return {failure: true, data: err.response?.data};
        } finally {
            loading.value = false;
        }
    }
    const deleteArtifacts = async (artifactId: number) => {
        loading.value = true;
        error.value = null;
        try {
            await artifactApi.deleteArtifact(artifactId);
            return {
                success: true
            }
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Error deleting artifacts';
        } finally {
            loading.value = false;
        }
    }
    return {
        artifacts,
        loading,
        error,
        pagination,
        updateArtifacts,
        createArtifacts,
        fetchArtifacts,
        deleteArtifacts
    }
});