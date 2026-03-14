import {defineStore} from "pinia";

import {ref} from "vue";
import type {Module, ModuleFormData} from "../types/modules.ts";
import {moduleApi} from "../api/module.ts";

export const useModuleStore = defineStore('module', () => {
    const modules = ref<Module[]>([]);
    const loading = ref<boolean>(false);
    const error = ref<string | null>(null);
    const pagination = ref({
        currentPage: 1,
        lastPage: 1,
        perPage: 5,
        total: 0
    });
    const createModule = async (moduleData: ModuleFormData) => {
        loading.value = true;
        error.value = '';
        try {
            const result = await moduleApi.createModule(moduleData);
            return {
                success: true,
                data: result.data,
            }
        } catch (e: any) {
            return {
                failure: true,
                error: e.response?.data?.error,
            }
        } finally {
            loading.value = false;
        }
    }
    const fetchModules = async (projectId: number, page: number, perPage: number = 5) => {
        loading.value = true;
        error.value = '';
        try {
            const response = await moduleApi.fetchModules({
                projectId: projectId,
                page : page,
                perPage: perPage,
            });
            modules.value = response.data;
            pagination.value = {
                currentPage: response.meta.current_page,
                lastPage: response.meta.last_page,
                perPage: response.meta.per_page,
                total: response.meta.total
            };
        } catch (e: any) {
            error.value = e?.response?.data?.error || 'Error loading modules.';
        } finally {
            loading.value = false;
        }
    }

    const updateModule = async (module: ModuleFormData) => {
        loading.value = true;
        error.value = '';
        try {
            const result = await moduleApi.updateModule(module);
            return {
                success: true,
                data: result.data,
            }
        } catch (e: any) {
            return {
                failure: true,
                error: e.response?.data?.error,
            }
        } finally {
            loading.value = false;
        }
    }

    const validateModule = async (module: Module) => {
        try {
            await moduleApi.validate(module.id);
            return {
                success: true
            }
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Error validation module';
            return {
                failure: true,
                data: e.response?.data?.message || 'Error validation module'
            };
        }
    }

    const deployModule = async (module: Module) => {
        try {
            await moduleApi.deploy(module.id);
            return {
                success: true
            }
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Error deploying module';
            return {
                failure: true,
                data: e.response?.data?.message || 'Error deploying module'
            };
        }
    }

    return {
        modules,
        loading,
        error,
        pagination,
        createModule,
        updateModule,
        fetchModules,
        validateModule,
        deployModule
    }
});
