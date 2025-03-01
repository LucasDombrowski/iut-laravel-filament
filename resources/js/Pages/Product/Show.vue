<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import gsap from 'gsap';

import ProductGallery from '@/Components/Pages/Product/ProductGallery.vue';
import ProductColorSelector from '@/Components/Pages/Product/ProductColorSelector.vue';
import ProductSizeSelector from '@/Components/Pages/Product/ProductSizeSelector.vue';
import ProductQuantity from '@/Components/Pages/Product/ProductQuantity.vue';
import ProductCategories from '@/Components/Pages/Product/ProductCategories.vue';
import { Product, ProductVariant } from '@/libs/types/product';
import {router} from "@inertiajs/vue3"

// Intégration de la prop product conforme à l'interface Product
const props = defineProps<{ product: Product }>();

const { product } = props;

// Sélection initiale : première couleur disponible
const selectedColorId = ref(product.variants.length ? product.variants[0].color.id : null);
const selectedSizeId = ref<number | null>(null);
const quantity = ref(1);

// Récupérer la couleur sélectionnée
const selectedColor = computed(() =>
  product.variants.find(variant => variant.color.id === selectedColorId.value)?.color || null
);

// Filtrer les tailles disponibles en fonction de la couleur sélectionnée
const availableSizes = computed(() => {
    if (!selectedColorId.value) {
        return [];
    }

    return Array.from(
        new Map(
            product.variants
                .filter(variant => variant.color_id === selectedColorId.value && variant.stock > 0)
                .map(variant => [variant.size.id, variant.size]) // Stocke par ID pour garantir l'unicité
        ).values()
    );
});

// Filtrer les couleurs disponibles en fonction de la taille sélectionnée
const availableColors = computed(() => {
    if (!selectedSizeId.value) {
        return Array.from(
            new Map(
                product.variants.map(variant => [variant.color.id, variant.color]) // Stocke par ID pour garantir l'unicité
            ).values()
        );
    }

    return Array.from(
        new Map(
            product.variants
                .filter(variant => variant.size_id === selectedSizeId.value && variant.stock > 0)
                .map(variant => [variant.color.id, variant.color]) // Stocke par ID pour garantir l'unicité
        ).values()
    );
});

// Trouver la variante sélectionnée
const selectedVariant = computed<ProductVariant | null>(() => {
    if (!selectedSizeId.value) return null;
    return product.variants.find(
        variant => variant.color_id === selectedColorId.value && variant.size_id === selectedSizeId.value
    ) || null;
});

// Déterminer la quantité max disponible
const maxQuantity = computed(() => selectedVariant.value?.stock || 0);

// Déterminer le prix
const price = computed(() => selectedVariant.value?.price || product.variants[0]?.price || 0);

// Gérer le changement de couleur
const handleColorChange = (colorId: number) => {
    selectedColorId.value = colorId;

    // Vérifier si la taille sélectionnée est toujours dispo
    if (selectedSizeId.value) {
        const isSizeAvailable = product.variants.some(
            variant => variant.color_id === colorId && variant.size_id === selectedSizeId.value && variant.stock > 0
        );

        if (!isSizeAvailable) {
            selectedSizeId.value = null;
        }
    }

    // Réinitialiser la quantité
    quantity.value = 1;
};

// Gérer le changement de taille
const handleSizeChange = (sizeId: number) => {
    selectedSizeId.value = sizeId;
    quantity.value = 1;
};

// Ajouter au panier (simulation)
const addToCart = () => {
    if (!selectedVariant.value) return;

    router.post(route('cart.add',{
        'variant': selectedVariant.value.id,
    }),{
        quantity: quantity.value
    });
};

// Animation GSAP
onMounted(() => {
    gsap.timeline()
        .from('.product-title', { y: -50, opacity: 0, duration: 0.8, ease: 'power3.out' })
        .from('.product-description', { y: -30, opacity: 0, duration: 0.8, ease: 'power3.out' }, '-=0.5')
        .from('.product-gallery', { x: -50, opacity: 0, duration: 0.8, ease: 'power3.out' }, '-=0.6')
        .from('.product-details', { x: 50, opacity: 0, duration: 0.8, ease: 'power3.out' }, '-=0.8')
        .from('.product-categories', { y: 30, opacity: 0, duration: 0.6, ease: 'power3.out' }, '-=0.6');
});
</script>

<template>
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Galerie Produit -->
            <div class="product-gallery">
                <ProductGallery 
                    :images="(product.variants.find(({color_id}) => color_id === selectedColorId)?.images || []).map(img => img.url)" 
                />
            </div>

            <!-- Détails Produit -->
            <div class="product-details space-y-6">
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
                        disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
                >
                    {{ selectedSizeId ? 'Ajouter au panier' : 'Veuillez sélectionner une taille' }}
                </button>
            </div>
        </div>

        <!-- Catégories Produit -->
        <div class="product-categories mt-12">
            <ProductCategories :categories="product.categories" />
        </div>
    </div>
</template>
