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
  <div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Récapitulatif de commande</h2>

    <div v-if="cart.items.length > 0" class="space-y-4 mb-6">
      <div v-for="item in cart.items" :key="item.variant.id" class="flex items-center space-x-4">
        <img :src="item.variant.thumbnail" :alt="item.variant.product.name" class="w-16 h-16 object-cover rounded-md">
        <div class="flex-1">
          <h3 class="font-medium text-gray-800">{{ item.variant.product.name }}</h3>
          <div class="text-sm text-gray-600">
            <span class="inline-block w-3 h-3 rounded-full mr-1" :style="{ backgroundColor: item.variant.color.hex }"></span>
            {{ item.variant.color.label }}, {{ item.variant.size.label }}
          </div>
          <div class="flex justify-between mt-1">
            <span class="text-gray-700">{{ item.quantity }} × {{ formatPrice(item.variant.price) }}</span>
            <span class="font-medium">{{ formatPrice(item.variant.price * item.quantity) }}</span>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-6">
      <p class="text-gray-500">Votre panier est vide.</p>
    </div>

    <div class="border-t border-gray-200 pt-4 space-y-2">
      <div class="flex justify-between">
        <span class="text-gray-600">Sous-total</span>
        <span class="font-medium">{{ formatPrice(subtotal) }}</span>
      </div>

      <transition name="fade">
        <div v-if="cart.discount" class="flex justify-between cursor-pointer" @click="showDiscountInfo = !showDiscountInfo">
          <span class="text-gray-600 flex items-center">
            Promotion
            <span v-if="!isValidDiscount" class="ml-2 text-xs text-red-500 bg-red-50 px-2 py-0.5 rounded">
              Non valide
            </span>
            <span v-else class="ml-2 text-xs text-green-500 bg-green-50 px-2 py-0.5 rounded">
              {{ cart.discount.code }}
            </span>
          </span>
          <span v-if="isValidDiscount" class="font-medium text-green-600">-{{ formatPrice(discountAmount) }}</span>
          <span v-else class="font-medium text-gray-400">-{{ formatPrice(0) }}</span>
        </div>
      </transition>

      <transition name="slide">
        <div v-if="showDiscountInfo" class="text-sm text-gray-500 bg-gray-50 p-2 rounded-md">
          <p v-if="cart.discount.type === 'percentage'">
            Remise de <strong>{{ cart.discount.value }}%</strong> sur votre commande.
          </p>
          <p v-else>
            Réduction de <strong>{{ formatPrice(cart.discount.value) }}</strong>.
          </p>
        </div>
      </transition>

      <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
        <span class="text-lg font-semibold text-gray-800">Total</span>
        <span class="text-lg font-semibold text-gray-800">{{ formatPrice(total) }}</span>
      </div>
    </div>
  </div>
</template>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
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
