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

const formattedPrice = (price: number) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR',
  }).format(price);
};

const discountAmount = computed(() => {
  if (!props.order.discount) return 0;
  return props.order.discount.type === 'percentage'
    ? (total.value * props.order.discount.value) / 100
    : props.order.discount.value;
});

const discountedTotal = computed(() => total.value - discountAmount.value);
</script>

<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[85vh] overflow-auto opacity-0 animate-fadeIn hover:shadow-2xl hover:scale-105 transition-all duration-300">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <div>
            <h3 class="text-xl font-bold text-gray-800">Commande #{{ order.id }}</h3>
            <div class="flex items-center space-x-2 mt-1">
              <span :class="`${statusColor} px-2 py-1 rounded-full text-xs font-medium border`">
                {{ order.status }}
              </span>
              <span class="text-sm text-gray-500">{{ formattedDate }}</span>
            </div>
          </div>
          <button 
            @click="emit('close')" 
            class="p-2 text-gray-500 hover:bg-gray-200 rounded-full transition-all hover:scale-110"
          >
            ✖
          </button>
        </div>

        <!-- Adresse de livraison -->
        <div class="mb-6">
          <h4 class="font-medium text-gray-800 mb-2">Adresse de livraison</h4>
          <div class="bg-gray-50 p-3 rounded-md text-gray-700">
            <p>{{ order.address_number }} {{ order.address_street }}</p>
            <p v-if="order.address_complement">{{ order.address_complement }}</p>
            <p>{{ order.address_zip_code }} {{ order.address_city }}</p>
            <p>{{ order.address_country_code }}</p>
          </div>
        </div>

        <!-- Liste des articles -->
        <div class="mb-6">
          <h4 class="font-medium text-gray-800 mb-2">Articles</h4>
          <div class="border rounded-md divide-y">
            <div
              v-for="(item, index) in order.variants"
              :key="index"
              class="p-3 flex items-center hover:bg-gray-100 transition-all duration-300"
            >
              <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                <img
                  :src="item.variant.thumbnail"
                  :alt="item.variant.product.name"
                  class="h-full w-full object-cover"
                />
              </div>
              <div class="ml-4 flex-1">
                <h5 class="font-medium text-gray-800">{{ item.variant.product.name }}</h5>
                <p class="text-sm text-gray-600">
                  {{ item.variant.color.label }}, {{ item.variant.size.label }}
                </p>
                <div class="flex justify-between mt-1">
                  <p class="text-sm text-gray-700">Qté: {{ item.quantity }}</p>
                  <p class="font-medium text-gray-900">{{ formattedPrice(item.unit_price * item.quantity) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Récapitulatif des prix -->
        <div class="border-t border-gray-200 pt-4">
          <div class="flex justify-between mb-2">
            <span class="text-gray-600">Sous-total</span>
            <span class="font-medium text-gray-900">{{ formattedPrice(total) }}</span>
          </div>
          <div v-if="order.discount" class="flex justify-between mb-2 text-green-600 font-medium">
            <span>Réduction ({{ order.discount.code }})</span>
            <span>-{{ formattedPrice(discountAmount) }}</span>
          </div>
          <div class="flex justify-between font-bold text-lg mt-2">
            <span class="text-gray-900">Total</span>
            <span class="bg-gradient-to-r from-indigo-600 to-blue-500 text-white px-3 py-1 rounded-md shadow-md">
              {{ formattedPrice(discountedTotal) }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.3s ease-out forwards;
}
</style>
