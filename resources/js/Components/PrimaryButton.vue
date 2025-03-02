<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button 
        :type="type" 
        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-blue-500 border border-transparent rounded-lg font-semibold text-xs text-white shadow-md hover:shadow-lg hover:from-indigo-700 hover:to-blue-600 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition-all duration-300 transform hover:scale-105"
    >
        <slot />
    </button>
</template>
