<script setup lang="ts">
import { Address } from '@/libs/types/address';

const props = defineProps<{
  address: Address;
}>();

const emit = defineEmits(['edit', 'delete']);

const handleEdit = () => {
  emit('edit', props.address);
};

const handleDelete = () => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette adresse ?')) {
    emit('delete', props.address.id);
  }
};
</script>

<template>
  <div 
    class="bg-white p-5 rounded-lg shadow-md border border-gray-200 hover:shadow-xl hover:scale-105 transition-transform duration-300"
  >
    <div class="flex justify-between items-start mb-2">
      <div>
        <p class="font-medium bg-gradient-to-r from-indigo-600 to-blue-500 text-transparent bg-clip-text">
          {{ address.number }} {{ address.street }}
        </p>
        <p v-if="address.complement" class="text-gray-600 text-sm">{{ address.complement }}</p>
        <p class="text-gray-600">{{ address.zip_code }} {{ address.city }}</p>
        <p class="text-gray-600">{{ address.country_code }}</p>
      </div>
      <div class="flex space-x-2">
        <button
          @click="handleEdit"
          class="p-2 rounded-md transition-all duration-300 text-gray-500 hover:text-indigo-600 hover:bg-indigo-100 focus:ring-2 focus:ring-indigo-500"
          title="Modifier"
        >
          ✏️
        </button>
        <button
          @click="handleDelete"
          class="p-2 rounded-md transition-all duration-300 text-gray-500 hover:text-red-600 hover:bg-red-100 focus:ring-2 focus:ring-red-500"
          title="Supprimer"
        >
          🗑️
        </button>
      </div>
    </div>
  </div>
</template>
