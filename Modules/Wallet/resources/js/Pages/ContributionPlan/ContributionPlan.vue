<template>
    <div>
        <logo-header :isBackBtn="false" />
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="card-header bg-white">
                            <app-typography variant="h4" color="#4682BE">{{ cardDetails.title }}</app-typography>
                            <app-typography variant="body3" color="#76848D">{{ cardDetails.subTitle }}</app-typography>
                        </div>
                        <div class="card-body my-3">
                            <div class="capsule-wrapper" v-for="(item, index) in capsulesArray" :key="index">
                                <capsule-field capPadding="15px" :label="item.title" :image-source="item.icon"
                                    :width="41" :height="41" />
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-center gap-3 bg-white border-0">
                            <reuseable-button outline textColor="#4682BE" class="px-5">Cancel</reuseable-button>
                            <reuseable-button class="px-5">Continue</reuseable-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { LogoHeader, AppTypography, CapsuleField, ReuseableButton } from "@/components/index";
export default {
    components: {
        LogoHeader,
        AppTypography,
        CapsuleField,
        ReuseableButton
    },
    data() {
        return {
            cardDetails: {
                title: "Contribution Plan for Harold",
                subTitle: "Fractal Pay allows you to make Regular Contributions or One-Off Payments."
            },
            capsulesArray: [
                { icon: 'regular-icon', title: 'Regular' },
                { icon: 'swipe-icon', title: 'One-Off Payment' },
            ],
        }
    },

}
</script>

<style scoped>
.card {
    box-shadow: 0px 4.92px 9.83px 0px #00000014;
    box-shadow: 0px 0px 2.46px 0px #0000000A;
    border-radius: 6px;
    padding: 32px;
}
</style>