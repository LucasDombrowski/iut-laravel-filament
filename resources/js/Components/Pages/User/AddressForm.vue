<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { Address } from '@/libs/types/address';
import { searchAddress } from '@/libs/api/address';
import CustomSelect from './CustomSelect.vue';

const props = defineProps<{
  countries: { code: string; name: string }[];
  editingAddress: Address | null;
}>();

const emit = defineEmits(['save', 'cancel']);

const formData = ref({
  number: '',
  street: '',
  city: '',
  complement: '',
  zip_code: '',
  country_code: 'FR',
});

const searchQuery = ref('');
const searchResults = ref<any[]>([]);
const isSearching = ref(false);
const showResults = ref(false);

watch(() => props.editingAddress, (newValue) => {
  if (newValue) {
    formData.value = {
      number: newValue.number,
      street: newValue.street,
      city: newValue.city,
      complement: newValue.complement || '',
      zip_code: newValue.zip_code,
      country_code: newValue.country_code,
    };
  } else {
    formData.value = {
      number: '',
      street: '',
      city: '',
      complement: '',
      zip_code: '',
      country_code: 'FR',
    };
  }
}, { immediate: true });

const isFormValid = computed(() => {
  return (
    formData.value.number.trim() !== '' &&
    formData.value.street.trim() !== '' &&
    formData.value.city.trim() !== '' &&
    formData.value.zip_code.trim() !== '' &&
    formData.value.country_code.trim() !== ''
  );
});

const handleSearch = async () => {
  if (searchQuery.value.length < 3) {
    searchResults.value = [];
    showResults.value = false;
    return;
  }

  isSearching.value = true;
  try {
    searchResults.value = await searchAddress(searchQuery.value);
    showResults.value = true;
  } catch (error) {
    console.error('Error searching address:', error);
  } finally {
    isSearching.value = false;
  }
};

const selectAddress = (result: any) => {
  formData.value.number = result.number;
  formData.value.street = result.street;
  formData.value.city = result.city;
  formData.value.zip_code = result.zipCode;
  searchQuery.value = result.label;
  showResults.value = false;
};

const handleSubmit = () => {
  if (!isFormValid.value) return;

  if (props.editingAddress) {
    emit('save', {
      ...props.editingAddress,
      ...formData.value,
    });
  } else {
    emit('save', formData.value);
  }
};
</script>

<template>
  <div class="fixed top-0 left-0 w-full h-full inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
      <div class="p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-bold text-gray-800">
            {{ editingAddress ? 'Modifier l\'adresse' : 'Ajouter une adresse' }}
          </h3>
          <button @click="emit('cancel')" class="text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form @submit.prevent="handleSubmit">
          <div class="mb-4 relative">
            <label class="block text-gray-700 text-sm font-medium mb-1">Rechercher une adresse</label>
            <input
              v-model="searchQuery"
              @input="handleSearch"
              @focus="showResults = searchResults.length > 0"
              type="text"
              placeholder="Commencez à taper votre adresse..."
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
            <div v-if="isSearching" class="absolute right-3 top-9">
              <svg class="animate-spin h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </div>
            <div v-if="showResults && searchResults.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
              <div
                v-for="(result, index) in searchResults"
                :key="index"
                @click="selectAddress(result)"
                class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
              >
                {{ result.label }}
              </div>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-gray-700 text-sm font-medium mb-1">Numéro</label>
              <input
                v-model="formData.number"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
              />
            </div>
            <div>
              <label class="block text-gray-700 text-sm font-medium mb-1">Code postal</label>
              <input
                v-model="formData.zip_code"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
              />
            </div>
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-1">Rue</label>
            <input
              v-model="formData.street"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-1">Complément d'adresse</label>
            <input
              v-model="formData.complement"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-1">Ville</label>
            <input
              v-model="formData.city"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>

          <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-1">Pays</label>
            <CustomSelect
              v-model="formData.country_code"
              :options="countries.map(country => ({ value: country.code, label: country.name }))"
            />
          </div>

          <div class="flex justify-end space-x-3">
            <button
              type="button"
              @click="emit('cancel')"
              class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
            >
              Annuler
            </button>
            <button
              type="submit"
              :disabled="!isFormValid"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed"
            >
              {{ editingAddress ? 'Modifier' : 'Ajouter' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>