export interface AuditAction {
    id: number;
    action: string;
    userName: string;
    date: string
}

export interface AuditParams {
    page: number;
    perPage: number;
    objectId: number;
    objectType: string;
}