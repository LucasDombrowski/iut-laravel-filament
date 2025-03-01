<script setup lang="ts">
import { ref, computed } from 'vue';
import ProductGallery from '@/Components/Pages/Product/ProductGallery.vue';
import ProductColorSelector from '@/Components/Pages/Product/ProductColorSelector.vue';
import ProductSizeSelector from '@/Components/Pages/Product/ProductSizeSelector.vue';
import ProductQuantity from '@/Components/Pages/Product/ProductQuantity.vue';
import ProductCategories from '@/Components/Pages/Product/ProductCategories.vue';
import { Product, ProductVariant } from '@/libs/types/product';
import { router } from "@inertiajs/vue3";

const props = defineProps<{ product: Product }>();

const selectedColorId = ref(props.product.variants.length ? props.product.variants[0].color.id : null);
const selectedSizeId = ref<number | null>(null);
const quantity = ref(1);

const selectedColor = computed(() =>
  props.product.variants.find(variant => variant.color.id === selectedColorId.value)?.color || null
);

const availableSizes = computed(() => {
  if (!selectedColorId.value) return [];
  return Array.from(
    new Map(
      props.product.variants
        .filter(variant => variant.color_id === selectedColorId.value && variant.stock > 0)
        .map(variant => [variant.size.id, variant.size])
    ).values()
  );
});

const availableColors = computed(() => {
  if (!selectedSizeId.value) {
    return Array.from(
      new Map(
        props.product.variants.map(variant => [variant.color.id, variant.color])
      ).values()
    );
  }

  return Array.from(
    new Map(
      props.product.variants
        .filter(variant => variant.size_id === selectedSizeId.value && variant.stock > 0)
        .map(variant => [variant.color.id, variant.color])
    ).values()
  );
});

const selectedVariant = computed<ProductVariant | null>(() => {
  if (!selectedSizeId.value) return null;
  return props.product.variants.find(
    variant => variant.color_id === selectedColorId.value && variant.size_id === selectedSizeId.value
  ) || null;
});

const maxQuantity = computed(() => selectedVariant.value?.stock || 0);
const price = computed(() => selectedVariant.value?.price || props.product.variants[0]?.price || 0);

const handleColorChange = (colorId: number) => {
  selectedColorId.value = colorId;
  if (selectedSizeId.value) {
    const isSizeAvailable = props.product.variants.some(
      variant => variant.color_id === colorId && variant.size_id === selectedSizeId.value && variant.stock > 0
    );
    if (!isSizeAvailable) {
      selectedSizeId.value = null;
    }
  }
  quantity.value = 1;
};

const handleSizeChange = (sizeId: number) => {
  selectedSizeId.value = sizeId;
  quantity.value = 1;
};

const addToCart = () => {
  if (!selectedVariant.value) return;

  router.post(route('cart.add', {
    'variant': selectedVariant.value.id,
  }), {
    quantity: quantity.value
  });
};
</script>

<template>
  <div class="max-w-7xl mx-auto opacity-0 animate-fadeIn">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Galerie Produit -->
      <div class="product-gallery transition-all duration-300 transform hover:scale-105">
        <ProductGallery 
          :images="(product.variants.find(({ color_id }) => color_id === selectedColorId)?.images || []).map(img => img.url)" 
        />
      </div>

      <!-- Détails Produit -->
      <div class="product-details space-y-6 transition-all duration-300 ease-in-out">
        <h1 class="product-title text-3xl font-bold text-gray-900">{{ product.name }}</h1>
        <p class="product-description text-gray-600">{{ product.description }}</p>

        <div class="product-price text-2xl font-semibold text-gray-900">
          {{ price.toFixed(2) }} €
        </div>

        <!-- Sélecteur de Couleur -->
        <div class="space-y-3">
          <h3 class="text-sm font-medium text-gray-900">Couleur : {{ selectedColor?.label }}</h3>
          <ProductColorSelector 
            :colors="availableColors"
            :selectedColorId="selectedColorId"
            @select="handleColorChange"
          />
        </div>

        <!-- Sélecteur de Taille -->
        <div class="space-y-3">
          <h3 class="text-sm font-medium text-gray-900">
            Taille : {{ selectedSizeId ? selectedVariant?.size.label : 'Sélectionnez une taille' }}
          </h3>
          <ProductSizeSelector 
            :sizes="availableSizes"
            :selectedSizeId="selectedSizeId"
            @select="handleSizeChange"
          />
        </div>

        <!-- Sélecteur de Quantité -->
        <div v-if="selectedSizeId" class="space-y-3">
          <h3 class="text-sm font-medium text-gray-900">Quantité :</h3>
          <ProductQuantity v-model="quantity" :max="maxQuantity" />
          <p class="text-sm text-gray-500">{{ maxQuantity }} en stock</p>
        </div>

        <!-- Bouton Ajouter au Panier -->
        <button 
          @click="addToCart"
          :disabled="!selectedSizeId"
          class="w-full bg-indigo-600 text-white py-3 px-6 rounded-md font-medium hover:bg-indigo-700 
            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 
            disabled:bg-gray-300 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-105"
        >
          {{ selectedSizeId ? 'Ajouter au panier' : 'Veuillez sélectionner une taille' }}
        </button>
      </div>
    </div>

    <!-- Catégories Produit -->
    <div class="product-categories mt-12 transition-all duration-300 ease-in-out">
      <ProductCategories :categories="product.categories" />
    </div>
  </div>
</template>

<style scoped>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.8s ease-out forwards;
}
</style>
