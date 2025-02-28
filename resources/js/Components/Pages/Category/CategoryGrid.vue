<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <CategoryCard v-for="category in categories" :key="category.id" :category="category" />
  </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import CategoryCard from './CategoryCard.vue';
import type { ProductCategory } from '@/libs/types/product';

defineProps<{ categories: ProductCategory[] }>();
</script>
