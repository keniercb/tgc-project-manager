import {defineStore} from "pinia";
import {ref} from "vue";
import type {AuditAction} from "../types/audit.ts";
import {auditApi} from "../api/audit.ts";

export const useAuditStore = defineStore('audit-trail', () => {
    const loading = ref<boolean>(false);
    const logs = ref<AuditAction[]>([]);
    const error = ref<string | null>(null);
    const pagination = ref({
        currentPage: 1,
        lastPage: 1,
        perPage: 5,
        total: 0
    });
    const fetchAuditActions = async (
        perPage: number,
        page: number,
        objectType: string,
        objectId: number
    ) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await auditApi.getAuditLogs({
                page,
                perPage,
                objectId,
                objectType
            });
            logs.value = response.data;
            pagination.value = {
                currentPage: response.meta.current_page,
                lastPage: response.meta.last_page,
                perPage: response.meta.per_page,
                total: response.meta.total
            };
        } catch (e) {
            error.value = 'Error loading logs';
        } finally {
            loading.value = false;
        }
    }
    const getActionColor = (action: string) => {
        if (action.includes('created')) return 'bg-green-100 text-green-800';
        if (action.includes('updated')) return 'bg-blue-100 text-blue-800';
        if (action.includes('deleted')) return 'bg-red-100 text-red-800';
        return 'bg-gray-100 text-gray-800';
    }
    const getActionLabel = (action: string) => {
        return action;
    }
    return {
        loading,
        logs,
        pagination,

        getActionColor,
        getActionLabel,
        fetchAuditActions
    }
});