export type ArtifactType = 'strategic_alignment' | 'big_picture' | 'domain_breakdown' | 'module_matrix' |
    'module_engineering' | 'system_architecture' | 'phase_scope';
export type ArtifactStatus = 'not started' | 'in progress' | 'blocked' | 'done'

export interface Artifact {
    id: number;
    type: string;
    status: string,
    owner: string,
    completedAt: string,
    projectId: number,
    content: string
}

export interface ArtifactFormData {
    id: number | undefined;
    type: string;
    status: string;
    projectId: number|undefined;
    content: string;
}