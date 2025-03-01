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
  <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow">
    <div class="flex justify-between items-start mb-2">
      <div>
        <p class="font-medium text-gray-800">{{ address.number }} {{ address.street }}</p>
        <p v-if="address.complement" class="text-gray-600 text-sm">{{ address.complement }}</p>
        <p class="text-gray-600">{{ address.zip_code }} {{ address.city }}</p>
        <p class="text-gray-600">{{ address.country_code }}</p>
      </div>
      <div class="flex space-x-2">
        <button
          @click="handleEdit"
          class="p-1 text-gray-500 hover:text-primary-600 transition-colors"
          title="Modifier"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
          </svg>
        </button>
        <button
          @click="handleDelete"
          class="p-1 text-gray-500 hover:text-red-600 transition-colors"
          title="Supprimer"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>