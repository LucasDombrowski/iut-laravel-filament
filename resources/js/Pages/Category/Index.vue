<template>
  <div class="min-h-screen bg-gray-50 py-8 lg:py-12 px-4 sm:px-6 lg:px-8 opacity-0 animate-fadeIn">
    <div class="max-w-7xl mx-auto">
      <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-6 text-center">Toutes les catégories</h1>
      <CategoryGrid :categories="categories" class="transition-all duration-300 hover:scale-105" />
    </div>
  </div>
</template>

<script setup lang="ts">
import CategoryGrid from '@/Components/Pages/Category/CategoryGrid.vue';
import type { ProductCategory } from '@/libs/types/product';

defineProps<{
  categories: ProductCategory[];
}>();
</script>

<style scoped>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.8s ease-out forwards;
}
</style>
