<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 opacity-0 animate-fadeIn">
    <CategoryCard v-for="category in categories" :key="category.id" :category="category" />
  </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import CategoryCard from './CategoryCard.vue';
import type { ProductCategory } from '@/libs/types/product';

defineProps<{ categories: ProductCategory[] }>();
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
  animation: fadeIn 0.6s ease-out forwards;
}
</style>
