<script setup lang="ts">
import { ref, computed } from 'vue';

// Événements émis vers le parent
const emit = defineEmits<{
  (e: 'payment-complete', paymentInfo: any): void;
}>();

// Champs du formulaire
const cardNumber = ref('');
const cardName = ref('');
const expiryDate = ref('');
const cvv = ref('');

// Erreurs
const errors = ref<{ [key: string]: string }>({});

// Détection du type de carte bancaire
const cardType = computed(() => {
  if (/^4/.test(cardNumber.value)) return 'visa';
  if (/^5[1-5]/.test(cardNumber.value)) return 'mastercard';
  if (/^3[47]/.test(cardNumber.value)) return 'amex';
  return 'default';
});

// Formatage du numéro de carte
const formatCardNumber = (value: string) => {
  return value
    .replace(/\D/g, '') // Supprime tout sauf les chiffres
    .replace(/(.{4})/g, '$1 ') // Ajoute un espace tous les 4 chiffres
    .trim();
};

// Formatage de la date d'expiration (MM/YY)
const formatExpiryDate = (value: string) => {
  return value
    .replace(/\D/g, '') // Supprime tout sauf les chiffres
    .replace(/(\d{2})(\d{0,2})/, '$1/$2') // Ajoute le slash après MM
    .substring(0, 5);
};

// Validation de la carte avec l'algorithme de Luhn
const isValidCardNumber = (number: string) => {
  const digits = number.replace(/\D/g, '').split('').reverse();
  const checksum = digits.reduce((sum, digit, index) => {
    let num = parseInt(digit, 10);
    if (index % 2 === 1) num *= 2;
    if (num > 9) num -= 9;
    return sum + num;
  }, 0);
  return checksum % 10 === 0;
};

// Validation du formulaire
const validateForm = () => {
  errors.value = {};

  if (!isValidCardNumber(cardNumber.value)) {
    errors.value.cardNumber = 'Numéro de carte invalide';
  }
  
  if (!cardName.value.trim()) {
    errors.value.cardName = 'Nom requis';
  }
  
  if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiryDate.value)) {
    errors.value.expiryDate = 'Date invalide (MM/YY)';
  }
  
  if (!/^\d{3,4}$/.test(cvv.value)) {
    errors.value.cvv = 'CVV invalide';
  }

  return Object.keys(errors.value).length === 0;
};

// Soumission du paiement
const submitPayment = () => {
  if (!validateForm()) return;

  emit('payment-complete', {
    cardNumber: cardNumber.value.replace(/\s/g, ''),
    cardName: cardName.value,
    expiryDate: expiryDate.value,
    cvv: cvv.value,
  });
};
</script>

<template>
  <div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations de paiement</h2>

    <form @submit.prevent="submitPayment" class="space-y-4">
      <!-- Numéro de carte -->
      <div>
        <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">Numéro de carte</label>
        <div class="relative">
          <input
            type="text"
            id="card_number"
            v-model="cardNumber"
            @input="cardNumber = formatCardNumber(cardNumber)"
            placeholder="1234 5678 9012 3456"
            maxlength="19"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500"
            :class="errors.cardNumber ? 'border-red-500' : 'border-gray-300'"
          >
        </div>
        <p v-if="errors.cardNumber" class="text-red-500 text-sm mt-1">{{ errors.cardNumber }}</p>
      </div>

      <!-- Nom sur la carte -->
      <div>
        <label for="card_name" class="block text-sm font-medium text-gray-700 mb-1">Nom sur la carte</label>
        <input
          type="text"
          id="card_name"
          v-model="cardName"
          placeholder="John Doe"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500"
          :class="errors.cardName ? 'border-red-500' : 'border-gray-300'"
        >
        <p v-if="errors.cardName" class="text-red-500 text-sm mt-1">{{ errors.cardName }}</p>
      </div>

      <!-- Date d'expiration & CVV -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration</label>
          <input
            type="text"
            id="expiry_date"
            v-model="expiryDate"
            @input="expiryDate = formatExpiryDate(expiryDate)"
            placeholder="MM/YY"
            maxlength="5"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500"
            :class="errors.expiryDate ? 'border-red-500' : 'border-gray-300'"
          >
          <p v-if="errors.expiryDate" class="text-red-500 text-sm mt-1">{{ errors.expiryDate }}</p>
        </div>

        <div>
          <label for="cvv" class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
          <input
            type="text"
            id="cvv"
            v-model="cvv"
            placeholder="123"
            maxlength="4"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500"
            :class="errors.cvv ? 'border-red-500' : 'border-gray-300'"
          >
          <p v-if="errors.cvv" class="text-red-500 text-sm mt-1">{{ errors.cvv }}</p>
        </div>
      </div>

      <!-- Bouton de paiement -->
      <div class="mt-6">
        <button
          type="submit"
          class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500"
        >
          Payer
        </button>
      </div>
    </form>
  </div>
</template>
