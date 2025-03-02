<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import UserIcon from '../Icons/UserIcon.vue';
import { Link } from '@inertiajs/vue3';

const isOpen = ref(false);
const dropdownRef = ref<HTMLElement | null>(null);

const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};

const closeDropdown = (event: MouseEvent) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target as Node)) {
    isOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', closeDropdown);
});

onUnmounted(() => {
  document.removeEventListener('click', closeDropdown);
});
</script>

<template>
  <div ref="dropdownRef" class="relative">
    <button @click.prevent="toggleDropdown" class="nav-link flex items-center gap-1 text-indigo-100 hover:text-white transition-all">
      <UserIcon class="w-5 h-5" />
      <span>Profil</span>
      <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
      </svg>
    </button>
    
    <div v-show="isOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 transition-all duration-200 ease-in-out">
      <Link :href="route('account.show',{
        tab: 'addresses'
      })" class="block px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-100">Adresses</Link>
      <Link :href="route('account.show',{
        tab: 'orders'
      })" class="block px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-100">Commandes</Link>
      <div class="border-t border-indigo-100 my-1"></div>
      <Link :href="route('logout')" method="post" as="button" class="block px-4 py-2 text-sm text-red-600 hover:bg-indigo-100 w-full text-left">Déconnexion</Link>
    </div>
  </div>
</template>