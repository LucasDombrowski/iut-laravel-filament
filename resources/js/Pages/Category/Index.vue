<template>
  <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Toutes les catégories</h1>
      <CategoryGrid :categories="categories" />
    </div>
  </div>
</template>

<script setup lang="ts">
import CategoryGrid from '@/Components/Pages/Category/CategoryGrid.vue';
import type { ProductCategory } from '@/libs/types/product';
import { ref } from 'vue';

const props = defineProps<{
  categories: ProductCategory[];
}>();
</script>
