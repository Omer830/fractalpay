\<template>
<!--  <StripeElements-->
<!--      v-if="stripeLoaded"-->
<!--      v-slot="{ elements, instance }"-->
<!--      ref="elms"-->
<!--      :stripe-key="stripeKey"-->
<!--      :instance-options="instanceOptions"-->
<!--      :elements-options="elementsOptions"-->
<!--  >-->
<!--    <StripeElement-->
<!--        ref="card"-->
<!--        :elements="elements"-->
<!--        :options="cardOptions"-->
<!--    />-->
<!--  </StripeElements>-->
<!--  <button type="button" @click="addCard">Add Card</button>-->
<!--  <div v-if="error">{{ error }}</div>-->
</template>

<script>
import { StripeElements, StripeElement } from 'vue-stripe-js'
import { loadStripe } from '@stripe/stripe-js'
import { defineComponent, ref, onBeforeMount } from 'vue'

export default defineComponent({
  name: 'AddPaymentMethod',

  components: {
    StripeElements,
    StripeElement,
  },

  props: {
    clientSecret: String, // Ensure this prop is passed down from the parent or view
  },

  // setup(props) {
  //   const stripeKey = ref('pk_test_51H01bCGbzLJ0Bm2NM5MiQaNlJzawH9LNcDBtjl9Y6tdMX1KtseqzRbhjyHkO1X8KhKemfiOJ9yZZGgsycq7niwEv004XFRN1Dl') // Use your actual Stripe public key
  //   const instanceOptions = ref({
  //     // Stripe instance options if needed
  //   })
  //   const elementsOptions = ref({
  //     // Stripe elements options if needed
  //   })
  //   const cardOptions = ref({
  //     // Card element options (e.g., style, hidePostalCode, etc.)
  //   })
  //   const stripeLoaded = ref(false)
  //   const card = ref()
  //   const elms = ref()
  //   const error = ref('')
  //
  //   onBeforeMount(() => {
  //     const stripePromise = loadStripe(stripeKey.value)
  //     stripePromise.then(() => {
  //       stripeLoaded.value = true
  //     })
  //   })
  //
  //   const addCard = async () => {
  //     const cardElement = card.value.stripeElement
  //
  //     try {
  //       const {setupIntent, error: stripeError} = await elms.value.instance.confirmCardSetup('seti_1Ps3B4GbzLJ0Bm2N4LlouBwy_secret_QjWDfsd40Ug02WBxdp1JFIbSedPCZaw', {
  //         payment_method: {
  //           card: cardElement,
  //           billing_details: {name: 'Card Holder Name'}, // Adjust accordingly
  //         },
  //       })
  //
  //       if (stripeError) {
  //         error.value = stripeError.message
  //       } else {
  //         // Handle the payment method and pass it to your backend
  //         console.log('Payment method added:', setupIntent.payment_method)
  //       }
  //     } catch (err) {
  //       error.value = err.message
  //     }
  //   }
  //
  //   return {
  //     stripeKey,
  //     stripeLoaded,
  //     instanceOptions,
  //     elementsOptions,
  //     cardOptions,
  //     card,
  //     elms,
  //     addCard,
  //     error,
  //   }
  // },
})
</script>
