<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        setTimeout(() => input.value.focus(), 10);
    }
});

defineExpose({ focus: () => input.value?.focus() });
</script>

<template>
    <input
        ref="input"
        class="w-full px-3 py-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm placeholder-gray-400 transition-colors duration-300"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
