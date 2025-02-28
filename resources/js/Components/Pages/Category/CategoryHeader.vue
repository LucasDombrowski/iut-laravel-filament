<script setup lang="ts">
import { ProductCategory } from '@/libs/types/product';
import { onMounted } from 'vue';
import gsap from 'gsap';

defineProps<{
  category: ProductCategory;
}>();

onMounted(() => {
  gsap.from('.category-header', {
    y: -50,
    opacity: 0,
    duration: 0.8,
    ease: 'power3.out'
  });
});
</script>

<template>
  <div class="category-header bg-white rounded-lg shadow-md overflow-hidden mb-8">
    <div class="relative h-48 overflow-hidden">
      <img 
        :src="category.thumbnail" 
        :alt="category.name"
        class="w-full h-full object-cover transition-transform duration-700 hover:scale-105"
      />
      <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
        <div class="p-6 text-white">
          <h1 class="text-3xl font-bold mb-2 page-title">{{ category.name }}</h1>
        </div>
      </div>
    </div>
    <div class="p-6">
      <p class="text-gray-700">{{ category.description }}</p>
      
      <div v-if="category.children && category.children.length > 0" class="mt-4">
        <h2 class="text-lg font-semibold mb-2">Sous-catégories</h2>
        <div class="flex flex-wrap gap-2">
          <a 
            v-for="child in category.children" 
            :key="child.id"
            :href="`/categories/${child.slug}`"
            class="inline-block px-4 py-2 bg-gray-100 hover:bg-primary-100 rounded-full text-sm font-medium transition-colors"
          >
            {{ child.name }}
          </a>
        </div>
      </div>
    </div>
  </div>
</template>