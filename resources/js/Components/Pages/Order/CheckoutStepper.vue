<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
  steps: string[];
  currentStep: number;
}>();

const emit = defineEmits<{
  (e: 'go-to-step', step: number): void;
}>();

const goToStep = (step: number) => {
  if (step < props.currentStep) {
    emit('go-to-step', step);
  }
};

const getStepStatus = (index: number) => {
  if (index < props.currentStep) return 'completed';
  if (index === props.currentStep) return 'current';
  return 'upcoming';
};
</script>

<template>
  <div class="py-4">
    <div class="max-w-3xl mx-auto">
      <nav aria-label="Progress">
        <ol class="flex items-center justify-center">
          <li v-for="(step, index) in steps" :key="index" :class="[
            index !== 0 ? 'ml-8 sm:ml-16 relative' : '',
          ]">
            <div v-if="index !== 0" class="absolute inset-0 flex items-center" aria-hidden="true">
              <div :class="[
                'h-0.5 w-full',
                index <= currentStep ? 'bg-indigo-600' : 'bg-gray-200',
              ]"></div>
            </div>
            <div :class="[
              'relative flex items-center justify-center',
              getStepStatus(index) === 'completed' ? 'cursor-pointer' : '',
            ]" @click="goToStep(index)">
              <span :class="[
                'h-8 w-8 flex items-center justify-center rounded-full',
                getStepStatus(index) === 'completed' ? 'bg-indigo-600 hover:bg-indigo-700' : 
                getStepStatus(index) === 'current' ? 'border-2 border-indigo-600 bg-white' : 
                'border-2 border-gray-300 bg-white',
              ]">
                <svg v-if="getStepStatus(index) === 'completed'" class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span v-else-if="getStepStatus(index) === 'current'" class="text-indigo-600 font-medium">{{ index + 1 }}</span>
                <span v-else class="text-gray-500">{{ index + 1 }}</span>
              </span>
              <span class="hidden sm:block ml-2 text-sm font-medium" :class="[
                getStepStatus(index) === 'completed' ? 'text-indigo-600' : 
                getStepStatus(index) === 'current' ? 'text-indigo-600' : 
                'text-gray-500',
              ]">{{ step }}</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>
  </div>
</template>