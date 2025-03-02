<script setup lang="ts">
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import { Navigation } from 'swiper/modules';
import { Product } from '@/libs/types/product';
import ProductCard from '../Category/ProductCard.vue';

const props = defineProps<{ topProducts: Product[] }>();
</script>

<template>
  <section class="py-10 max-w-7xl mx-auto px-4">
    <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center bg-gradient-to-r from-indigo-600 to-blue-500 text-transparent bg-clip-text">Produits les plus vendus</h2>
    <Swiper
      :modules="[Navigation]"
      :spaceBetween="20"
      :slidesPerView="1"
      :breakpoints="{ 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }"
      class="w-full !overflow-y-visible !overflow-x-clip"
    >
      <SwiperSlide v-for="product in topProducts" :key="product.id">
        <ProductCard :product="product" />
      </SwiperSlide>
    </Swiper>
  </section>
</template>
