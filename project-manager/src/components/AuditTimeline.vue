<template>
  <div>
    <LoadingSpinner v-if="auditStore.loading" message="Loading..."/>
    <div v-else-if="auditStore.logs.length === 0" class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
      </svg>
      <p class="mt-2 text-sm text-gray-500">Not audit logs to show</p>
    </div>
    <div v-else>
      <div>
        <h2 class="text-lg font-semibold text-gray-900">{{ objectType }} timeline</h2>
      </div>
      <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <div class="px-6 py-4">
          <div class="flow-root">
            <ul role="list" class="-mb-8">
              <li v-for="(log, index) in auditStore.logs" :key="log.id">
                <div class="relative pb-8">

                  <span
                      v-if="index !== auditStore.logs.length - 1"
                      class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                      aria-hidden="true"
                  ></span>

                  <div class="relative flex space-x-3">
                    <div>
                    <span
                        class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white"
                        :class="auditStore.getActionColor(log.action)"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            v-if="log.action.includes('created')"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />
                        <path
                            v-else-if="log.action.includes('updated')"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                        />
                        <path
                            v-else-if="log.action.includes('deleted')"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                        />
                        <path
                            v-else
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        />
                      </svg>
                    </span>
                    </div>

                    <div class="flex-1 min-w-0">
                      <div class="text-sm">
                        <div class="flex items-center justify-between">
                          <p class="font-medium text-gray-900">
                            {{ capitalize(log.action) }}
                          </p>
                        </div>
                        <div class="text-xs text-gray-500">
                          <span class="text-xs text-gray-500">
                          {{ formatDateTime(log.date) }}
                        </span>
                        </div>
                        <div class="mt-2 flex flex-wrap gap-2">
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                          <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                          </svg>
                          {{ log.userName }}
                        </span>
                          <span
                              class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                          <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                          </svg>
                          {{ objectType }}
                        </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div v-if="auditStore.logs.length > 0" class="px-6 py-4 border-t border-gray-200">
        <div class="flex justify-between items-center">
          <p class="text-sm text-gray-500">
            From {{ (auditStore.pagination.currentPage - 1) * auditStore.pagination.perPage + 1 }} to
            {{
              Math.min(auditStore.pagination.currentPage * auditStore.pagination.perPage, auditStore.pagination.total)
            }}
            of {{ auditStore.pagination.total }} project logs
          </p>
          <div class="flex gap-2">
            <button
                @click="changePage(auditStore.pagination.currentPage - 1)"
                :disabled="auditStore.pagination.currentPage === 1"
                class="px-3 py-1 border border-gray-300 rounded disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
            >
              Previous
            </button>
            <button
                @click="changePage(auditStore.pagination.currentPage + 1)"
                :disabled="auditStore.pagination.currentPage === auditStore.pagination.lastPage"
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
import {useAuditStore} from "../store/audit.ts";
import {onMounted} from "vue";

const auditStore = useAuditStore();
const props = defineProps<{
  objectType: string;
  objectId: number;
}>();

const formatDateTime = (date: string) => {
  return new Date(date).toLocaleString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
}

const capitalize = (str: string): string => {
  return str.charAt(0).toUpperCase() + str.slice(1);
};

const changePage = (page: number) => {
  if (page < 1 || page > auditStore.pagination.lastPage) return;
  auditStore.fetchAuditActions(5, page, props.objectType, props.objectId)
};

onMounted(() => {
  auditStore.fetchAuditActions(
      5,
      0,
      props.objectType,
      props.objectId
  )
});
</script>

