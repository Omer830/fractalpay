<template>
  <div class="container-fluid px-0">
    <div class="row m-0">
      <div class="col-lg-6 mx-0 px-0 d-lg-block d-none">
        <main-banner />
      </div>
      <div class="col-md-12 col-lg-6">
        <div class="form-wrapper">
          <app-typography variant="h1" fontWeight="600">Sign Up</app-typography>
          <app-typography variant="h6" color="#76848D" class="mt-2">
            Enter your credentials to access your new account
          </app-typography>
          <hr class="hr" />
          <signup-form />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { MainBanner, AppTypography } from "@components/index";
import SignupForm from "./SignupForm.vue";

export default {
  name: "SignupPage", // Rename the component to SignupPage
  components: {
    SignupForm,
    MainBanner,
    AppTypography,
  },
  props: {
    countries: Array
  }
};
</script>

<style scoped>
.form-wrapper {
  padding: 33px 80px;
  overflow-y: auto;
}

.hr{
  margin: 25px -10px;
  color:#4682BE
}
@media screen and (max-width: 475px) {
  .form-wrapper {
    padding: 20px 40px;
  }
}
</style>
