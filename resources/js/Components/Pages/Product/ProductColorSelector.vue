<script setup lang="ts">
import { ProductColor } from '@/libs/types/product';

const props = defineProps<{
  colors: ProductColor[];
  selectedColorId: number;
}>();

const emit = defineEmits<{
  (e: 'select', colorId: number): void;
}>();
</script>

<template>
  <div class="flex space-x-3">
    <div 
      v-for="color in colors" 
      :key="color.id"
      @click="emit('select', color.id)"
      class="relative w-10 h-10 rounded-full cursor-pointer flex items-center justify-center transition-all duration-300 transform hover:scale-105 focus:outline-none"
      :class="{ 'ring-2 ring-offset-2 ring-indigo-500': selectedColorId === color.id }"
    >
      <span 
        class="absolute inset-0 rounded-full transition-all duration-300"
        :style="{ backgroundColor: color.hex }"
        :class="{ 'border border-gray-300': color.label === 'Blanc' }"
      ></span>
    </div>
  </div>
</template>
