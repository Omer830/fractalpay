<template>

  <base-modal
      :isVisible="isVisible"
      :title="title"
      :subTitle="subTitle"
      buttonTitle="Save"
      :modalWidth="'600px'"
      iconBgColor="#205C40"
      @SUBMIT="$emit('FORM_SUBMIT', form)"
      @update:isVisible="$emit('update:isVisible', $event)"
  >

    <div class="min-h-[300px] text-center max-h[400px] overflow-auto">
      <!-- Select All and Search Section -->
      <OtpPlugin :isEmail="true" :isBackBtn="false" />


    </div>

  </base-modal>

</template>


<script>
import BaseModal from "./BaseModal.vue";
import OtpPlugin from "@modules/Auth/resources/js/Pages/Otp/OtpPlugin.vue";

export default {
  components: {
    OtpPlugin,


    BaseModal,

  },
  props: {
    isVisible: Boolean,
    title: {
      type: String,
      required: true,
      default: '...'
    },
    subTitle: {
      type: String,
      default: ''
    },
    formData: {
      type: Array,
      required: true
    },
    visitType:{
      type:Array,
      required: false,
    },
    contributorsArray:{
      type:Array,
      required: false,
    }
  },
  data() {
    return {
      form: this.formData,
      debouncedGetProviderDetails: null,



    };
  },
  watch: {
    // isVisible(newValue) {
    //   if (newValue) {
    //     this.resetForm();
    //   }
    // }


  },
  created() {

  },

  computed: {


  },

  methods: {
    handleCancel() {
      this.$emit('cancel');
    },


  },
}
</script>

<style scoped>

.empty-message {
  text-align: center;
  color: #76848D;
  margin-top: 20px;
}
</style>
