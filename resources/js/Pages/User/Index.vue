<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

import { Address } from '@/libs/types/address';
import { Order } from '@/libs/types/order';
import AddressList from '@/Components/Pages/User/AddressList.vue';
import OrderList from '@/Components/Pages/User/OrderList.vue';
import AddressForm from '@/Components/Pages/User/AddressForm.vue';

const props = defineProps<{
  addresses: Address[];
  orders: Order[];
  countries: { code: string; name: string }[];
}>();

const emit = defineEmits(['add-address', 'edit-address', 'delete-address']);

const activeTab = ref(0);
const swiperInstance = ref(null);

const handleTabClick = (index: number) => {
  activeTab.value = index;
  swiperInstance.value?.slideTo(index);
};

const handleSlideChange = (swiper: any) => {
  activeTab.value = swiper.activeIndex;
};

const handleAddAddress = (address: Omit<Address, 'id' | 'user_id'>) => {
  emit('add-address', address);
};

const handleEditAddress = (address: Address) => {
  emit('edit-address', address);
};

const handleDeleteAddress = (addressId: number) => {
  emit('delete-address', addressId);
};

const showAddressForm = ref(false);
const editingAddress = ref<Address | null>(null);

const handleTriggerAddAddressForm = () => {
    showAddressForm.value = true;
    editingAddress.value = null;
};

onMounted(() => {
  // Any initialization if needed
});
</script>

<template>
  <div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Mon compte</h1>

    <div class="mb-6 border-b border-gray-200">
      <div class="flex space-x-8">
        <button
          @click="handleTabClick(0)"
          class="py-4 px-1 border-b-2 font-medium text-sm transition-colors"
          :class="activeTab === 0 ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
        >
          Mes adresses
        </button>
        <button
          @click="handleTabClick(1)"
          class="py-4 px-1 border-b-2 font-medium text-sm transition-colors"
          :class="activeTab === 1 ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
        >
          Mes commandes
        </button>
      </div>
    </div>

    <Swiper
      :modules="[Navigation, Pagination]"
      :slides-per-view="1"
      :space-between="50"
      @swiper="swiperInstance = $event"
      @slide-change="handleSlideChange"
      class="w-full !overflow-x-clip !overflow-y-visible"
    >
      <SwiperSlide>
        <AddressList
          :addresses="addresses"
          :countries="countries"
          @add-address="handleAddAddress"
          @edit-address="handleEditAddress"
          @delete-address="handleDeleteAddress"
          @trigger-add-address-form="handleTriggerAddAddressForm"
        />
      </SwiperSlide>
      <SwiperSlide>
        <OrderList :orders="orders" />
      </SwiperSlide>
    </Swiper>
  </div>
  <AddressForm v-if="showAddressForm" :countries="countries" :editing-address="editingAddress"
        @save="editingAddress ? handleEditAddress : handleAddAddress" @cancel="showAddressForm = false" />
</template>