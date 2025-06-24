<template>
    <div>
        <logo-header @back-clicked="handleBack" />
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <regular-payment />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { LogoHeader, RegularPayment } from "@/components/index";
export default {
    components: {
        LogoHeader,
        RegularPayment
    },
    data() {
        return {
            cardDetails: {
                title: "Contribution Plan for Harold",
                subTitle: "Fractal Pay allows you to make Regular Contributions or One-Off Payments."
            },
        }
    },
    methods: {
        handleBack() {
            this.$router.push("/contribution-plan")
        }
    }

}
</script>

<style scoped></style>