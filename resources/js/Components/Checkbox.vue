<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        default: false,
    },
    value: {
        type: String,
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },
    set(val) {
        emit('update:checked', val);
    },
});
</script>

<template>
    <input
        v-model="proxyChecked"
        type="checkbox"
        :value="value"
        class="peer rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:outline-none cursor-pointer transition-all duration-200 ease-in-out"
        role="checkbox"
        :aria-checked="Boolean(proxyChecked)"
    >
</template>
