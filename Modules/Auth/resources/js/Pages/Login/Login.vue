<template>
  <div class="container-fluid px-0">
    <div class="row m-0">
      <div class="col-lg-6 mx-0 px-0 d-lg-block d-none">
        <main-banner />
      </div>
      <div class="col-md-12 col-lg-6">
        <div class="form-wrapper">
          <app-typography variant="text-38" fontWeight="600">{{ mainTitle }}</app-typography>
          <login-form/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { MainBanner, AppTypography } from "@/components/index";
import LoginForm from "./LoginForm.vue"
export default {
  name: "LoginPage",
  components: {
    MainBanner,
    AppTypography,
    LoginForm
  },
  data() {
    return {
      mainTitle: "Log In",
    };
  },
};
</script>

<style scoped>
.form-wrapper {
  padding: 150px 115px 0px 115px;
}

@media screen and (max-width: 475px) {
  .form-wrapper {
    padding: 20px 40px;
  }
}
</style>
