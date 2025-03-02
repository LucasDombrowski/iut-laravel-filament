<script setup lang="ts">
import { computed } from 'vue';
import { Discount } from '@/libs/types/order';

const props = defineProps<{ discount: Discount }>();

const discountText = computed(() => {
  return props.discount.type === 'percentage'
    ? `${props.discount.value}% de réduction dès ${props.discount.min_price}€ d'achats`
    : `d'une réduction de ${props.discount.value}€ dès ${props.discount.min_price}€ d'achats`;
});
</script>

<template>
  <div class="w-full bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-4 text-center font-bold">
    Profitez <span class="underline">{{ discountText }}</span> avec le code :
    <span class="bg-white text-indigo-600 px-3 py-1 rounded-md shadow-md">{{ discount.code }}</span>
  </div>
</template>
