<script setup lang="ts">
import { ProductCategory } from '@/libs/types/product';
import gsap from 'gsap';
import { onMounted } from 'vue';

const props = defineProps<{
  categories: ProductCategory[];
}>();

onMounted(() => {
  gsap.from('.category-link', {
    y: 10,
    opacity: 0,
    stagger: 0.1,
    duration: 0.5,
    ease: 'power2.out'
  });
});
</script>

<template>
  <div class="border-t border-gray-200 pt-6">
    <h3 class="text-sm font-medium text-gray-900 mb-3">Catégories</h3>
    <div class="flex flex-wrap gap-2">
      <a 
        v-for="category in categories" 
        :key="category.id"
        href="#"
        class="category-link inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-800 hover:bg-gray-200 transition-colors"
      >
        {{ category.name }}
      </a>
    </div>
  </div>
</template>