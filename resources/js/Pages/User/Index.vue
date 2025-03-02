<script setup lang="ts">
import { ref } from 'vue';
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
import { router } from '@inertiajs/vue3';
import OrderDetails from '@/Components/Pages/User/OrderDetails.vue';
import { getStatusColor } from '@/libs/utils/order';
import { add } from 'date-fns';

const props = defineProps<{
  addresses: Address[];
  orders: Order[];
  countries: { code: string; name: string }[];
  startSlide?: number;
}>();

const emit = defineEmits<{
  (e: 'add-address'): void;
  (e: 'edit-address', address: Address): void;
  (e: 'delete-address', addressId: number): void;
}>();

const activeTab = ref(0);
const swiperInstance = ref(null);
const showAddressForm = ref(false);
const editingAddress = ref<Address | null>(null);
const currentOrder = ref<Order | null>(null);

const handleTabClick = (index: number) => {
  activeTab.value = index;
  swiperInstance.value?.slideTo(index);
};

const handleSlideChange = (swiper: any) => {
  activeTab.value = swiper.activeIndex;
};

const handleAddressSave = (data: Address) => {
  showAddressForm.value = false;
  if (!data.id) {
    router.post(route('addresses.add'), { ...data });
  } else {
    router.put(route('addresses.update', { address: data.id }), { ...data });
  }
};

const handleDeleteAddress = (addressId: number) => {
  router.delete(route('addresses.delete', { address: addressId }));
};

const handleTriggerAddAddressForm = () => {
  showAddressForm.value = true;
  editingAddress.value = null;
};

const handleTriggerEditAddressForm = (address: Address) => {
  showAddressForm.value = true;
  editingAddress.value = address;
};

const closeOrderDetails = () => {
  currentOrder.value = null;
};
</script>

<template>
  <div class="max-w-6xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-indigo-800 bg-clip-text mb-6 border-b-4 border-indigo-500 pb-2 w-fit">
      Mon compte
    </h1>

    <!-- Tabs -->
    <div class="mb-6 border-b border-gray-200">
      <div class="flex space-x-6 py-5">
        <button
          @click="handleTabClick(0)"
          class="py-2 px-4 font-medium text-sm transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg"
          :class="activeTab === 0 ? 'bg-gradient-to-r from-indigo-600 to-blue-500 text-white shadow-md' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300'"
        >
          Mes adresses
        </button>
        <button
          @click="handleTabClick(1)"
          class="py-2 px-4 font-medium text-sm transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg"
          :class="activeTab === 1 ? 'bg-gradient-to-r from-indigo-600 to-blue-500 text-white shadow-md' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300'"
        >
          Mes commandes
        </button>
      </div>
    </div>

    <!-- Swiper -->
    <Swiper
      :modules="[Navigation, Pagination]"
      :initial-slide="props.startSlide || 0"
      :slides-per-view="1"
      :space-between="50"
      @swiper="swiperInstance = $event"
      @slide-change="handleSlideChange"
      class="w-full"
    >
      <SwiperSlide class="bg-white p-6 rounded-lg transition-all duration-300">
        <AddressList
          :addresses="addresses"
          :countries="countries"
          @delete-address="handleDeleteAddress"
          @trigger-add-address-form="handleTriggerAddAddressForm"
          @trigger-edit-address-form="handleTriggerEditAddressForm"
        />
      </SwiperSlide>
      <SwiperSlide class="bg-white p-6 rounded-lg transition-all duration-300">
        <OrderList :orders="orders" @view-details="(order) => (currentOrder = order)" />
      </SwiperSlide>
    </Swiper>
  </div>

  <!-- Address Form -->
  <AddressForm 
    v-if="showAddressForm" 
    :countries="countries" 
    :editing-address="editingAddress"
    @save="handleAddressSave" 
    @cancel="()=>{
      showAddressForm = false;
      editingAddress = null;
    }" 
  />

  <!-- Order Details -->
  <OrderDetails
    v-else-if="currentOrder"
    :order="currentOrder"
    :status-color="getStatusColor(currentOrder.status)"
    @close="closeOrderDetails"
  />
</template>
