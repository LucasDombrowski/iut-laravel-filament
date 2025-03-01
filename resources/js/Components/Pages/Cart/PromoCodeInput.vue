<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { gsap } from 'gsap';
import type { PromoCode } from '@/libs/types/cart';

const promoCode = ref('');
const promoError = ref('');
const promoSuccess = ref('');
const formRef = ref<HTMLElement | null>(null);

const emit = defineEmits<{
  (e: 'apply-promo', promo: PromoCode): void;
}>();

const validatePromoCode = () => {
  // Reset messages
  promoError.value = '';
  promoSuccess.value = '';
  
  if (!promoCode.value) {
    promoError.value = 'Veuillez entrer un code promo';
    return;
  }
  
  // Simulate promo code validation
  // In a real app, this would be an API call
  if (promoCode.value.toUpperCase() === 'DISCOUNT10') {
    const validPromo: PromoCode = {
      code: promoCode.value.toUpperCase(),
      discount: 10,
      isValid: true
    };
    promoSuccess.value = 'Code promo appliqué : 10% de réduction';
    emit('apply-promo', validPromo);
  } else if (promoCode.value.toUpperCase() === 'DISCOUNT20') {
    const validPromo: PromoCode = {
      code: promoCode.value.toUpperCase(),
      discount: 20,
      isValid: true
    };
    promoSuccess.value = 'Code promo appliqué : 20% de réduction';
    emit('apply-promo', validPromo);
  } else {
    promoError.value = 'Code promo invalide';
    emit('apply-promo', { code: '', discount: 0, isValid: false });
  }
};

onMounted(() => {
  if (formRef.value) {
    gsap.from(formRef.value, {
      opacity: 0,
      x: -20,
      duration: 0.5,
      delay: 0.3,
      ease: 'power2.out'
    });
  }
});
</script>

<template>
  <div ref="formRef" class="p-6 bg-gradient-to-r from-indigo-50 to-blue-50">
    <h2 class="text-lg font-medium text-indigo-900 mb-3 flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
      </svg>
      Code promo
    </h2>
    <div class="mt-3 flex space-x-2">
      <input
        v-model="promoCode"
        type="text"
        placeholder="Entrez votre code promo"
        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3 border"
      />
      <button
        @click="validatePromoCode"
        class="rounded-lg border border-transparent bg-indigo-600 px-5 py-3 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 hover:shadow-md"
      >
        Appliquer
      </button>
    </div>
    <p v-if="promoError" class="mt-2 text-sm text-red-600 flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      {{ promoError }}
    </p>
    <p v-if="promoSuccess" class="mt-2 text-sm text-green-600 flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      {{ promoSuccess }}
    </p>
  </div>
</template>