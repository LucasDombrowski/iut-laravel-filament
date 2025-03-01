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

const total = computed(()=>{
    return props.order.variants.reduce((acc, variant) => acc + variant.unit_price * variant.quantity, 0);
})

const discountedTotal = computed(()=>{
    if(!props.order.discount){
        return total.value
    } else if(props.order.discount.type === 'percentage'){
        return total.value - (total.value * props.order.discount.value / 100)
    } else {
        return total.value - props.order.discount.value
    }
})

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
  <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center">
      <div>
        <div class="flex items-center space-x-2 mb-2">
          <span :class="statusColor" class="px-2 py-1 rounded-full text-xs font-medium">
            {{ order.status }}
          </span>
          <span class="text-sm text-gray-500">{{ formattedDate }}</span>
        </div>
        <p class="font-medium">Commande #{{ order.id }}</p>
        <p class="text-sm text-gray-600">{{ itemCount }} article(s)</p>
      </div>
      
      <div class="mt-3 md:mt-0 flex flex-col md:items-end">
        <p class="font-bold text-lg">{{ formattedTotal }}</p>
        <button
          @click="emit('view-details', order)"
          class="mt-2 px-4 py-1 text-sm text-primary-600 border border-primary-600 rounded-md hover:bg-primary-50 transition-colors"
        >
          Voir les détails
        </button>
      </div>
    </div>
  </div>
</template>