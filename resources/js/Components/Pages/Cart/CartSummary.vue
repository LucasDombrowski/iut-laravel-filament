<script setup lang="ts">
import { computed, ref, onMounted, watch } from 'vue';
import type { CartItem } from '@/libs/types/cart';
import { Discount } from '@/libs/types/order';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
  items: CartItem[];
  cartDiscount?: Discount
}>();

const summaryRef = ref<HTMLElement | null>(null);
const totalRef = ref<HTMLElement | null>(null);

const subtotal = computed(() => {
  return props.items.reduce((total, item) => total + (item.variant.price * item.quantity), 0);
});

const isDiscountValid = computed(()=>{
  return props.cartDiscount && Date.now() < new Date(props.cartDiscount.end_at).getTime() && Date.now() > new Date(props.cartDiscount.start_at).getTime() && subtotal.value >= props.cartDiscount.min_price;
})

const discount = computed(() => {
  if (isDiscountValid.value) {
    return props.cartDiscount.type === 'percentage' ? subtotal.value * (props.cartDiscount.value / 100) : props.cartDiscount.value;
  }
  return 0;
});

const total = computed(() => {
  return subtotal.value - discount.value;
});

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR'
  }).format(price);
};

</script>

<template>
  <div ref="summaryRef" class="bg-white rounded-xl p-6 shadow-lg border border-indigo-100">
    <h2 class="text-xl font-semibold text-indigo-900 mb-6 pb-2 border-b-2 border-indigo-100">
      Récapitulatif de la commande
    </h2>
    
    <div class="space-y-4">
      <div class="flex justify-between items-center">
        <p class="text-gray-600">Sous-total</p>
        <p class="font-medium text-gray-800">{{ formatPrice(subtotal) }}</p>
      </div>
      
      <div v-if="discount && isDiscountValid" class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
        <div class="flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-green-700">{{ cartDiscount.type === "percentage" ? `${cartDiscount.value}%` : `${cartDiscount.value}€` }}</p>
        </div>
        <p class="font-medium text-green-700">-{{ formatPrice(discount) }}</p>
      </div>
      
      <div class="border-t-2 border-indigo-100 pt-4 mt-4">
        <div class="flex justify-between items-center">
          <p class="text-lg font-semibold text-indigo-900">Total</p>
          <p ref="totalRef" class="text-xl font-bold text-indigo-700">{{ formatPrice(total) }}</p>
        </div>
      </div>
    </div>
    
    <Link 
    class="block text-center w-full mt-8 bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-4 px-6 rounded-lg hover:from-indigo-700 hover:to-blue-600 transition-all duration-300 font-medium shadow-md hover:shadow-lg transform hover:-translate-y-1"
    :href="route('checkout')"
    >
      Procéder au paiement
    </Link>
    
    <div class="mt-6 flex items-center justify-center space-x-4 text-sm text-gray-500">
      <div class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
        Paiement sécurisé
      </div>
      <div class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
        </svg>
        Plusieurs moyens de paiement
      </div>
    </div>
  </div>
</template>