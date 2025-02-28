<script setup lang="ts">
import { Product } from '@/libs/types/product';
import ProductCard from './ProductCard.vue';
import { nextTick, onMounted } from 'vue';
import gsap from 'gsap';

defineProps<{
    products: Product[];
}>();
</script>

<template>
    <div v-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <ProductCard v-for="(product, index) in products" :key="product.id" :product="product" />
    </div>
    <div v-else class="flex flex-col items-center justify-center py-12 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-1">Aucun produit trouvé</h3>
        <p class="text-gray-500">Essayez de modifier vos filtres ou votre recherche.</p>
    </div>
</template>