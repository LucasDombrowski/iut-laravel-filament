<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const emptyCartRef = ref<HTMLElement | null>(null);

</script>

<template>
  <div ref="emptyCartRef" class="flex flex-col items-center justify-center py-16 px-4">
    <div class="w-32 h-32 bg-indigo-100 rounded-full flex items-center justify-center mb-6">
      <svg class="w-16 h-16 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
      </svg>
    </div>
    <h2 class="text-2xl font-bold text-indigo-900 mb-2">Votre panier est vide</h2>
    <p class="text-gray-600 text-center max-w-md mb-8">Ajoutez des articles à votre panier pour commencer vos achats</p>
    <Link :href="route('categories.index')" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-500 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
      </svg>
      Continuer vos achats
    </Link>
  </div>
</template>