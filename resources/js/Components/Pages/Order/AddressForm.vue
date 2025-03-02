<script setup lang="ts">
import { ref, computed } from 'vue';
import { Address } from '@/libs/types/address';
import { searchAddress } from '@/libs/api/address';

const props = defineProps<{
  savedAddresses: Address[];
  countries: { [key: string]: string };
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

const isNewAddressMode = computed(() => selectedSavedAddressId.value === null);

const selectedSavedAddress = computed(() => {
  return props.savedAddresses.find(addr => addr.id === selectedSavedAddressId.value) || null;
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
  const tempAddress = {
    ...newAddress.value,
    id: -1,
    user_id: 1, // ID temporaire
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
  <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 transition-all duration-300 hover:shadow-xl w-full max-w-4xl mx-auto">
    <h2 class="text-xl font-semibold text-gray-800 bg-gradient-to-r from-indigo-600 to-blue-500 text-transparent bg-clip-text mb-4 text-center sm:text-left">
      Adresse de livraison
    </h2>

    <div v-if="savedAddresses.length > 0" class="mb-6">
      <div class="flex flex-wrap gap-2 mb-4 justify-center sm:justify-start">
        <button
          v-for="address in savedAddresses"
          :key="address.id"
          @click="selectSavedAddress(address.id)"
          class="px-4 py-2 rounded-md border transition-all duration-300 text-sm sm:text-base hover:shadow-md"
          :class="[
            selectedSavedAddressId === address.id
              ? 'ring-2 ring-indigo-500 border-indigo-500 bg-indigo-50 text-indigo-700'
              : 'border-gray-300 hover:border-indigo-400 hover:ring-1 hover:ring-indigo-300'
          ]"
        >
          {{ address.number }} {{ address.street }}, {{ address.city }}
        </button>
      </div>

      <div v-if="selectedSavedAddress" class="bg-gray-50 p-4 rounded-lg shadow-inner text-center sm:text-left">
        <p class="font-medium">{{ selectedSavedAddress.number }} {{ selectedSavedAddress.street }}</p>
        <p v-if="selectedSavedAddress.complement" class="text-gray-600">{{ selectedSavedAddress.complement }}</p>
        <p>{{ selectedSavedAddress.zip_code }} {{ selectedSavedAddress.city }}</p>
        <p>{{ countries[selectedSavedAddress.country_code] ?? 'Inconnu' }}</p>
      </div>
    </div>

    <transition name="fade">
      <form v-if="isNewAddressMode" @submit.prevent="submitNewAddress" class="space-y-4">
        <div class="relative">
          <label class="block text-gray-700 text-sm font-medium mb-1">Rechercher une adresse</label>
          <input
            v-model="searchQuery"
            @input="handleSearch"
            @focus="showResults = searchResults.length > 0"
            @keydown.enter.prevent="handleSearch"
            type="text"
            placeholder="Commencez à taper votre adresse..."
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 transition-all duration-200"
          />
          <div v-if="isSearching" class="absolute right-3 top-9">
            <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </div>
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

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <input v-model="newAddress.number" placeholder="Numéro" class="border p-2 rounded-md focus:ring-2 focus:ring-indigo-500 transition-all duration-200" required />
          <input v-model="newAddress.street" placeholder="Rue" class="border p-2 rounded-md focus:ring-2 focus:ring-indigo-500 transition-all duration-200" required />
          <input v-model="newAddress.city" placeholder="Ville" class="border p-2 rounded-md focus:ring-2 focus:ring-indigo-500 transition-all duration-200" required />
          <input v-model="newAddress.zip_code" placeholder="Code postal" class="border p-2 rounded-md focus:ring-2 focus:ring-indigo-500 transition-all duration-200" required />
        </div>

        <div class="mt-4 flex items-center">
          <input type="checkbox" id="save_address" v-model="saveNewAddress" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
          <label for="save_address" class="ml-2 text-sm text-gray-700">Enregistrer cette adresse</label>
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-2 px-4 rounded-md hover:shadow-md hover:from-indigo-700 hover:to-blue-600 transition-all duration-300">
          Utiliser cette adresse
        </button>
      </form>
    </transition>
  </div>
</template>
