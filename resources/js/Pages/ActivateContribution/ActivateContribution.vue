<template>
    <div>
        <logo-header @back-clicked="handleBack" />
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="card-header bg-white">
                            <app-typography variant="h4" color="#4682BE">{{ cardDetails.title }}</app-typography>
                            <app-typography variant="body3" color="#76848D">{{ cardDetails.subTitle }}</app-typography>
                        </div>
                        <div class="card-body my-4">
                            <div class="capsule-wrapper d-flex align-items-center justify-content-center">
                                <app-typography variant="body1" color="#76848D" fontWeight="500">{{ cardDetails.capsule
                                    }}</app-typography>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-center gap-3 bg-white border-0">
                            <reuseable-button class="px-5">Activate</reuseable-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { LogoHeader, AppTypography, ReuseableButton } from "@/components/index";
export default {
    components: {
        LogoHeader,
        AppTypography,
        ReuseableButton
    },
    data() {
        return {
            cardDetails: {
                title: "To Activate Your Contribution ",
                subTitle: "proof of identity needed",
                capsule: "Almost there, please activate your account"
            },
        }
    },
    methods: {
        handleBack() {
            alert('back')
        }
    }
}
</script>

<style scoped>
.card {
    box-shadow: 0px 4.92px 9.83px 0px #00000014;
    box-shadow: 0px 0px 2.46px 0px #0000000A;
    border-radius: 6px;
    padding: 32px;
}

.capsule-wrapper {
    box-shadow: 0px 3.58px 7.15px 0px #00000014;
    box-shadow: 0px 0px 1.79px 0px #0000000A;
    border: 1px solid #3A84C6;
    border-radius: 50px;
    max-width: 475px;
    height: 56px;
    margin: auto;
}
</style>