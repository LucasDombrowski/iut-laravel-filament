<script setup lang="ts">
import { Order } from '@/libs/types/order';
import OrderCard from './OrderCard.vue';
import { getStatusColor } from '@/libs/utils/order';
import { Link } from '@inertiajs/vue3';

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
  <div class="w-full opacity-0 animate-fadeIn">
    <h2 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-blue-500 text-transparent bg-clip-text mb-6">
      Mes commandes
    </h2>

    <div v-if="orders.length === 0" class="text-center py-10">
      <p class="text-gray-600 text-lg">Vous n'avez pas encore passé de commande.</p>
      <Link
        :href="route('categories.index')"
        class="mt-4 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-500 text-white rounded-md hover:scale-105 hover:shadow-lg 
        transition-all duration-300 focus:ring-2 focus:ring-indigo-500"
      >
        Découvrir nos produits
      </Link>
    </div>

    <div v-else class="space-y-4 mt-4">
      <OrderCard
        v-for="order in orders"
        :key="order.id"
        :order="order"
        :status-color="getStatusColor(order.status)"
        @view-details="viewOrderDetails"
        class="hover:shadow-lg transition-all duration-300"
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
