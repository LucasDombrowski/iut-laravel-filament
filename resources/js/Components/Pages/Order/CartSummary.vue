<script setup lang="ts">
import { computed, ref } from 'vue';
import { Cart } from '@/libs/types/order';
import { isDiscountValid, calculateDiscountAmount } from '@/libs/utils/discount';

const props = defineProps<{ cart: Cart }>();

const subtotal = computed(() => {
  return props.cart.items.reduce((total, item) => total + item.variant.price * item.quantity, 0);
});

const discountAmount = computed(() => {
  if (!props.cart.discount) return 0;
  return calculateDiscountAmount(props.cart.discount, subtotal.value);
});

const isValidDiscount = computed(() => {
  if (!props.cart.discount) return false;
  return isDiscountValid(props.cart.discount, subtotal.value);
});

const total = computed(() => subtotal.value - discountAmount.value);

const formatPrice = (price: number): string => {
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(price);
};

const showDiscountInfo = ref(false);
</script>

<template>
  <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 transition-all duration-300 hover:shadow-xl">
    <h2 class="text-xl font-semibold text-gray-800 bg-gradient-to-r from-indigo-600 to-blue-500 text-transparent bg-clip-text mb-4">
      Récapitulatif de commande
    </h2>

    <div v-if="cart.items.length > 0" class="space-y-4 mb-6">
      <div v-for="item in cart.items" :key="item.variant.id" class="flex items-center space-x-4">
        <img :src="item.variant.thumbnail" :alt="item.variant.product.name" class="w-16 h-16 object-cover rounded-lg shadow-sm">
        <div class="flex-1">
          <h3 class="font-medium text-gray-800">{{ item.variant.product.name }}</h3>
          <div class="text-sm text-gray-600 flex items-center">
            <span class="inline-block w-3 h-3 rounded-full border border-gray-300 mr-2" :style="{ backgroundColor: item.variant.color.hex }"></span>
            {{ item.variant.color.label }}, {{ item.variant.size.label }}
          </div>
          <div class="flex justify-between mt-1">
            <span class="text-gray-700">{{ item.quantity }} × {{ formatPrice(item.variant.price) }}</span>
            <span class="font-medium text-gray-900">{{ formatPrice(item.variant.price * item.quantity) }}</span>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-6">
      <p class="text-gray-500">Votre panier est vide.</p>
    </div>

    <div class="border-t border-gray-300 pt-4 space-y-3">
      <div class="flex justify-between">
        <span class="text-gray-600">Sous-total</span>
        <span class="font-medium">{{ formatPrice(subtotal) }}</span>
      </div>

      <transition name="fade">
        <div v-if="cart.discount" class="flex justify-between cursor-pointer hover:text-indigo-600 transition-all duration-200" @click="showDiscountInfo = !showDiscountInfo">
          <span class="text-gray-600 flex items-center">
            Promotion
            <span v-if="!isValidDiscount" class="ml-2 text-xs text-red-600 bg-red-100 px-2 py-0.5 rounded">
              Non valide
            </span>
            <span v-else class="ml-2 text-xs text-green-600 bg-green-100 px-2 py-0.5 rounded">
              {{ cart.discount.code }}
            </span>
          </span>
          <span v-if="isValidDiscount" class="font-medium text-green-600">-{{ formatPrice(discountAmount) }}</span>
          <span v-else class="font-medium text-gray-400">-{{ formatPrice(0) }}</span>
        </div>
      </transition>

      <transition name="slide">
        <div v-if="showDiscountInfo" class="text-sm text-gray-600 bg-gray-100 p-3 rounded-md shadow-inner">
          <p v-if="cart.discount.type === 'percentage'">
            Remise de <strong>{{ cart.discount.value }}%</strong> sur votre commande.
          </p>
          <p v-else>
            Réduction de <strong>{{ formatPrice(cart.discount.value) }}</strong>.
          </p>
        </div>
      </transition>

      <div class="flex justify-between border-t border-gray-300 pt-3 mt-2">
        <span class="text-lg font-semibold text-gray-900">Total</span>
        <span class="text-lg font-semibold bg-gradient-to-r from-indigo-600 to-blue-500 text-transparent bg-clip-text">
          {{ formatPrice(total) }}
        </span>
      </div>
    </div>
  </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease-in-out;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}

.slide-enter-active, .slide-leave-active {
  transition: max-height 0.3s ease-in-out;
  overflow: hidden;
}
.slide-enter, .slide-leave-to {
  max-height: 0;
}
</style>
