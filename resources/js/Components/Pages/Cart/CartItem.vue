<script setup lang="ts">
import { ref, computed } from 'vue';
import type { CartItem } from '@/libs/types/cart';

const props = defineProps<{
  item: CartItem;
}>();

const emit = defineEmits<{
  (e: 'update:quantity', quantity: number): void;
  (e: 'remove'): void;
}>();

const updateQuantity = (newQuantity: number) => {
  const validQuantity = Math.max(1, Math.min(newQuantity, props.item.variant.stock));
  emit('update:quantity', validQuantity);
};

const formattedPrice = computed(() => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR'
  }).format(props.item.variant.price);
});

const totalPrice = computed(() => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR'
  }).format(props.item.variant.price * props.item.quantity);
});
</script>

<template>
  <div class="flex flex-col sm:flex-row items-center w-full p-4 border-b border-gray-200 bg-white hover:bg-gray-50 transition-all duration-300">
    
    <!-- Image du produit -->
    <div class="w-full sm:w-24 h-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 shadow-sm">
      <img 
        :src="item.variant.thumbnail" 
        :alt="item.variant.product.name" 
        class="h-full w-full object-cover transform hover:scale-105 transition-transform duration-300"
      />
    </div>

    <!-- Détails du produit -->
    <div class="flex flex-col sm:flex-row sm:items-center flex-grow w-full mt-4 sm:mt-0 sm:ml-6">
      <div class="flex-grow">
        <h3 class="text-lg font-semibold text-indigo-900">{{ item.variant.product.name }}</h3>
        <div class="flex flex-wrap gap-2 mt-1 text-sm">
          <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800">{{ item.variant.color.label }}</span>
          <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">{{ item.variant.size.label }}</span>
        </div>
      </div>

      <!-- Prix & Quantité -->
      <div class="flex sm:flex-col items-center sm:items-end sm:ml-auto mt-4 sm:mt-0">
        <p class="text-lg font-bold text-indigo-900">{{ totalPrice }}</p>
        
        <div class="flex items-center mt-2 sm:mt-3">
          <button 
            @click="updateQuantity(item.quantity - 1)" 
            class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center hover:bg-indigo-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="item.quantity <= 1"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>

          <span class="mx-3 w-8 text-center font-medium text-gray-800">{{ item.quantity }}</span>

          <button 
            @click="updateQuantity(item.quantity + 1)" 
            class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center hover:bg-indigo-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="item.quantity >= item.variant.stock"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
          </button>
        </div>

        <!-- Supprimer l'article -->
        <button 
          @click="emit('remove')" 
          class="mt-3 sm:mt-4 px-3 py-1 text-sm text-red-500 hover:text-red-700 transition-all flex items-center space-x-1"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
          <span>Supprimer</span>
        </button>
      </div>
    </div>
  </div>
</template>
