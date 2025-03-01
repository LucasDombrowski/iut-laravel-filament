<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
  modelValue: number;
  max: number;
}>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: number): void;
}>();

const quantity = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
});

const decrement = () => {
  if (quantity.value > 1) {
    quantity.value--;
  }
};

const increment = () => {
  if (quantity.value < props.max) {
    quantity.value++;
  }
};
</script>

<template>
  <div class="flex items-center">
    <button 
      type="button" 
      @click="decrement"
      class="w-10 h-10 rounded-l-md border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 focus:outline-none"
      :disabled="quantity <= 1"
    >
      <span class="sr-only">Réduire la quantité</span>
      <svg class="h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
      </svg>
    </button>
    
    <input 
      type="number" 
      v-model="quantity"
      min="1"
      :max="max"
      class="w-16 border-t border-b border-gray-300 text-center text-gray-900 [-moz-appearance:_textfield] [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none"
    />
    
    <button 
      type="button" 
      @click="increment"
      class="w-10 h-10 rounded-r-md border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 focus:outline-none"
      :disabled="quantity >= max"
    >
      <span class="sr-only">Augmenter la quantité</span>
      <svg class="h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
      </svg>
    </button>
  </div>
</template>