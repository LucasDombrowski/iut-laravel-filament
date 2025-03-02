<template>
  <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-4 transition-all duration-300 transform hover:scale-105 hover:shadow-xl hover:-translate-y-1">
    <Link 
      :href="route('categories.show', { slug: category.slug })" 
      class="block group focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg"
    >
      <div class="relative overflow-hidden rounded-lg">
        <img 
          :src="category.thumbnail" 
          :alt="category.name" 
          class="w-full h-48 object-cover rounded-lg transition-all duration-300 group-hover:opacity-90"
        >
        <div class="absolute inset-0 bg-gradient-to-b from-indigo-600 to-blue-500 opacity-0 group-hover:opacity-30 transition-opacity duration-300"></div>
      </div>
      <h2 class="text-xl font-semibold text-indigo-900 dark:text-white mt-4 group-hover:underline">
        {{ category.name }}
      </h2>
      <p class="text-gray-700 dark:text-gray-300 text-sm mt-2">
        {{ category.description }}
      </p>
    </Link>
  </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';
import type { ProductCategory } from '@/libs/types/product';
import { Link } from '@inertiajs/vue3';

defineProps<{ category: ProductCategory }>();
</script>
