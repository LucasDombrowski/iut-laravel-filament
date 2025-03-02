<script setup>
import AppFooter from '@/Components/Layout/AppFooter.vue';
import AppHeader from '@/Components/Layout/AppHeader.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    title: String,
});

</script>

<template>

    <Head :title="title" />

    <div class="flex flex-col min-h-screen">
        <AppHeader />
        <main class="flex-grow">
            <!-- Slot pour le contenu principal -->
            <slot></slot>
        </main>
        <AppFooter />
    </div>


</template>
