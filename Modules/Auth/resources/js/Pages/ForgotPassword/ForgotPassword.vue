<template>
  <div class="container-fluid px-0 ">
    <div class="row m-0">
      <div class="col-lg-6 mx-0 px-0 d-lg-block d-none">
        <main-banner height="100vh" />
      </div>
      <div class="col-md-12 col-lg-6 main-wrapper">
        <div class="form-wrapper">
          <app-typography variant="h1" fontWeight="600">{{ mainTitle }}</app-typography>
          <app-typography variant="body1" color="#76848D" class="mt-3 mb-4">{{ desc }}</app-typography>
          <forgot-password-form />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { MainBanner, AppTypography } from "@/components/index.js";
import ForgotPasswordForm from "./ForgotPasswordForm.vue"
export default {
  name: "forgot-password",
  components: {
    MainBanner,
    AppTypography,
    ForgotPasswordForm
  },
  data() {
    return {
      mainTitle: "Forgot Password",
      desc: `No worries, we’ll send you reset instructions.`,
    };
  },
};
</script>

<style scoped>
.main-wrapper {
  max-width: 494px;
  margin: auto;
  /* place-items: center; */
  /* min-height: 100vh;  */
  /* display: grid; */
}

/* .form-wrapper {
  padding: 33px 80px;
} */
@media screen and (max-width: 475px) {
  .form-wrapper {
    padding: 20px 40px;
  }
}
</style>
