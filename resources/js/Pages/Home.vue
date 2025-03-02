<script setup lang="ts">
import HeroDiscount from '@/Components/Pages/Home/HeroDiscount.vue';
import CategoryList from '@/Components/Pages/Home/CategoryList.vue';
import ProductCarousel from '@/Components/Pages/Home/ProductCarousel.vue';
import HighlightedProducts from '@/Components/Pages/Home/HighlightedProducts.vue';
import { ProductCategory, Product} from '@/libs/types/product';
import { Discount } from '@/libs/types/order';
import HeroBanner from '@/Components/Pages/Home/HeroBanner.vue';
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
  categories: ProductCategory[];
  topProducts: Product[];
  highlightedProducts: Product[];
  discount: Discount;
}>();
</script>

<template>
  <Head title="Home"/>
  <div>
    <HeroBanner
      title="Découvrez Nos Meilleurs Produits"
      subtitle="Qualité et élégance réunies pour vous."
      button-text="Voir la boutique"
      :buttonLink="route('categories.index')"
      :images="categories.map(category => category.thumbnail)"
    />
    <HeroDiscount :discount="discount" />
    <CategoryList :categories="categories" />
    <ProductCarousel :topProducts="topProducts" />
    <HighlightedProducts :highlightedProducts="highlightedProducts" />
  </div>
</template>
