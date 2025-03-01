<script setup lang="ts">
import { Order } from '@/libs/types/order';
import OrderCard from './OrderCard.vue';
import { getStatusColor } from '@/libs/utils/order';

const props = defineProps<{
  orders: Order[];
}>();

const emit = defineEmits<{
  (e: 'view-details', order: Order): void;
}>();

const viewOrderDetails = (order: Order) => {
  emit('view-details', order);
};
</script>

<template>
  <div class="w-full animate-fadeIn">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Mes commandes</h2>

    <div v-if="orders.length === 0" class="text-center py-10">
      <p class="text-gray-600 text-lg">Vous n'avez pas encore passé de commande.</p>
      <button class="mt-4 px-5 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-all">
        Découvrir nos produits
      </button>
    </div>

    <div v-else class="space-y-4 mt-4">
      <OrderCard
        v-for="order in orders"
        :key="order.id"
        :order="order"
        :status-color="getStatusColor(order.status)"
        @view-details="viewOrderDetails"
        class="hover:scale-[1.02] transition-transform duration-200"
      />
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
