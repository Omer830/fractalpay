<template>
    <div>
        <logo-header :isBackBtn="false" />
        <div class="otp-box mt-5">
            <OtpPlugin :isEmail="true" /> <!-- Include the OtpPlugin component -->
        </div>
    </div>
</template>

<script>
import { LogoHeader } from "@/components/index.js";
import OtpPlugin from "./OtpPlugin.vue";

export default {
    name: 'OtpPage',
    components: {
        LogoHeader,
        OtpPlugin,
    },
    data() {
        return {
            // any data if needed
        };
    },

}
</script>

<style scoped>
.otp-box {
    background-color: rgba(232, 236, 238, 0.25);
    margin-left: auto;
    margin-right: auto;
    display: flex;
    flex-direction: column;
    width: fit-content;
    padding: 30px;
    justify-content: center;
    text-align: center;
}
</style>
