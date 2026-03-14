<template>
  <div class="flex flex-col items-center justify-center py-16 gap-3">
    <Icon icon="mdi:loading" class="text-sky-500 text-3xl animate-spin" />
    <p class="text-slate-500 text-sm">{{ message }}</p>
  </div>
</template>

<script setup lang="ts">
import { Icon } from '@iconify/vue';

withDefaults(defineProps<{ message?: string }>(), {
  message: 'Loading...',
});
</script>
