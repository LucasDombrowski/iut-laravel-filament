<script setup lang="ts">
import { ProductSize } from '@/libs/types/product';

const props = defineProps<{
  sizes: ProductSize[];
  selectedSizeId: number | null;
}>();

const emit = defineEmits<{
  (e: 'select', sizeId: number): void;
}>();
</script>

<template>
  <div class="grid grid-cols-4 gap-2">
    <button 
      v-for="size in sizes" 
      :key="size.id"
      @click="emit('select', size.id)"
      class="group relative flex items-center justify-center rounded-md border py-3 px-4 text-sm font-medium uppercase transition-all duration-300 ease-in-out focus:ring-4 focus:ring-indigo-300"
      :class="[
        selectedSizeId === size.id 
          ? 'border-indigo-600 bg-indigo-600 text-white shadow-lg scale-105'
          : 'border-gray-300 bg-white text-gray-900 hover:bg-indigo-50 hover:shadow-md hover:scale-105'
      ]"
    >
      {{ size.label }}
    </button>

    <div v-if="sizes.length === 0" class="col-span-4 text-center py-3 text-gray-500 text-sm">
      Aucune taille disponible
    </div>
  </div>
</template>
