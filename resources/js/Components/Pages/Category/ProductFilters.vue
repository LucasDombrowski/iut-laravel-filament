<script setup lang="ts">
import { ref, watch } from 'vue';
import { ProductColor, ProductSize, ProductFilters } from '@/libs/types/product';

const props = defineProps<{
  availableColors: ProductColor[];
  availableSizes: ProductSize[];
  filters: ProductFilters;
}>();

const emit = defineEmits<{
  (e: 'update:filters', filters: ProductFilters): void;
}>();

const searchInput = ref(props.filters.search);
const selectedColors = ref<number[]>([...props.filters.colors]);
const selectedSizes = ref<number[]>([...props.filters.sizes]);
const isFiltersOpen = ref(false);

const toggleColor = (colorId: number) => {
  const index = selectedColors.value.indexOf(colorId);
  index === -1 ? selectedColors.value.push(colorId) : selectedColors.value.splice(index, 1);
  updateFilters();
};

const toggleSize = (sizeId: number) => {
  const index = selectedSizes.value.indexOf(sizeId);
  index === -1 ? selectedSizes.value.push(sizeId) : selectedSizes.value.splice(index, 1);
  updateFilters();
};

const updateFilters = () => {
  emit('update:filters', {
    ...props.filters,
    colors: [...selectedColors.value],
    sizes: [...selectedSizes.value],
    search: searchInput.value,
    page: 1
  });
};

const resetFilters = () => {
  selectedColors.value = [];
  selectedSizes.value = [];
  searchInput.value = '';
  updateFilters();
};

const handleSearch = () => {
  updateFilters();
};

const toggleFilters = () => {
  isFiltersOpen.value = !isFiltersOpen.value;
};

watch(() => props.filters, (newFilters) => {
  selectedColors.value = [...newFilters.colors];
  selectedSizes.value = [...newFilters.sizes];
  searchInput.value = newFilters.search;
}, { deep: true });
</script>

<template>
  <div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <!-- Search bar -->
      <div class="relative flex-grow">
        <input
          v-model="searchInput"
          type="text"
          placeholder="Rechercher des produits..."
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          @keyup.enter="handleSearch"
        />
        <button 
          @click="handleSearch"
          class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-indigo-600"
        >
          🔍
        </button>
      </div>

      <!-- Filter toggle for mobile -->
      <button 
        @click="toggleFilters"
        class="md:hidden px-4 py-2 bg-gray-100 rounded-lg flex items-center gap-2 hover:bg-gray-200 transition-all duration-300"
      >
        ☰ Filtres
        <span v-if="selectedColors.length > 0 || selectedSizes.length > 0" 
          class="bg-indigo-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
          {{ selectedColors.length + selectedSizes.length }}
        </span>
      </button>

      <!-- Reset filters button -->
      <button 
        v-if="selectedColors.length > 0 || selectedSizes.length > 0 || searchInput"
        @click="resetFilters"
        class="px-4 py-2 text-indigo-600 hover:text-indigo-800 transition-all duration-300 flex items-center gap-1"
      >
        ❌ Réinitialiser les filtres
      </button>
    </div>

    <!-- Filters section -->
    <div 
      class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
      :class="{ 'hidden md:grid': !isFiltersOpen }"
    >
      <!-- Colors filter -->
      <div>
        <h3 class="font-medium text-gray-700 mb-2">Couleurs</h3>
        <div class="flex flex-wrap gap-2">
          <button
            v-for="color in availableColors"
            :key="color.id"
            @click="toggleColor(color.id)"
            class="w-8 h-8 rounded-full border-2 flex items-center justify-center transition-all duration-300"
            :style="{ backgroundColor: color.hex }"
            :class="selectedColors.includes(color.id) ? 'border-indigo-600 scale-110' : 'border-gray-300 hover:scale-105'"
            :title="color.label"
          >
            <svg 
              v-if="selectedColors.includes(color.id)" 
              xmlns="http://www.w3.org/2000/svg" 
              class="h-4 w-4 text-white"
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Sizes filter -->
      <div>
        <h3 class="font-medium text-gray-700 mb-2">Tailles</h3>
        <div class="flex flex-wrap gap-2">
          <button
            v-for="size in availableSizes"
            :key="size.id"
            @click="toggleSize(size.id)"
            class="w-10 h-10 rounded-md flex items-center justify-center transition-all duration-300"
            :class="selectedSizes.includes(size.id) 
              ? 'bg-indigo-500 text-white scale-105 shadow-md' 
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
          >
            {{ size.label }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
