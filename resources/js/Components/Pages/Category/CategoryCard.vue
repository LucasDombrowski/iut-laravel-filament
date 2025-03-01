<template>
  <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-4 transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
    <Link 
      :href="route('categories.show', { slug: category.slug })" 
      class="block group focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg"
    >
      <img 
        :src="category.thumbnail" 
        :alt="category.name" 
        class="w-full h-48 object-cover rounded-lg mb-4 transition-all duration-300 group-hover:opacity-90"
      >
      <h2 class="text-xl font-semibold text-gray-900 dark:text-white group-hover:underline">
        {{ category.name }}
      </h2>
      <p class="text-gray-600 dark:text-gray-300 text-sm mt-2">
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
