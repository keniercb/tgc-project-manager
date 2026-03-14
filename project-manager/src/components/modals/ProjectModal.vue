<template>
  <div class="fixed inset-0 z-50 overflow-y-auto">

    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="$emit('close')"></div>


    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">
            {{ mode === 'create' ? 'Create' : 'Edit' }} project
          </h3>
          <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6"  stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <form @submit.prevent="handleSubmit" class="px-6 py-4">
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
             Project name <span class="text-red-500">*</span>
            </label>
            <input
                v-model="form.name"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': errors.name }"
                placeholder="Ex: Enterprise resource planning"
            />
            <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
          </div>

          <!-- Description -->
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Client name <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                v-model="form.clientName"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': errors.description }"
                placeholder="Client name..."
            ></input>
            <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.clientName }}</p>
          </div>

          <!-- Status -->
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Status <span class="text-red-500">*</span>
            </label>
            <select
                v-model="form.status"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="draft">Draft</option>
              <option value="execution">In execution</option>
              <option value="discovery">Discovery</option>
              <option value="delivered">Delivered</option>
            </select>
          </div>

          <div v-if="error" class="mb-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm">
            {{ error }}
          </div>

          <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
            <button
                type="button"
                @click="$emit('close')"
                class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
                type="submit"
                :disabled="loading"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
            >
              {{ loading ? 'In progress...' : (mode === 'create' ? 'Create' : 'Update') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch, onMounted } from 'vue';
import { useProjectStore } from '../../store/projects.ts';
import type { Project, ProjectFormData, ProjectStatus } from '../../types/project.ts';

interface Props {
  project?: Project | null;
  mode: 'create' | 'edit';
}

const props = withDefaults(defineProps<Props>(), {
  project: null
});

const emit = defineEmits<{
  close: [];
  saved: [];
}>();

const store = useProjectStore();
const loading = ref(false);
const error = ref<string | null>(null);

const form = reactive<ProjectFormData>({
  name: '',
  status: 'draft',
  clientName: ''
});

const errors = reactive<{ name: string; description: string }>({
  name: '',
  description: ''
});

// Watch for project changes (edit mode)
watch(() => props.project, (newProject) => {
  if (newProject && props.mode === 'edit') {
    form.name = newProject.name;
    form.status = newProject.status;
    form.clientName = newProject.client || '';
  }
}, { immediate: true });

const validateForm = (): boolean => {
  errors.name = '';
  errors.description = '';
  let isValid = true;

  if (!form.name.trim()) {
    errors.name = 'Project name cannot be empty';
    isValid = false;
  }

  if (!form.clientName.trim()) {
    errors.description = 'Client name cannot be empty';
    isValid = false;
  }

  return isValid;
};

const handleSubmit = async () => {
  if (!validateForm()) return;

  loading.value = true;
  error.value = null;

  try {
    if (props.mode === 'create') {
      const result = await store.createProject(form);
      if (result.success) {
        emit('saved');
      } else {
        error.value = result.error || 'Error creating project';
      }
    } else {
      const result = await store.updateProject(props.project!.id, form);
      if (result.success) {
        emit('saved');
      } else {
        error.value = result.error || 'Error al actualizar proyecto';
      }
    }
  } catch (err: any) {
    error.value = err.message || 'Error inesperado';
  } finally {
    loading.value = false;
  }
};
</script>