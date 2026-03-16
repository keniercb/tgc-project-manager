<template>
  <div>
    <LoadingSpinner v-if="loading" message="Loading..."/>
  </div>
</template>
<script setup lang="ts">

import LoadingSpinner from "../components/LoadingSpinner.vue";

defineProps<{
  loading: boolean;
}>();
</script>

