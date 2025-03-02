<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<{
  baseCode?: string;
}>();

const promoCode = ref(props.baseCode || '');
const promoError = ref('');
const promoSuccess = ref('');

const emit = defineEmits<{
  (e: 'apply-promo', code: string): void;
}>();

const validatePromoCode = () => {
  promoError.value = '';
  promoSuccess.value = '';

  if (!promoCode.value.trim()) {
    promoError.value = 'Veuillez entrer un code promo';
    return;
  }

  emit('apply-promo', promoCode.value);
};
</script>

<template>
  <div class="p-6 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg shadow-md w-full">
    <!-- Titre -->
    <h2 class="text-lg font-medium text-indigo-900 mb-3 flex items-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
      </svg>
      Code promo
    </h2>

    <!-- Input + Bouton -->
    <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
      <input
        v-model="promoCode"
        type="text"
        placeholder="Entrez votre code promo"
        class="w-full sm:w-auto flex-grow rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 border"
      />
      <button
        @click="validatePromoCode"
        class="w-full sm:w-auto px-5 py-3 rounded-lg bg-indigo-600 text-white font-medium shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-300"
      >
        Appliquer
      </button>
    </div>

    <!-- Messages d'erreur ou de succès -->
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
