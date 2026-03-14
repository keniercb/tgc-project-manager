import {defineStore} from 'pinia';
import {ref, computed} from 'vue';
import {projectsApi} from '../api/projects';
import type {Project, ProjectFilters, ProjectFormData} from '../types/project';

export const useProjectStore = defineStore('projects', () => {

    const projects = ref<Project[]>([]);
    const currentProject = ref<Project | null>(null);
    const loading = ref<boolean>(false);
    const error = ref<string | null>(null);
    const currentId = ref<number | 0>(0);
    const pagination = ref({
        currentPage: 1,
        lastPage: 1,
        perPage: 10,
        total: 0
    });

    const filters = ref<ProjectFilters>({
        search: '',
        status: 'all',
        page: 1,
        perPage: 15
    });

    const activeProjects = computed(() =>
        projects.value.filter(p => p.status !== 'draft' && p.status !== 'delivered')
    );

    const projectCount = computed(() => projects.value.length);

    const stats = computed(() => ({
        total: projects.value.length,
        draft: projects.value.filter(p => p.status === 'draft').length,
        discovery: projects.value.filter(p => p.status === 'discovery').length,
        execution: projects.value.filter(p => p.status === 'execution').length,
        delivered: projects.value.filter(p => p.status === 'delivered').length
    }));

    const fetchProjects = async (page: number = 1, perPage = 5) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await projectsApi.getAllProjects({
                perPage,
                page,
                search: filters.value.search,
                status: filters.value.status !== 'all' ? filters.value.status : ''
            });
            projects.value = response.data;
            pagination.value = {
                currentPage: response.meta.current_page,
                lastPage: response.meta.last_page,
                perPage: response.meta.per_page,
                total: response.meta.total
            };
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error loading projects';
        } finally {
            loading.value = false;
        }
    };

    const createProject = async (payload: ProjectFormData) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await projectsApi.createProject(payload);
            if (response.success) {
                projects.value.unshift(response.data);
                pagination.value.total += 1;
            }
            return {success: true, data: response.data};
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error creating project';
            return {success: false, error: error.value};
        } finally {
            loading.value = false;
        }
    };

    const updateProject = async (id: number, payload: Partial<ProjectFormData>) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await projectsApi.updateProject(id, payload);
            if (response.success) {
                const index = projects.value.findIndex(p => p.id === id);
                if (index !== -1) {
                    projects.value[index] = response.data;
                }
                if (currentProject.value?.id === id) {
                    currentProject.value = response.data;
                }
            }
            return {success: true, data: response.data};
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error updating project';
            return {success: false, error: error.value};
        } finally {
            loading.value = false;
        }
    };

    const deleteProject = async (id: number) => {
        loading.value = true;
        error.value = null;
        try {
            await projectsApi.deleteProject(id);
            projects.value = projects.value.filter(p => p.id !== id);
            pagination.value.total -= 1;
            return {success: true};
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error deleting project';
            return {success: false, error: error.value};
        } finally {
            loading.value = false;
        }
    };

    const fetchProject = async (id: number) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await projectsApi.getProjectInfo(id);
            currentProject.value = response.project;
            error.value = null;
            return {success: true, data: response};
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Error fetching project';
            return {success: false, error: error.value};
        } finally {
            loading.value = false;
        }
    }

    const setFilters = (newFilters: Partial<ProjectFilters>) => {
        filters.value = {...filters.value, ...newFilters};
    };

    const resetFilters = () => {
        filters.value = {
            search: '',
            status: 'all',
            page: 1,
            perPage: 10
        };
    };

    return {
        projects,
        currentProject,
        loading,
        error,
        pagination,
        filters,
        activeProjects,
        projectCount,
        stats,
        currentId,
        fetchProjects,
        fetchProject,
        createProject,
        updateProject,
        deleteProject,
        setFilters,
        resetFilters
    };
});