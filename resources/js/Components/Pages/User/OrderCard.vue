<script setup lang="ts">
import { computed } from 'vue';
import { Order } from '@/libs/types/order';

const props = defineProps<{
  order: Order;
  statusColor: string;
}>();

const emit = defineEmits(['view-details']);

const formattedDate = computed(() => {
  const date = new Date(props.order.created_at);
  return new Intl.DateTimeFormat('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  }).format(date);
});

const total = computed(() => {
  return props.order.variants.reduce((acc, variant) => acc + variant.unit_price * variant.quantity, 0);
});

const discountedTotal = computed(() => {
  if (!props.order.discount) {
    return total.value;
  } else if (props.order.discount.type === 'percentage') {
    return total.value - (total.value * props.order.discount.value / 100);
  } else {
    return total.value - props.order.discount.value;
  }
});

const formattedTotal = computed(() => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR',
  }).format(discountedTotal.value);
});

const itemCount = computed(() => {
  return props.order.variants.reduce((acc, item) => acc + item.quantity, 0);
});
</script>

<template>
  <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200 hover:shadow-xl transition-all duration-300">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
      <!-- Informations commande -->
      <div>
        <div class="flex items-center space-x-2 mb-1">
          <span :class="`${statusColor} bg-opacity-20 px-2 py-1 rounded-full text-xs font-medium border`">
            {{ order.status }}
          </span>
          <span class="text-sm text-gray-500">{{ formattedDate }}</span>
        </div>
        <p class="font-medium text-gray-800">Commande #{{ order.id }}</p>
        <p class="text-sm text-gray-600">{{ itemCount }} article(s)</p>
      </div>
      
      <!-- Total & Bouton -->
      <div class="mt-2 sm:mt-0 flex flex-col sm:items-end">
        <p class="font-bold text-lg bg-gradient-to-r from-indigo-600 to-blue-500 text-transparent bg-clip-text">
          {{ formattedTotal }}
        </p>
        <button
          @click="emit('view-details', order)"
          class="mt-2 px-4 py-2 text-sm text-white bg-gradient-to-r from-indigo-600 to-blue-500 rounded-md 
          hover:scale-105 hover:shadow-lg transition-all duration-300 focus:ring-2 focus:ring-indigo-500"
        >
          Voir les détails
        </button>
      </div>
    </div>
  </div>
</template>
