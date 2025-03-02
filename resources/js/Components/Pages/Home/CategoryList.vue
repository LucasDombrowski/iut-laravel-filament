<script setup lang="ts">
import { ProductCategory } from '@/libs/types/product';

const props = defineProps<{ categories: ProductCategory[] }>();
</script>

<template>
  <section class="py-10 max-w-7xl mx-auto px-4">
    <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center bg-gradient-to-r from-indigo-600 to-blue-500 text-transparent bg-clip-text">Nos Catégories</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <div
        v-for="category in categories"
        :key="category.id"
        class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-1"
      >
        <div class="relative overflow-hidden group">
          <a :href="`/categories/${category.slug}`">
            <img :src="category.thumbnail" :alt="category.name" class="w-full h-40 object-cover transition-transform duration-500 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-indigo-600 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
          </a>
        </div>
        <div class="p-4 text-center">
          <h3 class="font-semibold text-lg text-gray-800 hover:text-indigo-600 transition-colors">{{ category.name }}</h3>
        </div>
      </div>
    </div>
  </section>
</template>
