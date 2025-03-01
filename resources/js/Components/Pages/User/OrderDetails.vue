<script setup lang="ts">
import { computed } from 'vue';
import { Order } from '@/libs/types/order';

const props = defineProps<{
  order: Order;
  statusColor: string;
}>();

const emit = defineEmits(['close']);

const formattedDate = computed(() => {
  const date = new Date(props.order.created_at);
  return new Intl.DateTimeFormat('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  }).format(date);
});

const total = computed(() => {
  return props.order.variants.reduce((acc, item) => acc + item.unit_price * item.quantity, 0);
});

const formattedTotal = computed(() => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR',
  }).format(total.value);
});

const formattedPrice = (price: number) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR',
  }).format(price);
};

const discountAmount = computed(() => {
  if (!props.order.discount) return 0;
  
  if (props.order.discount.type === 'percentage') {
    return (total.value * props.order.discount.value) / 100;
  } else {
    return props.order.discount.value;
  }
});

const formattedDiscount = computed(() => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR',
  }).format(discountAmount.value);
});

const subtotal = computed(() => {
  return total.value + discountAmount.value;
});

const formattedSubtotal = computed(() => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR',
  }).format(subtotal.value);
});
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] overflow-auto">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <div>
            <h3 class="text-xl font-bold text-gray-800">Commande #{{ order.id }}</h3>
            <div class="flex items-center space-x-2 mt-1">
              <span :class="statusColor" class="px-2 py-1 rounded-full text-xs font-medium">
                {{ order.status }}
              </span>
              <span class="text-sm text-gray-500">{{ formattedDate }}</span>
            </div>
          </div>
          <button @click="emit('close')" class="text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="mb-6">
          <h4 class="font-medium text-gray-800 mb-2">Adresse de livraison</h4>
          <div class="bg-gray-50 p-3 rounded-md">
            <p>{{ order.address_number }} {{ order.address_street }}</p>
            <p v-if="order.address_complement">{{ order.address_complement }}</p>
            <p>{{ order.address_zip_code }} {{ order.address_city }}</p>
            <p>{{ order.address_country_code }}</p>
          </div>
        </div>

        <div class="mb-6">
          <h4 class="font-medium text-gray-800 mb-2">Articles</h4>
          <div class="border rounded-md divide-y">
            <div
              v-for="(item, index) in order.variants"
              :key="index"
              class="p-3 flex items-center"
            >
              <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                <img
                  :src="item.variant.thumbnail"
                  :alt="item.variant.product.name"
                  class="h-full w-full object-cover object-center"
                />
              </div>
              <div class="ml-4 flex-1">
                <h5 class="font-medium text-gray-800">{{ item.variant.product.name }}</h5>
                <p class="text-sm text-gray-600">
                  {{ item.variant.color.label }}, {{ item.variant.size.label }}
                </p>
                <div class="flex justify-between mt-1">
                  <p class="text-sm">Qté: {{ item.quantity }}</p>
                  <p class="font-medium">{{ formattedPrice(item.unit_price * item.quantity) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="border-t pt-4">
          <div class="flex justify-between mb-2">
            <span class="text-gray-600">Sous-total</span>
            <span>{{ formattedSubtotal }}</span>
          </div>
          <div v-if="order.discount" class="flex justify-between mb-2 text-green-600">
            <span>Réduction ({{ order.discount.code }})</span>
            <span>-{{ formattedDiscount }}</span>
          </div>
          <div class="flex justify-between font-bold text-lg mt-2">
            <span>Total</span>
            <span>{{ formattedTotal }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>