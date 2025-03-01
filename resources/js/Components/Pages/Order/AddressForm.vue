<script setup lang="ts">
import { ref, computed } from 'vue';
import { Address } from '@/libs/types/address';
import { searchAddress } from '@/libs/api/address';

const props = defineProps<{
  savedAddresses: Address[];
  countries: {
    [key: string]: string;
  }
}>();

const emit = defineEmits<{
  (e: 'update:address', address: Address): void;
  (e: 'save-address', value: boolean): void;
}>();

const selectedSavedAddressId = ref<number | null>(null);
const saveNewAddress = ref(false);


const newAddress = ref<Omit<Address, 'id' | 'user_id'>>({
  number: '',
  street: '',
  city: '',
  complement: '',
  zip_code: '',
  country_code: 'FR',
});

const isNewAddressMode = computed(() => {
  return selectedSavedAddressId.value === null;
});

const selectedSavedAddress = computed(() => {
  if (selectedSavedAddressId.value === null) return null;
  return props.savedAddresses.find(addr => addr.id === selectedSavedAddressId.value);
});

const selectSavedAddress = (addressId: number) => {
  selectedSavedAddressId.value = addressId;
  const address = props.savedAddresses.find(addr => addr.id === addressId);
  if (address) {
    emit('update:address', address);
    emit('save-address', false);
  }
};

const submitNewAddress = () => {
  // Create a temporary ID for the new address
  const tempAddress = {
    ...newAddress.value,
    id: -1, // Temporary ID
    user_id: 1, // Assuming user ID 1 for now
  };
  
  emit('update:address', tempAddress);
  
  emit('save-address', saveNewAddress.value);
};

const searchQuery = ref('');
const searchResults = ref<any[]>([]);
const isSearching = ref(false);
const showResults = ref(false);

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
  newAddress.value.number = result.number;
  newAddress.value.street = result.street;
  newAddress.value.city = result.city;
  newAddress.value.zip_code = result.zipCode;
  searchQuery.value = result.label;
  showResults.value = false;
};
</script>

<template>
  <div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Adresse de livraison</h2>
    
    <div v-if="savedAddresses.length > 0" class="mb-6">
      <div class="flex space-x-4 mb-4">
        <button 
          v-for="address in savedAddresses" 
          :key="address.id"
          @click="selectSavedAddress(address.id)"
          :class="[
            'px-4 py-2 rounded-md border transition-colors',
            selectedSavedAddressId === address.id 
              ? 'border-indigo-500 bg-indigo-50 text-indigo-700' 
              : 'border-gray-300 hover:border-gray-400'
          ]"
        >
          {{ address.number }} {{ address.street }}, {{ address.city }}
        </button>
      </div>
      
      <div v-if="selectedSavedAddress" class="bg-gray-50 p-4 rounded-md">
        <p class="font-medium">{{ selectedSavedAddress.number }} {{ selectedSavedAddress.street }}</p>
        <p v-if="selectedSavedAddress.complement" class="text-gray-600">{{ selectedSavedAddress.complement }}</p>
        <p>{{ selectedSavedAddress.zip_code }} {{ selectedSavedAddress.city }}</p>
        <p>{{ Object.entries(countries).find(([key,value]) => key === selectedSavedAddress.country_code)[1] }}</p>
      </div>
    </div>
    
    <form v-if="isNewAddressMode" @submit.prevent="submitNewAddress" class="space-y-4">
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
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="number" class="block text-sm font-medium text-gray-700 mb-1">Numéro</label>
          <input 
            type="text" 
            id="number" 
            v-model="newAddress.number" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            required
          >
        </div>
        
        <div>
          <label for="street" class="block text-sm font-medium text-gray-700 mb-1">Rue</label>
          <input 
            type="text" 
            id="street" 
            v-model="newAddress.street" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            required
          >
        </div>
        
        <div>
          <label for="complement" class="block text-sm font-medium text-gray-700 mb-1">Complément (optionnel)</label>
          <input 
            type="text" 
            id="complement" 
            v-model="newAddress.complement" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
          >
        </div>
        
        <div>
          <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
          <input 
            type="text" 
            id="city" 
            v-model="newAddress.city" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            required
          >
        </div>
        
        <div>
          <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-1">Code postal</label>
          <input 
            type="text" 
            id="zip_code" 
            v-model="newAddress.zip_code" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            required
          >
        </div>
        
        <div>
          <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
          <select 
            id="country" 
            v-model="newAddress.country_code" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            required
          >
            <option v-for="[key,value] in Object.entries(countries)" :key="key" :value="key">
              {{ value }}
            </option>
          </select>
        </div>
      </div>
      
      <div class="flex items-center mt-4">
        <input 
          type="checkbox" 
          id="save_address" 
          v-model="saveNewAddress" 
          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
        >
        <label for="save_address" class="ml-2 block text-sm text-gray-700">
          Enregistrer cette adresse pour mes prochaines commandes
        </label>
      </div>
      
      <div class="mt-4">
        <button 
          type="submit" 
          class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
          Utiliser cette adresse
        </button>
      </div>
    </form>
  </div>
</template>