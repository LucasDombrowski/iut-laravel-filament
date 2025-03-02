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
  emit('save', props.editingAddress ? { ...props.editingAddress, ...formData.value } : formData.value);
};
</script>

<template>
  <div class="fixed top-0 left-0 w-full h-full inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md opacity-0 animate-fadeIn hover:shadow-2xl hover:scale-105 transition-all duration-300">
      <div class="p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-bold text-gray-800">
            {{ editingAddress ? 'Modifier l\'adresse' : 'Ajouter une adresse' }}
          </h3>
          <button @click="emit('cancel')" class="text-gray-500 hover:text-gray-700 transition-all">
            ✖
          </button>
        </div>

        <form @submit.prevent="handleSubmit">
          <!-- Recherche d'adresse -->
          <div class="mb-4 relative">
            <label class="block text-gray-700 text-sm font-medium mb-1">Rechercher une adresse</label>
            <input
              v-model="searchQuery"
              @input="handleSearch"
              @focus="showResults = searchResults.length > 0"
              type="text"
              placeholder="Commencez à taper votre adresse..."
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500"
            />
            <div v-if="isSearching" class="absolute right-3 top-9 animate-spin text-indigo-500">⏳</div>
            <div v-if="showResults && searchResults.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
              <div
                v-for="(result, index) in searchResults"
                :key="index"
                @click="selectAddress(result)"
                class="px-4 py-2 hover:bg-indigo-100 cursor-pointer transition-all"
              >
                {{ result.label }}
              </div>
            </div>
          </div>

          <!-- Formulaire d'adresse -->
          <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-gray-700 text-sm font-medium mb-1">Numéro</label>
              <input v-model="formData.number" type="text" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500" />
            </div>
            <div>
              <label class="block text-gray-700 text-sm font-medium mb-1">Code postal</label>
              <input v-model="formData.zip_code" type="text" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500" />
            </div>
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-1">Rue</label>
            <input v-model="formData.street" type="text" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500" />
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-1">Complément d'adresse</label>
            <input v-model="formData.complement" type="text" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500" />
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-1">Ville</label>
            <input v-model="formData.city" type="text" class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-indigo-500" />
          </div>

          <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-1">Pays</label>
            <CustomSelect v-model="formData.country_code" :options="countries.map(country => ({ value: country.code, label: country.name }))" />
          </div>

          <div class="flex justify-end space-x-3">
            <button type="button" @click="emit('cancel')" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 transition-all">
              Annuler
            </button>
            <button 
              type="submit" 
              :disabled="!isFormValid" 
              class="px-4 py-2 text-white bg-gradient-to-r from-indigo-600 to-blue-500 rounded-md hover:scale-105 hover:shadow-lg disabled:bg-gray-300 disabled:cursor-not-allowed transition-all duration-300"
            >
              {{ editingAddress ? 'Modifier' : 'Ajouter' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
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
