<script setup lang="ts">
import { ref } from 'vue';

const emit = defineEmits<{
  (e: 'payment-complete', paymentInfo: any): void;
}>();

const cardNumber = ref('');
const cardName = ref('');
const expiryDate = ref('');
const cvv = ref('');

const formatCardNumber = (value: string) => {
  const v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
  const matches = v.match(/\d{4,16}/g);
  const match = matches && matches[0] || '';
  const parts = [];

  for (let i = 0, len = match.length; i < len; i += 4) {
    parts.push(match.substring(i, i + 4));
  }

  if (parts.length) {
    return parts.join(' ');
  } else {
    return value;
  }
};

const formatExpiryDate = (value: string) => {
  const v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
  
  if (v.length >= 2) {
    return `${v.substring(0, 2)}/${v.substring(2, 4)}`;
  }
  
  return v;
};

const onCardNumberInput = (e: Event) => {
  const input = e.target as HTMLInputElement;
  const { selectionStart } = input;
  const oldVal = input.value;
  const newVal = formatCardNumber(input.value);
  
  if (newVal !== oldVal) {
    input.value = newVal;
    if (selectionStart) {
      const diff = newVal.length - oldVal.length;
      input.setSelectionRange(selectionStart + diff, selectionStart + diff);
    }
  }
  
  cardNumber.value = newVal;
};

const onExpiryDateInput = (e: Event) => {
  const input = e.target as HTMLInputElement;
  const { selectionStart } = input;
  const oldVal = input.value;
  const newVal = formatExpiryDate(input.value);
  
  if (newVal !== oldVal) {
    input.value = newVal;
    if (selectionStart) {
      const diff = newVal.length - oldVal.length;
      input.setSelectionRange(selectionStart + diff, selectionStart + diff);
    }
  }
  
  expiryDate.value = newVal;
};

const submitPayment = () => {
  emit('payment-complete', {
    cardNumber: cardNumber.value,
    cardName: cardName.value,
    expiryDate: expiryDate.value,
    cvv: cvv.value
  });
};
</script>

<template>
  <div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations de paiement</h2>
    
    <form @submit.prevent="submitPayment" class="space-y-4">
      <div>
        <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">Numéro de carte</label>
        <div class="relative">
          <input 
            type="text" 
            id="card_number" 
            :value="cardNumber"
            @input="onCardNumberInput"
            placeholder="1234 5678 9012 3456"
            maxlength="19"
            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            required
          >
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
              <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
              <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </div>
      
      <div>
        <label for="card_name" class="block text-sm font-medium text-gray-700 mb-1">Nom sur la carte</label>
        <input 
          type="text" 
          id="card_name" 
          v-model="cardName" 
          placeholder="John Doe"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
          required
        >
      </div>
      
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration</label>
          <input 
            type="text" 
            id="expiry_date" 
            :value="expiryDate"
            @input="onExpiryDateInput"
            placeholder="MM/YY"
            maxlength="5"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            required
          >
        </div>
        
        <div>
          <label for="cvv" class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
          <input 
            type="text" 
            id="cvv" 
            v-model="cvv" 
            placeholder="123"
            maxlength="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            required
          >
        </div>
      </div>
      
      <div class="mt-6">
        <button 
          type="submit" 
          class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
          Payer
        </button>
      </div>
    </form>
  </div>
</template>