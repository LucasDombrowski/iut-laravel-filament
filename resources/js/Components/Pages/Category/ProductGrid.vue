<script setup lang="ts">
import { Product } from '@/libs/types/product';
import ProductCard from './ProductCard.vue';

defineProps<{
    products: Product[];
}>();
</script>

<template>
    <div v-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 animate-opacity-100">
        <ProductCard 
            v-for="(product, index) in products" 
            :key="product.id" 
            :product="product" 
            class="transition-transform duration-300 hover:scale-105"
        />
    </div>
    
    <div v-else class="flex flex-col items-center justify-center py-12 text-center animate-opacity-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4 animate-pulse" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-1">Aucun produit trouvé</h3>
        <p class="text-gray-700">Essayez de modifier vos filtres ou votre recherche.</p>
        <button 
            @click="$emit('reset-filters')"
            class="mt-4 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-500 text-white rounded-lg hover:from-indigo-700 hover:to-blue-600 transition-all duration-300"
        >
            Réinitialiser les filtres
        </button>
    </div>
</template>
