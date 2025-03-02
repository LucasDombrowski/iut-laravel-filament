<script setup lang="ts">
import { ref, watch } from 'vue';
import { Address } from '@/libs/types/address';
import AddressCard from './AddressCard.vue';

const props = defineProps<{
    addresses: Address[];
    countries: { code: string; name: string }[];
}>();

const emit = defineEmits(['delete-address', 'trigger-add-address-form', 'trigger-edit-address-form']);

const editingAddress = ref<Address | null>(null);

const handleEditAddress = (address: Address) => {
    editingAddress.value = address;
};

const handleDeleteAddress = (addressId: number) => {
    emit('delete-address', addressId);
};

const handleAddAddressClick = () => {
    emit('trigger-add-address-form');
};

watch(editingAddress, (address) => {
    if (address) {
        emit('trigger-edit-address-form', address);
    }
});
</script>

<template>
    <div class="w-full opacity-0 animate-fadeIn">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-blue-500 text-transparent bg-clip-text">
                Mes adresses
            </h2>
            <button
                @click="handleAddAddressClick"
                class="px-4 py-2 rounded-lg text-white font-medium shadow-md transition-all duration-300 
                bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 focus:ring-2 focus:ring-indigo-500"
            >
                Ajouter une adresse
            </button>
        </div>

        <div v-if="addresses.length === 0" class="text-center py-10 animate-pulse">
            <p class="text-gray-500">Vous n'avez pas encore d'adresse enregistrée.</p>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <AddressCard 
                v-for="address in addresses" 
                :key="address.id" 
                :address="address" 
                @edit="handleEditAddress" 
                @delete="handleDeleteAddress" 
                class="shadow-md hover:shadow-lg transition-all duration-300 rounded-lg"
            />
        </div>
    </div>
</template>

<style scoped>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.6s ease-out forwards;
}
</style>
