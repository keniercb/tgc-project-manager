<template>
  <div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="$emit('close')"></div>
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">
            {{ mode === 'create' ? 'Create ' : 'Edit' }} Module
          </h3>
          <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        <div class="border-b border-gray-200 bg-gray-50 shrink-0">
          <nav class="flex px-6 gap-1" aria-label="Tabs">
            <button
                v-for="tab in tabs"
                :key="tab.id"
                @click="activeTab = tab.id"
                :class="[
                'px-4 py-3 text-sm font-medium border-b-2 transition-colors',
                activeTab === tab.id
                  ? 'border-green-600 text-green-700 bg-white'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
              ]"
            >
              {{ tab.label }}
              <span
                  v-if="tab.hasErrors"
                  class="ml-1.5 inline-flex items-center justify-center w-4 h-4 text-xs text-white bg-red-500 rounded-full"
              >!</span>
            </button>
          </nav>
        </div>

        <form @submit.prevent="handleSubmit" class="px-6 py-4">
          <div v-if="activeTab=='general'">
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Name<span class="text-red-500">*</span>
              </label>
              <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                     :class="{ 'border-red-500': errors.name }" v-model="form.name"/>

              <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Objective<span class="text-red-500">*</span>
              </label>
              <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        rows="3"
                        :class="{ 'border-red-500': errors.objective }" v-model="form.objective">
            </textarea>
              <p v-if="errors.objective" class="mt-1 text-sm text-red-600">{{ errors.objective }}</p>
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Domain<span class="text-red-500">*</span>
              </label>
              <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        rows="3"
                        :class="{ 'border-red-500': errors.domain }" v-model="form.domain">
            </textarea>
              <p v-if="errors.domain" class="mt-1 text-sm text-red-600">{{ errors.domain }}</p>
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Responsibility<span class="text-red-500">*</span>
              </label>
              <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        rows="3"
                        :class="{ 'border-red-500': errors.responsibility }" v-model="form.responsibility">
            </textarea>
              <p v-if="errors.responsibility" class="mt-1 text-sm text-red-600">{{ errors.responsibility }}</p>
            </div>
          </div>
          <div v-if="activeTab=='interface'">
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Inputs<span class="text-red-500">*</span>
              </label>
              <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        rows="3"
                        :class="{ 'border-red-500': errors.inputs }" v-model="form.inputs">
            </textarea>
              <p v-if="errors.inputs" class="mt-1 text-sm text-red-600">{{ errors.inputs }}</p>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Outputs<span class="text-red-500">*</span>
              </label>
              <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        rows="3"
                        :class="{ 'border-red-500': errors.outputs }" v-model="form.outputs">
            </textarea>
              <p v-if="errors.outputs" class="mt-1 text-sm text-red-600">{{ errors.outputs }}</p>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Data Structure<span class="text-red-500">*</span>
              </label>
              <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        rows="3"
                        :class="{ 'border-red-500': errors.dataStructure }" v-model="form.dataStructure">
            </textarea>
              <p v-if="errors.dataStructure" class="mt-1 text-sm text-red-600">{{ errors.dataStructure }}</p>
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Version note
              </label>
              <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        rows="3"
                        :class="{ 'border-red-500': errors.versionNote }" v-model="form.versionNote">
            </textarea>
              <p v-if="errors.versionNote" class="mt-1 text-sm text-red-600">{{ errors.versionNote }}</p>
            </div>
          </div>
          <div v-if="activeTab=='advanced'">
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Logic Rules<span class="text-red-500">*</span>
              </label>
              <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        rows="3"
                        :class="{ 'border-red-500': errors.logicRules }" v-model="form.logicRules">
            </textarea>
              <p v-if="errors.logicRules" class="mt-1 text-sm text-red-600">{{ errors.logicRules }}</p>
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Failure Scenarios<span class="text-red-500">*</span>
              </label>
              <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        rows="3"
                        :class="{ 'border-red-500': errors.failureScenarios }" v-model="form.failureScenarios">
            </textarea>
              <p v-if="errors.failureScenarios" class="mt-1 text-sm text-red-600">{{ errors.failureScenarios }}</p>
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Audit Trail Requirements<span class="text-red-500">*</span>
              </label>
              <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        rows="3"
                        :class="{ 'border-red-500': errors.auditTrailRequirements }"
                        v-model="form.auditTrailRequirements">
            </textarea>
              <p v-if="errors.auditTrailRequirements" class="mt-1 text-sm text-red-600">{{
                  errors.auditTrailRequirements
                }}</p>
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Dependencies<span class="text-red-500">*</span>
              </label>
              <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        rows="3"
                        :class="{ 'border-red-500': errors.dependencies }" v-model="form.dependencies">
            </textarea>
              <p v-if="errors.dependencies" class="mt-1 text-sm text-red-600">{{ errors.dependencies }}</p>
            </div>
          </div>

          <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
            <button
                type="button"
                @click="$emit('close')"
                class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Cancel
            </button>
            <button
                type="submit"
                :disabled="isSubmitting"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              <span v-if="isSubmitting">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Saving...
              </span>
              <span v-else>{{ mode === 'create' ? 'Create' : 'Update' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>
<script setup lang="ts">
import type {Module, ModuleFormData} from "@/types/modules.ts";
import {reactive, ref} from "vue";

const props = defineProps<{
  mode: string,
  module: Module | null,
  projectId: number | undefined,
}>();

const emit = defineEmits<{
  close: [],
  submit: [payload: ModuleFormData]
}>();
const isSubmitting = ref(false);
const activeTab = ref('general');
const tabs = [
  {id: 'general', label: 'General', hasErrors: false},
  {id: 'interface', label: 'Interface', hasErrors: false},
  {id: 'advanced', label: 'Advanced', hasErrors: false}
];
const form = reactive({
  name: '',
  domain: '',
  project: 0,
  objective: '',
  responsibility: '',
  versionNote: '',

  inputs: '',
  outputs: '',
  dataStructure: '',

  logicRules: '',
  failureScenarios: '',
  auditTrailRequirements: '',
  dependencies: ''

});
if (props.module && props.module.id) {
  const fields = [
    'name', 'domain', 'objective',
    'responsibility', 'versionNote',
    'inputs', 'outputs', 'dataStructure',
    'logicRules', 'failureScenarios', 'auditTrailRequirements', 'dependencies'
  ];

  fields.forEach(field => {
    form[field] = props.module[field];
  });
}
const errors = reactive({
  name: '',
  domain: '',
  objective: '',
  responsibility: '',
  versionNote: '',
  inputs: '',
  outputs: '',
  dataStructure: '',
  logicRules: '',
  failureScenarios: '',
  auditTrailRequirements: '',
  dependencies: ''
});
const validateForm = () => {
  errors.name = form.name.trim() ? '' : 'Name is required';
  errors.domain = form.domain.trim() ? '' : 'Domain is required';
  errors.objective = form.objective.trim() ? '' : 'Object is required';
  errors.responsibility = form.responsibility.trim() ? '' : 'Responsibility is required';
  errors.inputs = form.inputs.trim() ? '' : 'Input is required';
  errors.outputs = form.outputs.trim() ? '' : 'Output is required';
  errors.dataStructure = form.dataStructure.trim() ? '' : 'Data Structure is required';
  errors.logicRules = form.logicRules.trim() ? '' : 'Logic Rules is required';
  errors.failureScenarios = form.failureScenarios.trim() ? '' : 'Failure was required';
  errors.auditTrailRequirements = form.auditTrailRequirements.trim() ? '' : 'Audits is required';

  if (form.inputs.trim()) {
    try {
      JSON.parse(form.inputs);
    } catch {
      errors.inputs = 'Inputs must be valid JSON format';
    }
  }
  if (form.outputs.trim()) {
    try {
      JSON.parse(form.outputs);
    } catch {
      errors.outputs = 'Outputs must be valid JSON format';
    }
  }
  if (form.dependencies.trim()) {
    try {
      JSON.parse(form.dependencies);
    } catch {
      errors.dependencies = 'Dependencies must be valid JSON format';
    }
  }
  return !Object.values(errors).some(error => error !== '');
}
const handleSubmit = async () => {
  if (!validateForm()) {
    return;
  }
  isSubmitting.value = true;
  let formData: ModuleFormData = {
    id: props.module?.id,
    name: form.name,
    projectId: props.projectId,
    objective: form.objective,
    domain: form.domain,
    fields: {
      objective: form.objective,
      responsibility: form.responsibility,
      versionNote: form.versionNote,
      inputs: form.inputs,
      outputs: form.outputs,
      dataStructure: form.dataStructure,
      logicRules: form.logicRules,
      failureScenarios: form.failureScenarios,
      auditTrailRequirements: form.failureScenarios,
      dependencies: form.dependencies
    }
  }
  try {
    emit('submit', formData);
    emit('close');
  } catch (error) {
  } finally {
    isSubmitting.value = false;
  }
}
</script>
