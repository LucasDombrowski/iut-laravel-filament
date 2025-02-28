<script setup lang="ts">
import { ref } from 'vue';
import { Order } from '@/libs/types/order';
import OrderCard from './OrderCard.vue';
import OrderDetails from './OrderDetails.vue';

const props = defineProps<{
  orders: Order[];
}>();

const selectedOrder = ref<Order | null>(null);
const showOrderDetails = ref(false);

const viewOrderDetails = (order: Order) => {
  selectedOrder.value = order;
  showOrderDetails.value = true;
};

const closeOrderDetails = () => {
  showOrderDetails.value = false;
  selectedOrder.value = null;
};

const getStatusColor = (status: string) => {
  switch (status.toLowerCase()) {
    case 'pending':
    case 'en attente':
      return 'bg-yellow-100 text-yellow-800';
    case 'processing':
    case 'en cours':
      return 'bg-blue-100 text-blue-800';
    case 'shipped':
    case 'expédiée':
      return 'bg-purple-100 text-purple-800';
    case 'delivered':
    case 'livrée':
      return 'bg-green-100 text-green-800';
    case 'cancelled':
    case 'annulée':
      return 'bg-red-100 text-red-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
};
</script>

<template>
  <div class="w-full">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Mes commandes</h2>

    <div v-if="orders.length === 0" class="text-center py-10">
      <p class="text-gray-500">Vous n'avez pas encore passé de commande.</p>
    </div>

    <div v-else class="space-y-4">
      <OrderCard
        v-for="order in orders"
        :key="order.id"
        :order="order"
        :status-color="getStatusColor(order.status)"
        @view-details="viewOrderDetails"
      />
    </div>

    <OrderDetails
      v-if="showOrderDetails && selectedOrder"
      :order="selectedOrder"
      :status-color="getStatusColor(selectedOrder.status)"
      @close="closeOrderDetails"
    />
  </div>
</template>