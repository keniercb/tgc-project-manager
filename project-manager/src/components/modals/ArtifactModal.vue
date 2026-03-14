<template>
  <div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="$emit('close')"></div>
    <div class="flex min-h-full items-center justify-center p-4">
      <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">
            {{ mode === 'create' ? 'Create ' : 'Edit' }} Artifact
          </h3>
          <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-6 h-6" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        <form @submit.prevent="handleSubmit" class="px-6 py-4">
          <InlineError v-if="error" :error="error"/>
          <div class="mb-4" v-if="mode === 'create'">
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Type <span class="text-red-500">*</span>
            </label>
            <select

                v-model="form.type"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                :class="{ 'border-red-500': errors.type }"
            >
              <option value="">Select type...</option>
              <option v-for="type in types" :key="type.id" :value="type.id">
                {{ type.name }}
              </option>
            </select>
            <p v-if="errors.type" class="mt-1 text-sm text-red-600">{{ errors.type }}</p>
          </div>
          <div v-else class="mt-1 text-sm text-gray-700">
            <span class="mt-4 text-lg font-medium text-gray-600">Type: </span>{{ getTypeDescription(form.type)}}
          </div>
          <div class="mb-4" v-if="mode === 'edit'">
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Status
            </label>
            <select
                v-model="form.status"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg"
            >
              <option value="not started" selected>Not started</option>
              <option value="in progress">In progress</option>
              <option value="blocked">Blocked</option>
              <option value="done">Done</option>
            </select>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Content <span class="text-red-500">*</span>
            </label>
            <textarea
                v-model="form.content"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg font-mono text-sm leading-relaxed"
                rows="5"
                :class="{
                'border-red-500': errors.content,
                'bg-blue-50/50': form.type && isTemplateContent
              }"
                placeholder="Select a type to load JSON template..."
                spellcheck="false"
            >
            </textarea>
            <p v-if="errors.content" class="mt-1 text-sm text-red-600">{{ errors.content }}</p>
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
import type {Artifact, ArtifactFormData} from "@/types/artifact.ts";
import {computed, reactive, ref, watch} from "vue";
import InlineError from "../InlineError.vue";

const props = defineProps<{
  mode: string
  artifact: Artifact | null,
  project: number | undefined
}>();

const emit = defineEmits<{
  close: [],
  submit: [payload: ArtifactFormData]
}>();
const types = [
  {
    id: "system_architecture",
    name: "System Architecture",
  }, {
    id: "strategic_alignment",
    name: "Strategic Alignment",
  }, {
    id: 'big_picture',
    name: "Big Picture",
  }, {
    id: "domain_breakdown",
    name: "Domain Breakdown",
  }, {
    id: 'module_matrix',
    name: "Module Matrix",
  }, {
    id: 'module_engineering',
    name: "Module Engineering",
  }, {
    id: 'phase_scope',
    name: "Phase scope",
  }
];
const isSubmitting = ref(false);
const error = ref('');
const artifactTemplates: Record<string, any> = {
  strategic_alignment: {
    transformation: "",
    supported_decisions: [],
    measurable_success: [
      {
        metric: "",
        target: ""
      }
    ],
    out_of_scope: []
  },
  big_picture: {
    ecosystem_vision: "",
    impacted_domains: [],
    success_definition: ""
  },
  domain_breakdown: {
    domains: [
      {
        name: "",
        objective: "",
        owner_user_id: ""
      }
    ]
  },
  module_matrix: {
    modules_overview: [
      {
        name: "",
        domain: "",
        priority: "",
        phase: ""
      }
    ]
  },
  system_architecture: {
    auth_model: "",
    api_style: "",
    data_model_notes: "",
    scalability_notes: ""
  },
  phase_scope: {
    included_modules: [],
    excluded_items: [],
    acceptance_criteria: []
  },
  module_engineering: {
    module_name: "",
    description: "",
    technologies: [],
    dependencies: []
  }
};
const form = reactive({
  projectId: 0,
  type: '',
  status: '',
  content: '',
});

watch(() => form.type, (newType) => {
  if (newType && artifactTemplates[newType]) {
    form.content = JSON.stringify(artifactTemplates[newType], null, 2);
  } else {
    form.content = '';
  }
}, {immediate: true});

if (props.artifact && props.artifact.id) {
  form.type = props.artifact.type;
  form.status = props.artifact.status;
  form.content = props.artifact.content;
}
const errors = reactive({
  name: '',
  type: '',
  content: '',
});

const isTemplateContent = computed(() => {
  if (!form.type || !artifactTemplates[form.type]) return false;
  try {
    const current = JSON.parse(form.content);
    const template = artifactTemplates[form.type];
    return JSON.stringify(current) === JSON.stringify(template);
  } catch {
    return false;
  }
});

const validateForm = () => {
  errors.type = form.type.trim() ? '' : 'Type is required';
  errors.content = form.content.trim() ? '' : 'Content is required';
  if (form.content.trim()) {
    try {
      JSON.parse(form.content);
    } catch {
      errors.content = 'Content must be valid JSON format';
    }
  }
  return !errors.name && !errors.type && !errors.content;
}
const getTypeDescription = (type: string) => {
  const labels = {
    'strategic_alignment': 'Strategic Alignment',
    'big_picture': 'Big Picture',
    'domain_breakdown': 'Domain Breakdown',
    'module_matrix': 'Module Matrix',
    'module_engineering': 'Module Engineering',
    'phase_scope': 'Phase scope',
    'system_architecture': 'System Architecture',
  };
  return labels[type] || type;
}
const handleSubmit = async () => {
  if (!validateForm()) {
    return;
  }
  isSubmitting.value = true;
  const artifactPayload: ArtifactFormData = {
    id: props.artifact?.id,
    projectId: props.project,
    type: form.type,
    status: props.mode === 'create' ? 'not started' : form.status,
    content: form.content
  }
  try {
    emit('submit', artifactPayload);
    emit('close');
  } catch (e) {

  } finally {
    isSubmitting.value = false;
  }
};
</script>

