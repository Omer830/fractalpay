<template>
    <success-alert :img="imgSrc" title="Welcome Back!" desc="Your account is linked with FractalPay"
        :isBackButton="false" @invite="handleInviteAction" btnText="Dashboard" height="80px" btnWidth="75" />
</template>
<script>
import { SuccessAlert } from "@/components/index";
import SUCCESS_IMG from "../../assets/images/success.png";
export default {
    name: "success-screen",
    components: {
        SuccessAlert,
    },
    data() {
        return {
            imgSrc: SUCCESS_IMG
        }
    },
    methods: {
        handleInviteAction() {
            this.$router.push('/investment-dashboard')
        }
    }
};
</script>

<style scoped></style>