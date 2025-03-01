<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { Product } from '@/libs/types/product';
import gsap from 'gsap';
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
  <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl">
    <div class="relative overflow-hidden group">
      <a :href="`/products/${product.slug}`">
        <img 
          :src="selectedVariant?.thumbnail" 
          :alt="product.name"
          class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105"
        />
      </a>
      
      <!-- Color options -->
      <div class="flex justify-end gap-1 z-10 mt-2 mr-3">
        <button
          v-for="color in availableColors"
          :key="color.id"
          @click="selectedColorId = color.id"
          class="w-6 h-6 rounded-full border-2 transition-transform"
          :class="selectedColorId === color.id ? 'border-white transform scale-125' : 'border-transparent'"
          :style="{ backgroundColor: color.hex }"
          :title="color.label"
        ></button>
      </div>
    </div>
    
    <div class="p-4">
      <div class="flex justify-between items-start mb-2">
        <h3 class="font-medium text-gray-800 hover:text-primary-600 transition-colors">
          <a :href="`/products/${product.slug}`">{{ product.name }}</a>
        </h3>
        <span class="font-bold text-primary-600">{{ selectedVariant?.price.toFixed(2) }} €</span>
      </div>
      
      <!-- Other categories -->
      <div v-if="otherCategories.length > 0" class="mb-2 flex flex-wrap gap-1">
        <Link
          v-for="category in otherCategories" 
          :key="category.id"
          :href="route('categories.show', { slug: category.slug })"
          class="text-xs px-2 py-1 bg-gray-100 rounded-full hover:bg-gray-200 transition-colors"
        >
          {{ category.name }}
      </Link>
      </div>
      
      <!-- Available sizes -->
      <div class="mt-3">
        <div class="text-xs text-gray-500 mb-1">Tailles disponibles:</div>
        <div class="flex flex-wrap gap-1">
          <span 
            v-for="size in Array.from(
                new Set(product.variants
              .filter(v => v.color_id === selectedColorId)
              .map(v => v.size.label))
            )" 
            :key="size"
            class="text-xs px-2 py-1 bg-gray-100 rounded-md"
          >
            {{ size }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>