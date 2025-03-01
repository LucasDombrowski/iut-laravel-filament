<script setup lang="ts">
import { ProductSize } from '@/libs/types/product';
import gsap from 'gsap';
import { onMounted, watch } from 'vue';

const props = defineProps<{
  sizes: ProductSize[];
  selectedSizeId: number | null;
}>();

const emit = defineEmits<{
  (e: 'select', sizeId: number): void;
}>();

onMounted(() => {
  animateSizes();
});

watch(() => props.sizes, () => {
  animateSizes();
}, { deep: true });

const animateSizes = () => {
  gsap.fromTo('.size-option', 
    { y: 10, opacity: 0 },
    { y: 0, opacity: 1, stagger: 0.1, duration: 0.4, ease: 'power2.out' }
  );
};

</script>

<template>
  <div class="grid grid-cols-4 gap-2">
    <button 
      v-for="size in sizes" 
      :key="size.id"
      @click="emit('select', size.id)"
      class="size-option group relative flex items-center justify-center rounded-md border py-3 px-4 text-sm font-medium uppercase focus:outline-none"
      :class="[
        selectedSizeId === size.id 
          ? 'border-indigo-600 bg-indigo-600 text-white' 
          : 'border-gray-300 bg-white text-gray-900 hover:bg-gray-50'
      ]"
    >
      {{ size.label }}
    </button>
    
    <div v-if="sizes.length === 0" class="col-span-4 text-center py-3 text-gray-500 text-sm">
      Aucune taille disponible
    </div>
  </div>
</template>