<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <div v-show="message" class="py-1 transition-opacity duration-300 ease-in-out" role="alert">
        <p class="text-sm text-red-600 font-medium">
            {{ message }}
        </p>
    </div>
</template>
