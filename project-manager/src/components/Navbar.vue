<template>
  <nav class="bg-white shadow-md border-b border-gray-200 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">

        <div class="flex items-center gap-8">

          <router-link to="/" class="flex items-center gap-2">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
              </svg>
            </div>
          </router-link>


          <div class="hidden md:flex items-center gap-1">
            <router-link
                to="/"
                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                :class="isActive('/') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-100'"
            >
              Project Manager
            </router-link>
          </div>
        </div>


        <div class="flex items-center gap-4">
          <div class="w-px h-6 bg-gray-300 hidden sm:block"></div>
          <div class="flex items-center gap-3">
            <!-- Avatar -->
            <div
                class="w-9 h-9 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
              {{ userInitials }}
            </div>


            <div class="hidden lg:block">
              <p class="text-sm font-medium text-gray-900">{{ authStore.userName }}</p>
              <p class="text-xs text-gray-500">{{ authStore.user?.email }}</p>
            </div>


            <div class="relative">
              <button
                  @click="toggleDropdown"
                  class="p-2 text-gray-400 hover:text-gray-600 transition-colors"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>

              <div
                  v-if="isDropdownOpen"
                  class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                  @click="isDropdownOpen = false"
              >

                <button
                    @click="handleLogout"
                    :disabled="authStore.loading"
                    class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors disabled:opacity-50"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                  </svg>
                  <span>{{ authStore.loading ? 'Login out...' : 'Logout' }}</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
        v-if="isDropdownOpen"
        class="fixed inset-0 z-30"
        @click="isDropdownOpen = false"
    ></div>
  </nav>
</template>

<script setup lang="ts">
import {ref, computed, onMounted, onUnmounted} from 'vue';
import {useRouter} from 'vue-router';
import {useAuthStore} from '../store/auth.ts';

const router = useRouter();
const authStore = useAuthStore();

const isDropdownOpen = ref(false);

const userInitials = computed(() => {
  const name = authStore.userName || 'U';
  const parts = name.split(' ');
  if (parts.length >= 2) {
    return (parts[0][0] + parts[1][0]).toUpperCase();
  }
  return name.substring(0, 2).toUpperCase();
});

const isActive = (path: string) => {
  return router.currentRoute.value.path === path;
};

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};


const handleClickOutside = (event: MouseEvent) => {
  const target = event.target as HTMLElement;
  if (!target.closest('.relative')) {
    isDropdownOpen.value = false;
  }
};

const handleLogout = async () => {
  await authStore.logout();
  await router.push('/login');
};


onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>