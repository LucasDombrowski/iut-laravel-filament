<script setup lang="ts">
import { ProductCategory } from '@/libs/types/product';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
  categories: ProductCategory[];
}>();

const currentPath = window.location.pathname;
</script>

<template>
  <div class="border-t border-gray-200 pt-6">
    <h3 class="text-sm font-semibold text-indigo-900 mb-3">Catégories</h3>
    <div class="flex flex-wrap gap-2">
      <Link
        v-for="category in categories" 
        :key="category.id"
        :href="route('categories.show', { slug: category.slug })"
        class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium transition-all duration-300 shadow-sm focus:ring-2 focus:ring-indigo-400"
        :class="currentPath.includes(category.slug) 
          ? 'bg-indigo-600 text-white shadow-md scale-105' 
          : 'bg-indigo-50 text-indigo-800 hover:bg-indigo-100 hover:text-indigo-900 hover:scale-105'"
      >
        {{ category.name }}
      </Link>
    </div>
  </div>
</template>
