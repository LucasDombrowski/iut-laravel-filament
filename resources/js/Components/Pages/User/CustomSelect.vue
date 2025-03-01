<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted } from 'vue';

const props = defineProps<{
  modelValue: string;
  options: { value: string; label: string }[];
}>();

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const selectedOption = ref(
  props.options.find(option => option.value === props.modelValue) || props.options[0]
);

watch(() => props.modelValue, (newValue) => {
  selectedOption.value = props.options.find(option => option.value === newValue) || props.options[0];
});

const selectOption = (option: { value: string; label: string }) => {
  selectedOption.value = option;
  emit('update:modelValue', option.value);
  isOpen.value = false;
};

const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};

const closeDropdown = (event: Event) => {
  const target = event.target as HTMLElement;
  if (!target.closest('.dropdown')) {
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
  <div class="relative dropdown w-full">
    <button
      type="button"
      @click="toggleDropdown"
      class="w-full flex justify-between items-center px-4 py-2 border border-gray-300 rounded-md bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
    >
      <span>{{ selectedOption.label }}</span>
      <svg
        :class="{ 'rotate-180': isOpen }"
        class="h-5 w-5 text-gray-400 transition-transform"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
        fill="currentColor"
      >
        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
      </svg>
    </button>

    <div
      v-if="isOpen"
      class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto opacity-0 animate-fadeIn"
    >
      <div
        v-for="option in options"
        :key="option.value"
        @click="selectOption(option)"
        class="px-4 py-2 cursor-pointer hover:bg-indigo-100 transition-all"
        :class="{ 'bg-indigo-50 text-indigo-700': option.value === selectedOption.value }"
      >
        {{ option.label }}
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-5px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.2s ease-out forwards;
}
</style>
