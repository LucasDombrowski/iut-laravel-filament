<template>
  <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 animate-opacity-100">
    <div class="max-w-7xl mx-auto">
      <h1 class="text-3xl font-bold text-indigo-800 mb-8 text-center border-b-4 border-indigo-500 pb-2 w-fit mx-auto">Toutes les catégories</h1>
      <CategoryGrid :categories="categories" class="transition-transform duration-300 hover:scale-105 bg-white rounded-lg shadow-lg p-6" />
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
