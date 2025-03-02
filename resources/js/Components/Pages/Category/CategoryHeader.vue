<script setup lang="ts">
import { ProductCategory } from '@/libs/types/product';

defineProps<{
  category: ProductCategory;
}>();
</script>

<template>
  <div class="category-header bg-white rounded-lg shadow-md overflow-hidden mb-8 animate-opacity-100">
    <div class="relative h-48 overflow-hidden">
      <img 
        :src="category.thumbnail" 
        :alt="category.name"
        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 hover:-translate-y-1"
      />
      <div class="absolute inset-0 bg-gradient-to-t from-indigo-600 to-blue-500 opacity-80 flex items-end">
        <div class="p-6 text-white">
          <h1 class="text-3xl font-bold text-indigo-100 mb-2">{{ category.name }}</h1>
        </div>
      </div>
    </div>
    
    <div class="p-6">
      <p class="text-gray-700">{{ category.description }}</p>
      
      <div v-if="category.children && category.children.length > 0" class="mt-4">
        <h2 class="text-lg font-semibold text-indigo-900 mb-2">Sous-catégories</h2>
        <div class="flex flex-wrap gap-3">
          <a 
            v-for="child in category.children" 
            :key="child.id"
            :href="`/categories/${child.slug}`"
            class="inline-block px-4 py-2 bg-gray-100 hover:bg-indigo-100 rounded-full text-sm font-medium transition-all transform hover:scale-105 focus:ring-2 focus:ring-indigo-500"
          >
            {{ child.name }}
          </a>
        </div>
      </div>
    </div>
  </div>
</template>
