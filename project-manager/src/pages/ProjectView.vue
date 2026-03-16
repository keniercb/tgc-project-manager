<template>
  <div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-4">
            <router-link
                to="/"
                class="text-gray-500 hover:text-gray-700 transition-colors"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
              </svg>
            </router-link>
            <div>
              <h1 class="text-xl font-bold text-gray-900">
                {{ store.currentProject?.name || 'Loading data...' }}
              </h1>
              <p class="text-sm text-gray-500">Project details</p>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <button
                @click="editProject"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
              Edit
            </button>
            <button
                @click="confirmDeleteProject"
                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
              Delete
            </button>
          </div>
        </div>
      </div>
    </header>
    <LoadingSpinner v-if="store.loading" message="Loading project details..."/>

    <div class="bg-white rounded-lg shadow mb-8">
      <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Project Information</h2>
      </div>
      <div class="px-6 py-4">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <dt class="text-sm font-medium text-gray-500">Name</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ store.currentProject?.name }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">Client name</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ store.currentProject?.client || '—' }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">Status</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ store.currentProject?.status || '—' }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">Created at</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ formatDate(store.currentProject?.createdAt) }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">Modules</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ store.currentProject?.modulesCount }}</dd>
          </div>
          <div>
            <dt class="text-sm font-medium text-gray-500">Artifacts</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ store.currentProject?.artifactsCount }}</dd>
          </div>
        </dl>
      </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

      <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
          <h2 class="text-lg font-semibold text-gray-900">Modules</h2>
          <button
              @click="openModuleModal"
              class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors"
          >
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create module
          </button>
        </div>
        <div class="p-6">
          <!--              :modules="moduleStore.modules"
                        :loading="moduleStore.loading"-->
          <ModuleList
              :modules="moduleStore.modules"
              :loading="moduleStore.loading"
              :pagination="moduleStore.pagination"
              @edit="openModuleModal"
              @validate="validateModule"
              @deploy="deployModule"
              @changePage="changePage"
          />
        </div>
      </div>

      <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
          <h2 class="text-lg font-semibold text-gray-900">Artifacts</h2>
          <button
              @click="openArtifactModal"
              class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors"
          >
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create artifact
          </button>
        </div>
        <div class="p-6">
          <ArtifactList
              :artifactList="artifactStore.artifacts"
              :loading="artifactStore.loading"
              :pagination="artifactStore.pagination"
              @edit="openArtifactModal"
              @delete="confirmDeleteArtifact"
              @changePage="changePage"
          />
        </div>
      </div>
    </div>
  </div>

  <DeleteModal
      v-if="showDeleteModal"
      :title="deleteConfig.title"
      :message="deleteConfig.message"
      @confirm="handleDelete"
      @cancel="showDeleteModal = false"
  />
  <ArtifactModal
      v-if="showArtifactModal"
      :artifact="selectedArtifact"
      :error="artifactStore.error"
      :project="store.currentProject?.id"
      :mode="artifactModalMode"
      @close="closeArtifactModal"
      @submit="handleArtifactSaved"
  />
  <ModuleModal
      v-if="showModuleModal"
      :module="selectedModule"
      :error="store.error"
      :projectId="store.currentProject?.id"
      :mode="moduleModalMode"
      @close="showModuleModal=false"
      @submit="handleModuleSaved"
  />
  <ErrorModal
      v-if="store.error"
      title="Error"
      :message="store.error"
      @close="store.error = null"/>
</template>
<script setup lang="ts">


import {onMounted, onUnmounted, ref} from "vue";
import {useProjectStore} from "../store/projects.ts";
import LoadingSpinner from "../components/LoadingSpinner.vue";
import ArtifactList from "../components/ArtifactList.vue";
import ModuleList from "../components/ModuleList.vue";
import {useModuleStore} from "../store/modules.ts";
import {useArtifactsStore} from "../store/artifacts.ts";

import DeleteModal from "../components/modals/DeleteModal.vue";
import router from "../router";
import ArtifactModal from "../components/modals/ArtifactModal.vue";
import type {Artifact, ArtifactFormData} from "@/types/artifact.ts";
import ErrorModal from "../components/modals/ErrorModal.vue";
import ModuleModal from "../components/modals/ModuleModal.vue";

import type {Module, ModuleFormData} from "@/types/modules.ts";

const store = useProjectStore();
const moduleStore = useModuleStore();
const artifactStore = useArtifactsStore();

const deleteConfig = ref({
  type: '' as 'project' | 'module' | 'artifact',
  id: 0 as number,
  title: '',
  message: ''
});
const showDeleteModal = ref(false);

const artifactModalMode = ref<string>('create');
const selectedArtifact = ref<Artifact | null>(null);
const showArtifactModal = ref(false);

const showModuleModal = ref(false);
const moduleModalMode = ref('create');
const selectedModule = ref<Module | null>(null);

onMounted(() => {
  store.fetchProject(store.currentId);
  artifactStore.fetchArtifacts(store.currentId);
  moduleStore.fetchModules(store.currentId);
})

onUnmounted(() => {
  store.currentProject = null;
  store.currentId = 0;
});
const formatDate = (date?: string) => {
  if (!date) return '—';
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

const handleDelete = async () => {
  const {type, id} = deleteConfig.value;

  if (type === 'project') {
    await store.deleteProject(id);
    await router.push('/');
  } else if (type === 'artifact') {
    await artifactStore.deleteArtifacts(id);
  }

  showDeleteModal.value = false;
};


const openArtifactModal = (artifact?: Artifact) => {
  artifactModalMode.value = artifact?.id ? 'edit' : 'create';
  selectedArtifact.value = artifact?.id ? artifact : null;
  showArtifactModal.value = true;
};

const openModuleModal = (module?: Module) => {
  moduleModalMode.value = module?.id ? 'edit' : 'create';
  selectedModule.value = module?.id ? module : null;
  showModuleModal.value = true;
}

const validateModule = async (module: Module) => {
  const resp = await moduleStore.validateModule(module);
  if (resp.success) {
    await moduleStore.fetchModules(store.currentId);
  } else {
    store.error = 'Error validating module. ' + resp.data;
  }
}

const deployModule = async (module: Module) => {
  const resp = await moduleStore.deployModule(module);
  if (resp.success) {
    await moduleStore.fetchModules(store.currentId);
  } else {
    store.error = 'Error deploying module.';
  }
}

const confirmDeleteArtifact = (artifact: Artifact) => {
  deleteConfig.value = {
    type: 'artifact',
    id: artifact.id,
    message: 'Do you what to precede deleting current artifact?',
    title: 'Delete artifact?'
  };
  showDeleteModal.value = true;
};

const closeArtifactModal = () => {
  showArtifactModal.value = false;
  selectedArtifact.value = null;
};

const handleArtifactSaved = async (artifact: ArtifactFormData) => {
  let resp;
  if (artifact?.id !== undefined) {
    resp = await artifactStore.updateArtifacts(artifact);
  } else {
    resp = await artifactStore.createArtifacts(artifact);
  }
  if (resp && resp.success) {
    await artifactStore.fetchArtifacts(store.currentId);
  } else {
    store.error = resp?.data.message + ' ' + resp?.data.cause;
  }
}

const handleModuleSaved = async (module: ModuleFormData) => {

  let resp;
  console.log(module);
  if (module?.id === undefined) {
    resp = await moduleStore.createModule(module);
  } else {
    resp = await moduleStore.updateModule(module);
  }
  if (resp && resp.success) {
    await moduleStore.fetchModules(store.currentId);
  } else {
    store.error = resp?.error;
  }
}

const changePage = (page: number, type: string = 'artifact') => {
  let _store;
  if (type === 'module') {
    _store = moduleStore;
  } else if (type === 'artifact') {
    _store = artifactStore;
  }
  if (page < 1 || page > _store.pagination.lastPage) return;
  if (type === 'artifact') {
    _store.fetchArtifacts(store.currentId, page);
  } else if (type === 'module') {
    _store.fetchModules(store.currentId, page);
  }
};
</script>

