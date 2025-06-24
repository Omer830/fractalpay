<template>
    <div class="main-wrapper">
        <div class="content-wrapper my-4 text-center">

            <img :src="img" alt="dummy_img" class="img-fluid m-auto mb-4" />

            <div class="d-flex flex-column gap-3">
                <app-typography variant="text-42" fontWeight="700" class="text-center">{{ title }}
                </app-typography>
                <app-typography variant="h6" fontWeight="300" class="text-center mb-3">{{ desc }} </app-typography>
                <div class="d-flex gap-2 justify-content-center">
                    <reuseable-button :width="btnWidth" v-if="isBackButton" @click="handleBack" outline class="px-3"
                        textColor="'#4682BE'">{{ backBtnText }}</reuseable-button>
                    <reuseable-button :width="btnWidth" :btnHeight='height' @click="handleInvite" round="lg">
                    {{ btnText }}
                    </reuseable-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { AppTypography, ReuseableButton } from "@/components/index";
import SUCCESS_IMG from "../../assets/images/success.png";
export default {
    name: 'success-alert',
    components: {
        AppTypography,
        ReuseableButton
    },
    props: {
        img: {
            type: String,
            default: SUCCESS_IMG,
        },
        title: String,
        desc: String,
        isBackButton: {
            type: Boolean,
            default: false,
        },
        btnText: {
            type: String,
            default: "Invite More",
        },
        backBtnText: {
            type: String,
            default: "Go to Dashboard",
        },
        height: {
            type: String,
            default: '56px'
        },
        btnWidth: {
            type: String,
            default: '100'
        }
    },
    methods: {
        handleInvite() {
            this.$emit("invite");
        },
        handleBack() {
            this.$emit("back");
        },
    },
}

</script>

<style scoped>
.main-wrapper {
    max-width: 560px;
    margin: auto;
}
</style>