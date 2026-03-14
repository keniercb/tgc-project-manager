<template>
  <div>
    <LoadingSpinner v-if="loading" message="Loading..."/>
    <div v-if="modules.length === 0" class="text-center py-8">
      <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
      </svg>
      <p class="mt-2 text-sm text-gray-500">No modules to show...</p>
    </div>
    <div v-else class="space-y-3">
      <div
          v-for="module in modules"
          :key="module.id"
          class="p-4 border border-gray-200 rounded-lg hover:border-green-300 transition-colors"
      >
        <div class="flex justify-between items-start">
          <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
              </svg>
            </div>
            <div>
              <h3 class="font-medium text-gray-900">{{ module.name }}</h3>
              <p class="text-xs text-gray-600"><span
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  :class="getStatusClasses(module.status)">{{ getStatusLabel(module.status) }}</span>
              </p>
            </div>
          </div>
          <div class="flex gap-2">
            <button v-if="module.status === 'draft'"
                    @click="$emit('edit', module)"
                    class="text-green-600 hover:text-green-800 text-sm"
                    title="Edit"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
            </button>
            <button
                v-if="module.status === 'draft'"
                @click="$emit('validate', module)"
                class="text-green-600 hover:text-green-800 text-sm"
                title="Validate"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>

            </button>
            <button
                v-if="module.status === 'validated'"
                @click="$emit('deploy', module)"
                class="text-green-600 hover:text-green-800 text-sm"
                title="Ready to build"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
              </svg>

            </button>
          </div>
        </div>
      </div>
      <div v-if="modules.length > 0" class="px-6 py-4 border-t border-gray-200">
        <div class="flex justify-between items-center">
          <p class="text-sm text-gray-500">
            From {{ (pagination.currentPage - 1) * pagination.perPage + 1 }} to
            {{ Math.min(pagination.currentPage * pagination.perPage, pagination.total) }}
            of {{ pagination.total }} modules
          </p>
          <div class="flex gap-2">
            <button
                @click="$emit('changePage',pagination.currentPage - 1,'module') "
                :disabled="pagination.currentPage === 1"
                class="px-3 py-1 border border-gray-300 rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
            >
              Previous
            </button>
            <button
                @click="$emit('changePage',pagination.currentPage +1,'module')"
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
import LoadingSpinner from "../components/LoadingSpinner.vue";
import type {Module, ModuleStatus} from "../types/modules.ts";

defineProps<{
  modules: Module[];
  loading: boolean;
  pagination: any;
}>();
defineEmits<{
  changePage: [page: number, type: string];
}>();
const getStatusClasses = (status: ModuleStatus) => {
  const labels = {
    'draft': 'bg-gray-100 text-gray-800',
    'validated': 'bg-green-100 text-green-800',
    'ready_for_build': 'bg-blue-100 text-blue-800'
  };
  return labels[status] || status;
}
const getStatusLabel = (status: ModuleStatus) => {
  const labels = {
    'draft': 'Draft',
    'validated': 'Validated',
    'ready_for_build': 'Ready for build'
  };
  return labels[status] || status;
}
</script>