<script setup lang="ts">
import { ref, watch } from 'vue';
import { ProductCategory, ProductFilters, Product, ProductColor, ProductSize } from '@/libs/types/product';
import CategoryHeader from '@/Components/Pages/Category/CategoryHeader.vue';
import ProductFiltersComponent from '@/Components/Pages/Category/ProductFilters.vue';
import ProductGrid from '@/Components/Pages/Category/ProductGrid.vue';
import Pagination from '@/Components/Pages/Category/Pagination.vue';
import { PaginationMeta } from '@/libs/types/category';
import { Head, router } from "@inertiajs/vue3";

const props = defineProps<{
    category: ProductCategory,
    products: Product[],
    colors: ProductColor[],
    sizes: ProductSize[],
    baseSearch?: string,
    meta: PaginationMeta,
    selectedColors: number[],
    selectedSizes: number[]
}>();

const { category, products, meta, selectedColors, selectedSizes, baseSearch } = props;

const filters = ref<ProductFilters>({
  colors: selectedColors,
  sizes: selectedSizes,
  search: baseSearch ?? '',
  page: meta.currentPage
});

const isLoading = ref(false);

function handlePageChange(page: number) {
  filters.value.page = page;
}

watch(filters, (newFilters) => {
    let url = route('categories.show', { slug: category.slug });
    const urlQueryParts = [];
    if (newFilters.colors.length) {
        urlQueryParts.push(`colors[]=${newFilters.colors.join('&colors[]=')}`);
    }
    if (newFilters.sizes.length) {
        urlQueryParts.push(`sizes[]=${newFilters.sizes.join('&sizes[]=')}`);
    }
    if (newFilters.search) {
        urlQueryParts.push(`search=${newFilters.search}`);
    }
    if (newFilters.page) {
        urlQueryParts.push(`page=${newFilters.page}`);
    }
    if (urlQueryParts.length) {
        url += `?${urlQueryParts.join('&')}`;
    }
    router.get(url);
}, { deep: true });
</script>

<template>
  <Head :title="category.name" />
  <div class="min-h-screen bg-gray-50 py-12 lg:py-16 px-4 sm:px-6 lg:px-8 animate-opacity-100">
    <div class="max-w-7xl mx-auto">
      <!-- Loading state -->
      <div v-if="isLoading" class="flex flex-col items-center justify-center py-20">
        <div class="w-16 h-16 border-4 border-gray-300 border-t-indigo-600 rounded-full animate-spin mb-4"></div>
        <p class="text-gray-700">Chargement en cours...</p>
      </div>
      
      <!-- Category not found -->
      <div v-else-if="!category" class="text-center py-20">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Catégorie non trouvée</h1>
        <p class="text-gray-600 mb-8">La catégorie que vous recherchez n'existe pas.</p>
        <a href="/" class="inline-block px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-500 text-white rounded-lg hover:from-indigo-700 hover:to-blue-600 transition-all duration-300">
          Retour à l'accueil
        </a>
      </div>
      
      <!-- Category content -->
      <template v-else>
        <CategoryHeader :category="category" />
        
        <ProductFiltersComponent 
          :available-colors="colors"
          :available-sizes="sizes"
          :filters="filters"
          @update:filters="newFilters => filters = newFilters"
        />
        
        <!-- Results summary -->
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-medium text-gray-800">
            {{ meta.total }} produits trouvés
          </h2>
          <div class="text-sm text-gray-500">
            Page {{ meta.currentPage }} sur {{ meta.lastPage }}
          </div>
        </div>

        <div class="border-t border-gray-200 mt-6"></div>

        <ProductGrid :products="products" class="mt-6 p-2 transition-transform duration-300" />
        
        <Pagination 
          :meta="meta"
          @page-change="handlePageChange"
        />
      </template>
    </div>
  </div>
</template>
