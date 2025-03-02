<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import ProfileDropdown from './ProfileDropdown.vue';
import CartIcon from '../Icons/CartIcon.vue';
import HomeIcon from '../Icons/HomeIcon.vue';
import ProductIcon from '../Icons/ProductIcon.vue';
import UserIcon from '../Icons/UserIcon.vue';
import { Link, usePage } from '@inertiajs/vue3';

const isMenuOpen = ref(false);
const page = usePage() as any;
const isLoggedIn = computed(() => page.props.auth.user);
const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

watch(page, () => {
  isMenuOpen.value = false;
});

</script>

<template>
  <header class="bg-gradient-to-r from-indigo-600 to-blue-500 shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3">
      <div class="flex items-center justify-between">
        <!-- Logo -->
        <div class="flex-shrink-0">
          <a href="#" class="text-2xl font-bold text-white">ShopVue</a>
        </div>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-8">
          <Link :href="route('home')" class="nav-link flex items-center gap-1 text-indigo-100 hover:text-white transition-all">
            <HomeIcon class="w-5 h-5" />
            <span>Accueil</span>
          </Link>
          <Link :href="route('categories.index')" class="nav-link flex items-center gap-1 text-indigo-100 hover:text-white transition-all">
            <ProductIcon class="w-5 h-5" />
            <span>Produits</span>
          </Link>
          <div v-if="isLoggedIn" class="relative">
            <ProfileDropdown />
          </div>
          <Link v-else :href="route('login')" class="nav-link flex items-center gap-1 text-indigo-100 hover:text-white transition-all">
            <UserIcon class="w-5 h-5" />
            <span>Connexion</span>
          </Link>
          <Link :href="route('cart.index')" class="nav-link flex items-center gap-1 relative text-indigo-100 hover:text-white transition-all">
            <CartIcon class="w-5 h-5" />
            <span>Panier</span>
          </Link>
        </nav>

        <!-- Mobile Menu Button -->
        <div class="md:hidden flex items-center">
          <Link :href="route('cart.index')" class="nav-link mr-4 relative">
            <CartIcon class="w-6 h-6 text-white" />
          </Link>
          <button @click="toggleMenu" class="text-white focus:outline-none transition-transform hover:scale-105">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path v-if="!isMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Navigation -->
      <div v-show="isMenuOpen" class="md:hidden mt-4 pb-2 transition-all duration-300 ease-in-out bg-white rounded-lg shadow-md p-4">
        <div class="flex flex-col space-y-4">
          <Link :href="route('home')" class="nav-link flex items-center gap-2 text-indigo-600">
            <HomeIcon class="w-5 h-5" />
            <span>Accueil</span>
          </Link>
          <Link :href="route('categories.index')" class="nav-link flex items-center gap-2  text-indigo-600">
            <ProductIcon class="w-5 h-5" />
            <span>Produits</span>
          </Link>
          <Link :href="route('login')" v-if="!isLoggedIn" href="#" class="nav-link flex items-center gap-2 text-indigo-600">
            <UserIcon class="w-5 h-5" />
            <span>Connexion</span>
          </Link>
          <div v-else class="pl-2 border-l-2 border-indigo-300">
            <Link :href="route('account.show')" class="nav-link flex items-center gap-2 mb-2 text-indigo-600">
              <UserIcon class="w-5 h-5" />
              <span>Profil</span>
            </Link>
            <div class="pl-7 flex flex-col space-y-2">
              <Link :href="route('account.show',{
                tab: 'addresses'
              })" class="nav-link text-sm text-indigo-500">Adresses</LInk>
              <Link :href="route('account.show',{
                tab: 'orders'
              })" class="nav-link text-sm text-indigo-500">Commandes</Link>
              <Link :href="route('logout')" method="post" as="button" class="nav-link text-sm text-indigo-500 text-left text-red-500">Déconnexion</Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>
