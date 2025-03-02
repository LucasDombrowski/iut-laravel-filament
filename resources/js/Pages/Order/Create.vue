<script setup lang="ts">
import { ref, computed } from 'vue';
import CartSummary from '@/Components/Pages/Order/CartSummary.vue';
import AddressForm from '@/Components/Pages/Order/AddressForm.vue';
import PaymentForm from '@/Components/Pages/Order/PaymentForm.vue';
import CheckoutStepper from '@/Components/Pages/Order/CheckoutStepper.vue';
import { Cart } from '@/libs/types/order';
import { Address } from '@/libs/types/address';
import { Head, router } from "@inertiajs/vue3";

const props = defineProps<{
  cart: Cart;
  savedAddresses: Address[];
  countries: { [key: string]: string };
}>();

const steps = ['Panier', 'Livraison', 'Paiement'];
const currentStep = ref(0);
const saveAddress = ref(false);
const selectedAddress = ref<Address | null>(null);
const isProcessing = ref(false);
const paymentError = ref<string | null>(null);

const goToStep = (step: number) => {
  if (step < currentStep.value) {
    currentStep.value = step;
  }
};

const nextStep = () => {
  if (currentStep.value === 0 && props.cart.items.length === 0) return;
  if (currentStep.value === 1 && !selectedAddress.value) return;

  if (currentStep.value < steps.length - 1) {
    currentStep.value++;
  } else {
    if (!isProcessing.value) {
      processOrder();
    }
  }
};

const previousStep = () => {
  if (currentStep.value > 0) {
    currentStep.value--;
  }
};

const handleAddressSelected = (address: Address) => {
  selectedAddress.value = address;
  nextStep();
};

const handleSaveAddress = (value: boolean) => {
  saveAddress.value = value;
};

const handlePaymentComplete = async () => {
  isProcessing.value = true;
  paymentError.value = null;

  try {
    await new Promise(resolve => setTimeout(resolve, 1500));
    processOrder();
  } catch (error) {
    paymentError.value = "Le paiement a échoué. Veuillez réessayer.";
    isProcessing.value = false;
  }
};

const processOrder = () => {
  if (!selectedAddress.value) return;

  const addressData: any = { ...selectedAddress.value };
  if (saveAddress.value) {
    addressData.save = true;
  }

  router.post(route('checkout.add'), { address: addressData }, {
    onStart: () => isProcessing.value = true,
    onFinish: () => isProcessing.value = false,
    onError: (error) => paymentError.value = error.message || "Une erreur est survenue.",
  });
};

const isLastStep = computed(() => currentStep.value === steps.length - 1);
const canContinue = computed(() => {
  if (currentStep.value === 0) return props.cart.items.length > 0;
  if (currentStep.value === 1) return !!selectedAddress.value;
  return true;
});
</script>

<template>
  <Head title="Checkout"/>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Finaliser votre commande</h1>
      
      <CheckoutStepper :steps="steps" :current-step="currentStep" @go-to-step="goToStep" />
      
      <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
          <transition name="fade-slide" mode="out-in">
            <div :key="currentStep">
              <div v-if="currentStep === 0">
                <CartSummary :cart="cart" />
                <div class="mt-6 flex justify-end">
                  <button 
                    @click="nextStep"
                    :disabled="!canContinue"
                    class="bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-3 px-6 rounded-md hover:from-indigo-700 hover:to-blue-600 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:bg-gray-300 disabled:cursor-not-allowed"
                  >
                    Continuer
                  </button>
                </div>
              </div>

              <div v-else-if="currentStep === 1">
                <AddressForm 
                  :saved-addresses="savedAddresses" 
                  @update:address="handleAddressSelected"
                  @save-address="handleSaveAddress"
                  :countries="countries"
                />
                <div class="mt-6 flex justify-between">
                  <button 
                    @click="previousStep" 
                    class="border border-gray-300 bg-white text-gray-700 py-3 px-6 rounded-md hover:bg-gray-50 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                  >
                    Retour
                  </button>
                </div>
              </div>

              <div v-else-if="currentStep === 2">
                <PaymentForm @payment-complete="handlePaymentComplete" />
                <div v-if="paymentError" class="mt-4 text-red-600 text-sm">{{ paymentError }}</div>
                <div class="mt-6 flex justify-between">
                  <button 
                    @click="previousStep" 
                    class="border border-gray-300 bg-white text-gray-700 py-3 px-6 rounded-md hover:bg-gray-50 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                  >
                    Retour
                  </button>
                </div>
              </div>
            </div>
          </transition>
        </div>

        <div class="lg:col-span-1">
          <div class="sticky top-8">
            <CartSummary :cart="cart" />
            
            <div v-if="selectedAddress && !isLastStep" class="mt-6 bg-white rounded-lg shadow-md p-6">
              <h3 class="text-lg font-medium text-gray-800 mb-2">Adresse de livraison</h3>
              <p class="text-gray-600">{{ selectedAddress.number }} {{ selectedAddress.street }}</p>
              <p v-if="selectedAddress.complement" class="text-gray-600">{{ selectedAddress.complement }}</p>
              <p class="text-gray-600">{{ selectedAddress.zip_code }} {{ selectedAddress.city }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Transition entre les étapes */
.fade-slide-enter-active, .fade-slide-leave-active {
  transition: opacity 0.5s ease, transform 0.5s ease;
}

.fade-slide-enter-from, .fade-slide-leave-to {
  opacity: 0;
  transform: translateY(10px);
}
</style>
