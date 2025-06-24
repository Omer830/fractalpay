<template>
    <div>
        <logo-header @back-clicked="handleBack" />
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-8 offset-lg-2">
                    <title-action-bar textWeight="600" textVariant="text-38" :title="header.title"
                        :subTitle="header.subTitle" :isActionButtons="false" :isUserName="false" :isDivider="true" />
                </div>
                <div class="col-lg-8 offset-lg-2">
                    <payment-summary />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { LogoHeader, TitleActionBar, PaymentSummary } from "@/components/index";
export default {
    components: {
        PaymentSummary,
        TitleActionBar,
        LogoHeader,
    },
    data() {
        return {
            header: {
                title: "Summary",
                subTitle: "Please confirm if this is correct"
            },
            cardDetails: {
                title: "Contribution Plan for Harold",
                subTitle: "Fractal Pay allows you to make Regular Contributions or One-Off Payments."
            },
        }
    },
    methods: {
        handleBack() {
            this.$router.push("/")
        }
    }

}
</script>

<style scoped></style>