<script setup lang="ts">
import { ref, computed } from 'vue';

const emit = defineEmits<{
  (e: 'payment-complete', paymentInfo: any): void;
}>();

const cardNumber = ref('');
const cardName = ref('');
const expiryDate = ref('');
const cvv = ref('');
const errors = ref<{ [key: string]: string }>({});

const cardType = computed(() => {
  if (/^4/.test(cardNumber.value)) return 'visa';
  if (/^5[1-5]/.test(cardNumber.value)) return 'mastercard';
  if (/^3[47]/.test(cardNumber.value)) return 'amex';
  return 'default';
});

const formatCardNumber = (value: string) => {
  return value.replace(/\D/g, '').replace(/(.{4})/g, '$1 ').trim();
};

const formatExpiryDate = (value: string) => {
  return value.replace(/\D/g, '').replace(/(\d{2})(\d{0,2})/, '$1/$2').substring(0, 5);
};

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

const validateForm = () => {
  errors.value = {};

  if (!isValidCardNumber(cardNumber.value)) errors.value.cardNumber = 'Numéro de carte invalide';
  if (!cardName.value.trim()) errors.value.cardName = 'Nom requis';
  if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(expiryDate.value)) errors.value.expiryDate = 'Date invalide (MM/YY)';
  if (!/^\d{3,4}$/.test(cvv.value)) errors.value.cvv = 'CVV invalide';

  return Object.keys(errors.value).length === 0;
};

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
  <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 transition-all duration-300 hover:shadow-xl w-full mx-auto">
    <h2 class="text-xl font-semibold text-gray-800 bg-gradient-to-r from-indigo-600 to-blue-500 text-transparent bg-clip-text mb-6 text-center sm:text-left">
      Informations de paiement
    </h2>

    <form @submit.prevent="submitPayment" class="space-y-5">
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
            class="w-full px-3 py-3 border rounded-md focus:ring-2 focus:ring-indigo-500 transition-all duration-200"
            :class="errors.cardNumber ? 'border-red-500' : 'border-gray-300'"
          />
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
          class="w-full px-3 py-3 border rounded-md focus:ring-2 focus:ring-indigo-500 transition-all duration-200"
          :class="errors.cardName ? 'border-red-500' : 'border-gray-300'"
        />
        <p v-if="errors.cardName" class="text-red-500 text-sm mt-1">{{ errors.cardName }}</p>
      </div>

      <!-- Date d'expiration & CVV -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration</label>
          <input
            type="text"
            id="expiry_date"
            v-model="expiryDate"
            @input="expiryDate = formatExpiryDate(expiryDate)"
            placeholder="MM/YY"
            maxlength="5"
            class="w-full px-3 py-3 border rounded-md focus:ring-2 focus:ring-indigo-500 transition-all duration-200"
            :class="errors.expiryDate ? 'border-red-500' : 'border-gray-300'"
          />
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
            class="w-full px-3 py-3 border rounded-md focus:ring-2 focus:ring-indigo-500 transition-all duration-200"
            :class="errors.cvv ? 'border-red-500' : 'border-gray-300'"
          />
          <p v-if="errors.cvv" class="text-red-500 text-sm mt-1">{{ errors.cvv }}</p>
        </div>
      </div>

      <!-- Bouton de paiement -->
      <div class="mt-6 flex justify-center">
        <button
          type="submit"
          class="w-full bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-3 px-6 rounded-md hover:shadow-md hover:scale-105 transition-all duration-300"
        >
          Payer
        </button>
      </div>
    </form>
  </div>
</template>
