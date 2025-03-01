<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { gsap } from 'gsap';
import CartItem from '@/Components/Pages/Cart/CartItem.vue';
import PromoCodeInput from '@/Components/Pages/Cart/PromoCodeInput.vue';
import CartSummary from '@/Components/Pages/Cart/CartSummary.vue';
import EmptyCart from '@/Components/Pages/Cart/EmptyCart.vue';
import type { CartItem as CartItemType } from '@/libs/types/cart';
import {router} from "@inertiajs/vue3";
import { Discount } from '@/libs/types/order';

const props = defineProps<{
  cartItems: CartItemType[];
  cartDiscount: Discount | null;
}>();


const cartItems = ref<CartItemType[]>(props.cartItems);

const appliedPromoCode = ref<Discount | null>(props.cartDiscount);
const pageTitle = ref<HTMLElement | null>(null);

const updateItemQuantity = (itemId: number, newQuantity: number) => {
  const itemIndex = cartItems.value.findIndex(item => item.variant.id === itemId);
  if (itemIndex !== -1) {
    cartItems.value[itemIndex].quantity = newQuantity;
  }
};

const removeItem = (itemId: number) => {
  router.delete(route('cart.remove', { variant: itemId }));
  cartItems.value = cartItems.value.filter(item => item.variant.id !== itemId);
};

const applyPromoCode = (code: string) => {
  const items = convertCartItems(cartItems.value);
  router.put(route('cart.update'), {
    code,
    items
  });
};

const convertCartItems = (items: CartItemType[]) => {
  return items.map(item => {
    return {
      id: item.variant.id,
      quantity: item.quantity
    };
  });
};

onMounted(() => {
  if (pageTitle.value) {
    gsap.from(pageTitle.value, {
      opacity: 0,
      y: -20,
      duration: 0.7,
      ease: 'power2.out'
    });
  }
});

watch(cartItems,(newValue)=>{
  const putValues = convertCartItems(newValue);

  router.put(route('cart.update'),{
    items: putValues
  });
},{
  deep: true
});

watch(props.cartDiscount, (newDiscount) => {
  appliedPromoCode.value = newDiscount;
},{
  immediate: true,
  deep: true
});

</script>

<template>
  <div class="container mx-auto px-4 py-12">
    <h1 ref="pageTitle" class="text-3xl font-bold mb-10 text-center text-indigo-800">
      <span class="inline-block border-b-4 border-indigo-500 pb-2">Votre Panier</span>
    </h1>
    
    <div v-if="cartItems.length === 0">
      <EmptyCart />
    </div>
    
    <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
          <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-indigo-600 to-blue-500">
            <h2 class="text-xl font-semibold text-white">Articles ({{ cartItems.length }})</h2>
          </div>
          
          <div class="divide-y divide-gray-100">
            <CartItem
              v-for="item in cartItems"
              :key="item.variant.id"
              :item="item"
              @update:quantity="(qty) => updateItemQuantity(item.variant.id, qty)"
              @remove="removeItem(item.variant.id)"
            />
          </div>
          
          <PromoCodeInput @apply-promo="applyPromoCode" :base-code="appliedPromoCode ? appliedPromoCode.code : ''" />
        </div>
      </div>
      
      <div>
        <CartSummary :items="cartItems" :cart-discount="appliedPromoCode" />
      </div>
    </div>
  </div>
</template>