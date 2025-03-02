<script setup lang="ts">
import { Product } from '@/libs/types/product';
import ProductCard from '../Category/ProductCard.vue';

const props = defineProps<{ highlightedProducts: Product[] }>();
</script>

<template>
  <section class="py-10 max-w-7xl mx-auto px-4">
    <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center bg-gradient-to-r from-indigo-600 to-blue-500 text-transparent bg-clip-text">Nos Sélections</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <ProductCard v-for="product in highlightedProducts" :key="product.id" :product="product" />
    </div>
  </section>
</template>
