export type ModuleStatus = 'draft' | 'validated' | 'ready_for_build';

export interface Module {
    id: number | undefined;
    name: string;
    objective: string;
    domain: string;
    status: ModuleStatus;
    projectId: number;
}

export interface ModuleFormData {
    id: number | undefined;
    name: string;
    objective: string;
    domain: string;
    projectId: number | undefined;
    fields : object | undefined;
}