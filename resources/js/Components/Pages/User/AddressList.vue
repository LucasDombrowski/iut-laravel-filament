<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { Address } from '@/libs/types/address';
import AddressCard from './AddressCard.vue';
import AddressForm from './AddressForm.vue';

const props = defineProps<{
    addresses: Address[];
    countries: { code: string; name: string }[];
}>();

const emit = defineEmits(['delete-address', 'trigger-add-address-form','trigger-edit-address-form']);

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
    <div class="w-full">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Mes adresses</h2>
            <button @click="handleAddAddressClick"
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                Ajouter une adresse
            </button>
        </div>

        <div v-if="addresses.length === 0" class="text-center py-10">
            <p class="text-gray-500">Vous n'avez pas encore d'adresse enregistrée.</p>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <AddressCard v-for="address in addresses" :key="address.id" :address="address" @edit="handleEditAddress"
                @delete="handleDeleteAddress" />
        </div>
    </div>
</template>