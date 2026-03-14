<template>
  <Teleport to="body">
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
      <div class="bg-white rounded-lg max-w-sm w-full p-6 text-center shadow-xl">
        <div class="mx-auto h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
          <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3 class="mt-4 text-lg font-medium text-gray-900">{{ title }}</h3>
        <p class="mt-2 text-sm text-gray-500">{{ message }}</p>
        <button @click="$emit('close')" class="mt-5 w-full bg-red-600 text-white rounded-lg px-4 py-2 hover:bg-red-700">
          Close
        </button>
      </div>
    </div>
  </Teleport>
</template>
<script setup lang="ts">
defineProps<{
  title: string;
  message: string;
}>()
defineEmits<{
  close: []
}>()
</script>
