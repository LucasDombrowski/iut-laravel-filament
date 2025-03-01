<script setup lang="ts">
import { ProductColor } from '@/libs/types/product';
import gsap from 'gsap';
import { onMounted } from 'vue';

const props = defineProps<{
  colors: ProductColor[];
  selectedColorId: number;
}>();

const emit = defineEmits<{
  (e: 'select', colorId: number): void;
}>();

onMounted(() => {
  // Animate color options on mount
  gsap.from('.color-option', {
    scale: 0.5,
    opacity: 0,
    stagger: 0.1,
    duration: 0.4,
    ease: 'back.out(1.7)'
  });
});
</script>

<template>
  <div class="flex space-x-3">
    <div 
      v-for="color in colors" 
      :key="color.id"
      @click="emit('select', color.id)"
      class="color-option relative w-10 h-10 rounded-full cursor-pointer flex items-center justify-center"
      :class="{ 'ring-2 ring-offset-2 ring-indigo-500': selectedColorId === color.id }"
    >
      <span 
        class="absolute inset-0 rounded-full"
        :style="{ backgroundColor: color.hex }"
        :class="{ 'border border-gray-300': color.label === 'Blanc' }"
      ></span>
    </div>
  </div>
</template>