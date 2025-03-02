<script setup lang="ts">
import { ref, computed } from 'vue';
import { Product } from '@/libs/types/product';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
  product: Product;
}>();

const selectedColorId = ref(props.product.variants[0]?.color_id);

const availableColors = computed(() => {
  const uniqueColors = new Map();
  props.product.variants.forEach(variant => {
    if (!uniqueColors.has(variant.color_id)) {
      uniqueColors.set(variant.color_id, variant.color);
    }
  });
  return Array.from(uniqueColors.values());
});

const selectedVariant = computed(() => {
  return props.product.variants.find(v => v.color_id === selectedColorId.value);
});

const otherCategories = computed(() => {
  return props.product.categories.filter(category => 
    !window.location.href.includes(category.slug)
  );
});
</script>

<template>
  <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
    <div class="relative overflow-hidden group">
      <a :href="`/products/${product.slug}`">
        <img 
          :src="selectedVariant?.thumbnail" 
          :alt="product.name"
          class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105"
        />
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-indigo-600 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
      </a>
      
      <!-- Color options -->
      <div class="absolute top-3 right-3 flex gap-1">
        <button
          v-for="color in availableColors"
          :key="color.id"
          @click="selectedColorId = color.id"
          class="w-6 h-6 rounded-full border-2 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
          :class="selectedColorId === color.id ? 'border-indigo-600 scale-110' : 'border-transparent hover:scale-105'"
          :style="{ backgroundColor: color.hex }"
          :title="color.label"
        ></button>
      </div>
    </div>
    
    <div class="p-4">
      <div class="flex justify-between items-start mb-2 gap-2">
        <h3 class="font-medium text-gray-800 hover:text-indigo-600 transition-colors">
          <a :href="`/products/${product.slug}`">{{ product.name }}</a>
        </h3>
        <span class="font-bold text-indigo-600 whitespace-nowrap">{{ selectedVariant?.price.toFixed(2) }} €</span>
      </div>
      
      <!-- Other categories -->
      <div v-if="otherCategories.length > 0" class="mb-2 flex flex-wrap gap-2">
        <Link
          v-for="category in otherCategories" 
          :key="category.id"
          :href="route('categories.show', { slug: category.slug })"
          class="text-xs px-2 py-1 bg-gray-100 rounded-full hover:bg-indigo-100 transition-all duration-300"
        >
          {{ category.name }}
        </Link>
      </div>
      
      <!-- Available sizes -->
      <div class="mt-3">
        <div class="text-xs text-gray-700 mb-1">Tailles disponibles :</div>
        <div class="flex flex-wrap gap-1">
          <span 
            v-for="size in Array.from(
                new Set(product.variants
              .filter(v => v.color_id === selectedColorId)
              .map(v => v.size.label))
            )" 
            :key="size"
            class="text-xs px-2 py-1 bg-gray-100 rounded-md hover:bg-gray-200 transition-all duration-300"
          >
            {{ size }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
