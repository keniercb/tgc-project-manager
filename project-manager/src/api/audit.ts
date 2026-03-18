import type {PaginatedResponse} from "@/types";
import type {AuditAction, AuditParams} from "../types/audit.ts";
import apiClient from "../api/axios.ts";



export const auditApi = {
    getAuditLogs: async (params: AuditParams): Promise<PaginatedResponse<AuditAction>> => {
        const response = await apiClient.get<PaginatedResponse<AuditAction>>(`/audit`, {
                params: params,
            }
        );
        return response.data;
    }
}