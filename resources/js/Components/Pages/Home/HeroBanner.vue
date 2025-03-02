<script setup lang="ts">
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, EffectFade } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-fade';
import { Link } from '@inertiajs/vue3';

defineProps<{
  images: string[]; // Liste des images pour le slider
  title: string;
  subtitle: string;
  buttonText: string;
  buttonLink: string;
}>();
</script>

<template>
  <div class="relative w-full h-[500px] flex items-center justify-center text-center text-white">
    <!-- Slider d'images -->
    <Swiper
      :modules="[Autoplay, EffectFade]"
      :autoplay="{ delay: 5000, disableOnInteraction: false }"
      :effect="'fade'"
      loop
      class="absolute inset-0 w-full h-full"
    >
      <SwiperSlide v-for="(image, index) in images" :key="index">
        <div 
          class="absolute inset-0 bg-cover bg-center"
          :style="{ backgroundImage: `url(${image})` }"
        ></div>
      </SwiperSlide>
    </Swiper>

    <!-- Overlay sombre pour améliorer la lisibilité -->
    <div class="absolute inset-0 bg-black/50 z-10"></div>

    <!-- Contenu du Hero Banner -->
    <div class="relative z-10 max-w-3xl">
      <h1 class="text-4xl sm:text-5xl font-bold mb-4">{{ title }}</h1>
      <p class="text-lg sm:text-xl mb-6">{{ subtitle }}</p>
      <Link 
        :href="buttonLink"
        class="block w-fit mx-auto bg-gradient-to-r from-indigo-600 to-blue-500 px-6 py-3 rounded-md text-white font-semibold shadow-lg hover:scale-105 transition-transform hover:from-indigo-700 hover:to-blue-600"
      >
        {{ buttonText }}
    </Link>
    </div>
  </div>
</template>
