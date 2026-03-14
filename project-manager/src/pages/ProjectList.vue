<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between items-center">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Projects</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your projects</p>
          </div>
          <button
              @click="openCreateModal"
              class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
          >
            <svg class="w-5 h-5 mr-2" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Project
          </button>
        </div>
      </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
          <p class="text-sm text-gray-500">Total</p>
          <p class="text-2xl font-bold text-gray-900">{{ store.stats.total }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
          <p class="text-sm text-gray-500">Draft</p>
          <p class="text-2xl font-bold text-red-600">{{ store.stats.draft }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
          <p class="text-sm text-gray-500">Discovery</p>
          <p class="text-2xl font-bold text-green-600">{{ store.stats.discovery }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
          <p class="text-sm text-gray-500">In Execution</p>
          <p class="text-2xl font-bold text-blue-600">{{ store.stats.execution }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
          <p class="text-sm text-gray-500">Delivered</p>
          <p class="text-2xl font-bold text-gray-600">{{ store.stats.delivered }}</p>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
          <!-- Search -->
          <div class="flex-1">
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Buscar proyectos..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                @input="debouncedSearch"
            />
          </div>
          <!-- Status Filter -->
          <div class="md:w-48">
            <select
                v-model="statusFilter"
                @change="applyFilters"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="all">All</option>
              <option value="draft">Draft</option>
              <option value="discovery">Discovery</option>
              <option value="execution">Execution</option>
              <option value="delivered">Delivered</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <LoadingSpinner v-if="store.loading" message="Loading projects..." />
       <InlineError v-if="store.error" :error="store.error" />
        <div v-else-if="store.projects.length > 0" class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Project
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Client Name
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Created At
              </th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="project in store.projects" :key="project.id">
              <td class="px-6 py-4">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <span class="text-blue-600 font-semibold">{{ project.name.charAt(0) }}</span>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ project.name }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                  <span
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                      :class="getStatusClasses(project.status)"
                  >
                    {{ getStatusLabel(project.status) }}
                  </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-900">
                {{ project.client || '—' }}
              </td>

              <td class="px-6 py-4 text-sm text-gray-500">
                <div>{{ formatDate(project.createdAt) }}</div>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex justify-end gap-2">
                  <button
                      @click="viewProject(project.id)"
                      class="px-3 py-1 border border-gray-300 rounded text-blue-600 hover:text-blue-800 text-sm hover:bg-gray-50"
                  >
                    View
                  </button>
                  <button
                      @click="editProject(project.id)"
                      class="px-3 py-1 border border-gray-300 rounded text-green-600 hover:text-green-800 text-sm hover:bg-gray-50"
                  >
                    Edit
                  </button>
                  <button
                      @click="confirmDelete(project.id)"
                      class="px-3 py-1 border border-gray-300 rounded text-red-600 hover:text-red-800 text-sm hover:bg-gray-50"
                  >
                    Archive
                  </button>
                </div>
              </td>
            </tr>
            </tbody>
          </table>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400"  stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">No projects</h3>
          <p class="mt-1 text-sm text-gray-500">Comienza creando un nuevo proyecto.</p>
          <button
              @click="openCreateModal"
              class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
          >
            Create project
          </button>
        </div>

        <div v-if="store.projects.length > 0" class="px-6 py-4 border-t border-gray-200">
          <div class="flex justify-between items-center">
            <p class="text-sm text-gray-500">
              From {{ (store.pagination.currentPage - 1) * store.pagination.perPage + 1 }} to
              {{ Math.min(store.pagination.currentPage * store.pagination.perPage, store.pagination.total) }}
              of {{ store.pagination.total }} projects
            </p>
            <div class="flex gap-2">
              <button
                  @click="changePage(store.pagination.currentPage - 1)"
                  :disabled="store.pagination.currentPage === 1"
                  class="px-3 py-1 border border-gray-300 rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
              >
                Previus
              </button>
              <button
                  @click="changePage(store.pagination.currentPage + 1)"
                  :disabled="store.pagination.currentPage === store.pagination.lastPage"
                  class="px-3 py-1 border border-gray-300 rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
              >
                Next
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>
    <ProjectModal
        v-if="showModal"
        :project="selectedProject"
        :mode="modalMode"
        @close="closeModal"
        @saved="handleSaved"
    />
    <DeleteConfirmationModal
        v-if="showDeleteModal"
        :project-name="projectToDelete?.name || ''"
        @confirm="handleDelete"
        @cancel="showDeleteModal = false"
    />
  </div>
</template>
<script setup lang="ts">
import {ref, onMounted} from 'vue';
import {useRouter} from 'vue-router';
import {useProjectStore} from '../store/projects';
import type {Project, ProjectStatus} from '../types/project';
import DeleteConfirmationModal from "./DeleteConfirmationModal.vue";
import ProjectModal from "../components/modals/ProjectModal.vue";
import LoadingSpinner from "../components/LoadingSpinner.vue";
import InlineError from "../components/InlineError.vue";

const store = useProjectStore();
const router = useRouter();



const showModal = ref(false);
const showDeleteModal = ref(false);
const modalMode = ref<'create' | 'edit'>('create');
const selectedProject = ref<Project | null>(null);
const projectToDelete = ref<Project | null>(null);


const searchQuery = ref('');
const statusFilter = ref<string>('all');
let searchTimeout: ReturnType<typeof setTimeout>;

onMounted(() => {
  store.fetchProjects();
});

function statusLabel(status: string) {
  const map: Record<string, string> = {
    draft: 'Draft',
    delivered: 'Delivered',
    execution: 'In Execution',
    discovery: 'Discovery',
  };
  return map[status] ?? status;
}

function statusClass(status: string) {
  const map: Record<string, string> = {
    draft: 'bg-emerald-950/60 text-emerald-400 border border-emerald-800/40',
    delivered: 'bg-slate-800 text-slate-400 border border-slate-700',
    execution: 'bg-amber-950/60 text-amber-400 border border-amber-800/40',
    discovery: 'bg-amber-950/60 text-amber-400 border border-amber-800/40',
  };
  return map[status] ?? 'bg-slate-800 text-slate-400';
}

const truncate = (text: string, length: number) => {
  if (!text) return '';
  return text.length > length ? text.substring(0, length) + '...' : text;
};

const formatDate = (date?: string) => {
  if (!date) return '—';
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

const getStatusClasses = (status: ProjectStatus) => {
  const classes = {
    draft: 'bg-green-100 text-green-800',
    discovery: 'bg-blue-100 text-blue-800',
    delivered: 'bg-yellow-100 text-yellow-800',
    execution: 'bg-gray-100 text-gray-800'
  };
  return classes[status] || classes.draft;
};
const getStatusLabel = (status: ProjectStatus) => {
  const labels = {
    draft: 'Draft',
    execution: 'In Execution',
    delivered: 'Delivered',
    discovery: 'Discovery',
    archived: 'Archived',
  };
  return labels[status] || status;
};

const debouncedSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    applyFilters();
  }, 500);
};
const applyFilters = () => {
  store.setFilters({
    search: searchQuery.value,
    status: statusFilter.value as ProjectStatus | 'all',
    page: 1
  });
  store.fetchProjects(1);
};

const changePage = (page: number) => {
  if (page < 1 || page > store.pagination.lastPage) return;
  store.fetchProjects(page);
};

const openCreateModal = () => {
  modalMode.value = 'create';
  selectedProject.value = null;
  showModal.value = true;
};

const viewProject = (id: number) => {
  store.currentId = id;
  router.push(`/project-detail`);
};

const editProject = (id: number) => {
  const project = store.projects.find(p => p.id === id);
  if (project) {
    modalMode.value = 'edit';
    selectedProject.value = project;
    showModal.value = true;
  }
};

const confirmDelete = (id: number) => {
  projectToDelete.value = store.projects.find(p => p.id === id) || null;
  showDeleteModal.value = true;
};

const handleDelete = async () => {
  if (projectToDelete.value) {
    await store.deleteProject(projectToDelete.value.id);
    showDeleteModal.value = false;
    projectToDelete.value = null;
    await store.fetchProjects(store.pagination.currentPage);
  }
};

const handleSaved = () => {
  showModal.value = false;
  selectedProject.value = null;
  store.fetchProjects(store.pagination.currentPage);
};

const closeModal = () => {
  showModal.value = false;
  selectedProject.value = null;
};
</script>