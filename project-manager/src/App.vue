<template>
  <div id="app">
    <Navbar v-if="showNavBar"/>
    <main :class="{ 'pt-0': !showNavBar }">
      <RouterView/>
    </main>
  </div>

</template>

<script setup lang="ts">
import {RouterView, useRouter} from 'vue-router';
import Navbar from "./components/Navbar.vue";
import {computed} from "vue";
import {useAuthStore} from "./store/auth.ts";

const router = useRouter();
const authStore = useAuthStore();
const showNavBar = computed(() => {
  const publicRoutes = ['Login'];
  return authStore.isAuthenticated && !publicRoutes.includes(router.name as string);
})
</script>

<style scoped>
#app {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}
</style>
