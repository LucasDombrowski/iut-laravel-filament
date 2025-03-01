<script setup lang="ts">
import { computed } from 'vue';
import { PaginationMeta } from '@/libs/types/category';

const props = defineProps<{
  meta: PaginationMeta;
}>();

const emit = defineEmits<{
  (e: 'page-change', page: number): void;
}>();

const changePage = (page: number) => {
  if (page < 1 || page > props.meta.lastPage) return;
  emit('page-change', page);
};

const pageNumbers = computed(() => {
  const currentPage = props.meta.currentPage;
  const totalPages = props.meta.lastPage;
  
  if (totalPages <= 7) {
    return Array.from({ length: totalPages }, (_, i) => i + 1);
  }
  
  if (currentPage <= 3) {
    return [1, 2, 3, 4, 5, '...', totalPages];
  }
  
  if (currentPage >= totalPages - 2) {
    return [1, '...', totalPages - 4, totalPages - 3, totalPages - 2, totalPages - 1, totalPages];
  }
  
  return [
    1, 
    '...', 
    currentPage - 1, 
    currentPage, 
    currentPage + 1, 
    '...', 
    totalPages
  ];
});
</script>

<template>
  <div v-if="meta.lastPage > 1" class="flex justify-center mt-8">
    <nav class="flex items-center space-x-1">
      <!-- Bouton Précédent -->
      <button 
        @click="changePage(meta.currentPage - 1)"
        :disabled="meta.currentPage === 1"
        class="px-3 py-2 rounded-md transition-all duration-300 transform"
        :class="meta.currentPage === 1 
          ? 'text-gray-400 cursor-not-allowed' 
          : 'text-gray-700 hover:bg-indigo-100 focus:ring-2 focus:ring-indigo-500 hover:scale-105'"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      
      <!-- Numéros de page -->
      <template v-for="(page, index) in pageNumbers" :key="index">
        <span v-if="page === '...'" class="px-3 py-2 text-gray-500">...</span>
        <button 
          v-else
          @click="changePage(Number(page))"
          class="px-3 py-2 rounded-md transition-all duration-300"
          :class="meta.currentPage === page 
            ? 'bg-indigo-600 text-white font-bold shadow-md' 
            : 'text-gray-700 hover:bg-indigo-100 focus:ring-2 focus:ring-indigo-500 hover:scale-105'"
        >
          {{ page }}
        </button>
      </template>
      
      <!-- Bouton Suivant -->
      <button 
        @click="changePage(meta.currentPage + 1)"
        :disabled="meta.currentPage === meta.lastPage"
        class="px-3 py-2 rounded-md transition-all duration-300 transform"
        :class="meta.currentPage === meta.lastPage
          ? 'text-gray-400 cursor-not-allowed' 
          : 'text-gray-700 hover:bg-indigo-100 focus:ring-2 focus:ring-indigo-500 hover:scale-105'"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </nav>
  </div>
</template>
