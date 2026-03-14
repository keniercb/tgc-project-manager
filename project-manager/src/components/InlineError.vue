<template>
  <div class="p-4 bg-red-50 text-red-700">
    {{ error }}
  </div>
</template>
<script setup lang="ts">

withDefaults(defineProps<{ error?: string }>(), {
  error: 'Some error occur.',
});
</script>

