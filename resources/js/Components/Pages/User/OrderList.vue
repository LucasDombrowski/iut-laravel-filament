<script setup lang="ts">
import { ref } from 'vue';
import { Order } from '@/libs/types/order';
import OrderCard from './OrderCard.vue';
import OrderDetails from './OrderDetails.vue';
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
  </div>
</template>