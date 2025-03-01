<script setup lang="ts">
import { ref } from 'vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Thumbs } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/thumbs';

const props = defineProps<{
  images: string[];
}>();

const thumbsSwiper = ref(null);
const mainSwiper = ref(null);
</script>

<template>
  <div class="product-gallery-container">
    <!-- Main Gallery -->
    <div class="main-gallery mb-4 overflow-hidden rounded-lg bg-gray-100 shadow-md">
      <Swiper
        :modules="[Navigation, Thumbs]"
        :thumbs="{ swiper: thumbsSwiper }"
        :loop="true"
        :spaceBetween="10"
        class="h-[500px] w-full"
        @swiper="mainSwiper = $event"
      >
        <SwiperSlide v-for="(image, index) in images" :key="index" class="flex items-center justify-center">
          <img :src="image" :alt="`Product image ${index + 1}`" class="h-full w-full object-cover transition-all duration-300 transform hover:scale-105" />
        </SwiperSlide>
        
        <!-- Fallback if no images -->
        <SwiperSlide v-if="images.length === 0" class="flex items-center justify-center">
          <div class="text-gray-400 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="mt-2">Aucune image disponible</p>
          </div>
        </SwiperSlide>
      </Swiper>
    </div>
    
    <!-- Thumbnails -->
    <div class="product-thumbnails">
      <Swiper
        :modules="[Thumbs]"
        :slides-per-view="4"
        :space-between="10"
        :watch-slides-progress="true"
        @swiper="thumbsSwiper = $event"
        class="h-24"
      >
        <SwiperSlide v-for="(image, index) in images" :key="index" class="cursor-pointer rounded-md overflow-hidden transition-all duration-300 hover:scale-105 focus:ring-2 focus:ring-indigo-500">
          <img :src="image" :alt="`Thumbnail ${index + 1}`" class="h-full w-full object-cover rounded-md shadow-sm border border-gray-200 hover:border-indigo-500" />
        </SwiperSlide>
      </Swiper>
    </div>
  </div>
</template>

<style scoped>
:deep(.swiper-button-next),
:deep(.swiper-button-prev) {
  color: #4f46e5;
  background-color: rgba(255, 255, 255, 0.8);
  border-radius: 50%;
  width: 40px;
  height: 40px;
}

:deep(.swiper-button-next:after),
:deep(.swiper-button-prev:after) {
  font-size: 20px;
}

:deep(.swiper-slide-thumb-active) {
  border: 2px solid #4f46e5;
  opacity: 1;
}

:deep(.swiper-slide) {
  opacity: 0.6;
  transition: opacity 0.3s, transform 0.3s;
}

:deep(.swiper-slide:hover) {
  opacity: 1;
  transform: scale(1.05);
}
</style>
