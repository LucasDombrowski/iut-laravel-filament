<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { gsap } from 'gsap';
import type { CartItem } from '@/libs/types/cart';

const props = defineProps<{
  item: CartItem;
}>();

const emit = defineEmits<{
  (e: 'update:quantity', quantity: number): void;
  (e: 'remove'): void;
}>();

const itemRef = ref<HTMLElement | null>(null);

const updateQuantity = (newQuantity: number) => {
  // Ensure quantity is within bounds
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

onMounted(() => {
  if (itemRef.value) {
    gsap.from(itemRef.value, {
      opacity: 0,
      y: 20,
      duration: 0.5,
      ease: 'power2.out'
    });
  }
});
</script>

<template>
  <div ref="itemRef" class="flex items-center py-6 px-4 hover:bg-indigo-50 transition-colors duration-300">
    <!-- Product Image -->
    <div class="w-28 h-28 flex-shrink-0 overflow-hidden rounded-lg border border-gray-200 shadow-sm">
      <img :src="item.variant.thumbnail" :alt="item.variant.product.name" class="h-full w-full object-cover object-center transform hover:scale-105 transition-transform duration-300">
    </div>

    <!-- Product Details -->
    <div class="ml-6 flex flex-1 flex-col">
      <div class="flex justify-between">
        <div>
          <h3 class="text-lg font-medium text-indigo-900">{{ item.variant.product.name }}</h3>
          <div class="mt-2 flex space-x-4">
            <p class="text-sm text-gray-600">
              <span class="font-medium text-gray-700">Couleur:</span> 
              <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                {{ item.variant.color.label }}
              </span>
            </p>
            <p class="text-sm text-gray-600">
              <span class="font-medium text-gray-700">Taille:</span>
              <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ item.variant.size.label }}
              </span>
            </p>
          </div>
        </div>
        <p class="text-right font-medium text-indigo-900">{{ formattedPrice }}</p>
      </div>

      <div class="flex items-center justify-between mt-6">
        <!-- Quantity Controls -->
        <div class="flex items-center">
          <button 
            @click="updateQuantity(item.quantity - 1)" 
            class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center hover:bg-indigo-200 transition-colors"
            :disabled="item.quantity <= 1"
            :class="{'opacity-50 cursor-not-allowed': item.quantity <= 1}"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
          </button>
          <span class="mx-3 w-8 text-center font-medium">{{ item.quantity }}</span>
          <button 
            @click="updateQuantity(item.quantity + 1)" 
            class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center hover:bg-indigo-200 transition-colors"
            :disabled="item.quantity >= item.variant.stock"
            :class="{'opacity-50 cursor-not-allowed': item.quantity >= item.variant.stock}"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
          </button>
        </div>

        <!-- Stock Info -->
        <p class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-md">
          <span class="font-medium">Stock:</span> {{ item.variant.stock }}
        </p>

        <!-- Total Price -->
        <p class="font-medium text-lg text-indigo-900">
          Total: <span class="font-bold">{{ totalPrice }}</span>
        </p>

        <!-- Remove Button -->
        <button 
          @click="emit('remove')" 
          class="text-red-500 hover:text-red-700 transition-colors flex items-center space-x-1 group"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
          <span>Supprimer</span>
        </button>
      </div>
    </div>
  </div>
</template>