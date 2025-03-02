<script setup lang="ts">
import HomeIcon from '../Icons/HomeIcon.vue';
import ProductIcon from '../Icons/ProductIcon.vue';
import UserIcon from '../Icons/UserIcon.vue';
import CartIcon from '../Icons/CartIcon.vue';
import { Link } from '@inertiajs/vue3';

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};
</script>

<template>
  <footer class="bg-gradient-to-b from-indigo-600 to-blue-500 text-white mt-auto">
    <div class="container mx-auto px-4 py-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Logo et Description -->
        <div class="md:col-span-1">
          <div class="text-2xl font-bold text-white mb-4">ShopFilament</div>
          <p class="text-gray-300 text-sm">
            Votre boutique en ligne pour tous vos besoins. Nous proposons une large gamme de produits de qualité à des prix compétitifs.
          </p>
        </div>

        <!-- Liens de Navigation -->
        <div class="md:col-span-1">
          <h3 class="text-lg font-semibold mb-4">Navigation</h3>
          <ul class="space-y-2">
            <li v-for="(item, index) in [
              { name: 'Accueil', icon: HomeIcon, link: route('home') },
              { name: 'Produits', icon: ProductIcon, link: route('categories.index') },
            ]" :key="index">
              <Link :href="item.link" class="text-gray-300 hover:text-white transition-all duration-300 flex items-center gap-2">
                <component :is="item.icon" class="w-4 h-4" />
                <span>{{ item.name }}</span>
              </Link>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div class="md:col-span-1">
          <h3 class="text-lg font-semibold mb-4">Contact</h3>
          <ul class="space-y-2 text-gray-300">
            <li class="flex items-start gap-2">
              <svg class="w-5 h-5 mt-0.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              </svg>
              <span>123 Rue du Commerce, 75000 Paris</span>
            </li>
            <li class="flex items-center gap-2">
              <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <span>contact@shopvue.com</span>
            </li>
          </ul>
        </div>

        <!-- Newsletter -->
        <div class="md:col-span-1">
          <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
          <p class="text-gray-300 text-sm mb-4">Inscrivez-vous pour recevoir nos dernières offres</p>
          <div class="flex">
            <input type="email" placeholder="Votre email"
              class="px-4 py-3 rounded-l-lg w-full border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            <button class="bg-indigo-600 hover:from-indigo-700 hover:to-blue-600 px-4 py-3 rounded-r-lg transition-all duration-300 shadow-md hover:shadow-lg">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Footer Bottom -->
      <div class="mt-8 pt-6 flex flex-col md:flex-row justify-between items-center">
        <p class="text-gray-300 text-sm">© 2025 ShopVue. Tous droits réservés.</p>
        <button @click="scrollToTop"
          class="mt-4 md:mt-0 bg-indigo-600 text-white p-2 rounded-full transition-all duration-300 shadow-md hover:shadow-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
          </svg>
        </button>
      </div>
    </div>
  </footer>
</template>
