<template>
  <div>
    <LoadingSpinner v-if="loading"/>
    <div v-else-if="artifactList.length === 0" class="text-center py-8">
      <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
      </svg>
      <p class="mt-2 text-sm text-gray-500">No artifact to show...</p>
    </div>
    <div v-else class="space-y-3">
      <div
          v-for="artifact in artifactList"
          :key="artifact.id"
          class="p-4 border border-gray-200 rounded-lg hover:border-green-300 transition-colors"
      >
        <div class="flex justify-between items-start">
          <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
            <div>
              <p class="text-xs text-gray-600">
                {{ getTypeLabel(artifact.type) }} • <span
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  :class="getStatusClasses(artifact.status)">{{ getStatusLabel(artifact.status) }}</span> • Completed
                at: {{ formatDate(artifact.completedAt) }}
              </p>
            </div>
          </div>
          <div class="flex gap-2">
            <button
                @click="$emit('edit', artifact)"
                class="text-green-600 hover:text-green-800 text-sm"
                title="Edit"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
            </button>
            <button
                @click="$emit('delete', artifact)"
                class="text-red-600 hover:text-red-800 text-sm"
                title="Delete"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div v-if="artifactList.length > 0" class="px-6 py-4 border-t border-gray-200">
        <div class="flex justify-between items-center">
          <p class="text-sm text-gray-500">
            From {{ (pagination.currentPage - 1) * pagination.perPage + 1 }} to
            {{ Math.min(pagination.currentPage * pagination.perPage, pagination.total) }}
            of {{ pagination.total }} artifacts
          </p>
          <div class="flex gap-2">
            <button
                @click="$emit('changePage',pagination.currentPage - 1,'artifact') "
                :disabled="pagination.currentPage === 1"
                class="px-3 py-1 border border-gray-300 rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
            >
              Previous
            </button>
            <button
                @click="$emit('changePage',pagination.currentPage +1,'artifact')"
                :disabled="pagination.currentPage === pagination.lastPage"
                class="px-3 py-1 border border-gray-300 rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
            >
              Next
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import type {Artifact, ArtifactStatus, ArtifactType} from "../types/artifact.ts";
import LoadingSpinner from "../components/LoadingSpinner.vue";

defineProps<{
  artifactList: Artifact[];
  loading: boolean;
  pagination: any;
}>();
defineEmits<{
  edit: [artifact: Artifact];
  delete: [artifact: Artifact];
  changePage: [page: number, type: string];
}>();

const getTypeLabel = (type: ArtifactType) => {
  const labels = {
    'strategic_alignment': 'Strategic Alignment',
    'big_picture': 'Big Picture',
    'domain_breakdown': 'Domain breakdown',
    'module_matrix': 'Module matrix',
    'module_engineering': 'Module Engineering',
    'system_architecture': 'System architecture',
    'phase_scope': 'Phase scope',
  };
  return labels[type] || type;
};

const getStatusLabel = (status: ArtifactStatus) => {
  const labels = {
    'not started': 'Not started',
    'in progress': 'In Progress',
    'blocked': 'Blocked',
    'done': 'Completed',
  };
  return labels[status] || status;
}

const getStatusClasses = (status: ArtifactStatus) => {
  const classes = {
    'not started': 'bg-gray-100 text-gray-800',
    'in progress': 'bg-green-100 text-green-800',
    'blocked': 'bg-red-100 text-red-800',
    'done': 'bg-blue-100 text-blue-800'
  };
  return classes[status] || classes['not started'];
};

const formatDate = (date?: string) => {
  if (!date) return '—';
  return new Date(date).toDateString();
};


</script>
