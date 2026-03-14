<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">

      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Project Manager</h1>
        <div class="w-14 h-14 rounded-2xl bg-blue-500 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-500/30">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
          </svg></div>
        <p class="text-gray-600 mt-2">Login to continue</p>
      </div>

      <form @submit.prevent="handleSubmit">
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
            Email address:
            <span class="text-red-500">*</span>
          </label>
          <input
              id="email"
              v-model="form.email"
              type="email"
              placeholder="email@yourdomain.dev"
              :disabled="store.loading"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100"
              :class="{ 'border-red-500': errors.email }"
          />
          <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
        </div>

        <div class="mb-6">
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
            Password
            <span class="text-red-500">*</span>
          </label>
          <input
              id="password"
              v-model="form.password"
              type="password"
              placeholder="••••••••"
              :disabled="store.loading"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100"
              :class="{ 'border-red-500': errors.password }"
          />
          <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
        </div>

        <!-- Error Global -->
        <div v-if="store.error" class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm">
          {{ store.error }}
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            :disabled="store.loading"
            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition"
        >
          <span v-if="store.loading">Login user...</span>
          <span v-else>Login</span>
        </button>
      </form>
    </div>
  </div>
</template>


<script setup lang="ts">
import {reactive} from 'vue';
import {useRouter} from 'vue-router';
import { Icon } from '@iconify/vue';
import {useAuthStore} from '../store/auth';
import type {LoginCredentials} from '../types/auth';

const router = useRouter();
const store = useAuthStore();


const form = reactive<LoginCredentials>({
  email: '',
  password: ''
});

const errors = reactive<{ email: string; password: string }>({
  email: '',
  password: ''
});

// Validación
const validateForm = (): boolean => {
  errors.email = '';
  errors.password = '';
  let isValid = true;

  if (!form.email) {
    errors.email = 'Email is required';
    isValid = false;
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
    errors.email = 'You must provide a valid email address';
    isValid = false;
  }

  if (!form.password) {
    errors.password = 'Password is required';
    isValid = false;
  } else if (form.password.length < 6) {
    errors.password = 'Password must be at least 6 characters long';
    isValid = false;
  }

  return isValid;
};

const handleSubmit = async () => {
  if (!validateForm()) return;

  store.clearError();
  const result = await store.login(form);

  if (result.success) {
    router.push('/');
  }
};
</script>