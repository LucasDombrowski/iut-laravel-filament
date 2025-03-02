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
  <div class="py-6">
    <div class="max-w-3xl mx-auto">
      <nav aria-label="Progress">
        <ol class="flex items-center justify-center">
          <li
            v-for="(step, index) in steps"
            :key="index"
            class="relative flex items-center"
            :class="{ 'ml-8 sm:ml-16': index !== 0 }"
          >
            <!-- Ligne de progression -->
            <div
              v-if="index !== 0"
              class="absolute inset-0 flex items-center"
              aria-hidden="true"
            >
              <div
                class="h-1 w-full transition-all duration-500"
                :class="index <= currentStep ? 'bg-gradient-to-r from-indigo-600 to-blue-500' : 'bg-gray-300'"
              ></div>
            </div>

            <!-- Étape -->
            <div
              class="relative flex items-center justify-center group"
              :class="{ 'cursor-pointer': getStepStatus(index) === 'completed' }"
              @click="goToStep(index)"
              :title="getStepStatus(index) === 'completed' ? `Revenir à l'étape ${index + 1}` : ''"
            >
              <span
                class="h-12 w-12 flex items-center justify-center rounded-full transition-all duration-300 shadow-md"
                :class="[
                  getStepStatus(index) === 'completed'
                    ? 'bg-gradient-to-r from-indigo-600 to-blue-500 text-white hover:from-indigo-700 hover:to-blue-600'
                    : getStepStatus(index) === 'current'
                    ? 'border-4 border-indigo-600 bg-white text-indigo-600 font-bold'
                    : 'border-4 border-gray-300 bg-white text-gray-500 hover:border-indigo-400',
                ]"
              >
                <svg
                  v-if="getStepStatus(index) === 'completed'"
                  class="h-6 w-6"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                  aria-hidden="true"
                >
                  <path
                    fill-rule="evenodd"
                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                    clip-rule="evenodd"
                  />
                </svg>
                <span v-else>{{ index + 1 }}</span>
              </span>

              <!-- Label -->
              <span
                class="hidden sm:block ml-3 text-sm font-medium transition-colors duration-300"
                :class="[
                  getStepStatus(index) === 'completed'
                    ? 'text-indigo-600'
                    : getStepStatus(index) === 'current'
                    ? 'text-indigo-600 font-bold'
                    : 'text-gray-500 hover:text-indigo-400',
                ]"
              >
                {{ step }}
              </span>

              <!-- Tooltip -->
              <span
                v-if="getStepStatus(index) === 'completed'"
                class="absolute bottom-full mb-2 hidden group-hover:block bg-indigo-600 text-white text-xs px-2 py-1 rounded-md shadow-lg"
              >
                Cliquez pour revenir ici
              </span>
            </div>
          </li>
        </ol>
      </nav>
    </div>
  </div>
</template>

<style scoped>
/* Transition douce pour l'animation de progression */
.transition-all {
  transition: all 0.3s ease-in-out;
}

/* Effet d'apparition du tooltip */
.group:hover .group-hover\\:block {
  display: block;
}
</style>
