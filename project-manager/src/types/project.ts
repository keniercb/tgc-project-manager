export type ProjectStatus = 'draft' | 'discovery' | 'delivered' | 'execution';

export interface Project {
    id: number;
    name: string;
    client: string;
    status: ProjectStatus;
    statusDescription: string;
    createdAt: string;
    modulesCount: number;
    artifactsCount: number;
}

export interface ProjectFormData {
    name: string;
    status: ProjectStatus;
    clientName: string;
}

export interface ProjectFilters {
    search: string;
    status: ProjectStatus | 'all';
    page: number;
    perPage: number;
}

export interface ProjectState {
    projects: Project[];
    currentProject: Project | null;
    loading: boolean;
    error: string | null;
    pagination: {
        currentPage: number;
        lastPage: number;
        perPage: number;
        total: number;
    };
}