<template>
    <div class="card">
        <div class="header d-flex flex-column align-items-center p-5">
            <image-svg width="110px" height="110px" :name="headerIcon" />
            <app-typography variant="h3" color="#32BA7C" fontWeight="600" class="mt-4 mb-2">{{ modalTitle
                }}</app-typography>
            <app-typography v-if="isSubTitle" variant="h5" color="#777E90">{{ modalSubTitle }}</app-typography>
        </div>
        <hr class="divider">
        <div class="card-body px-5" v-for="(item, index) in detailsArray" :key="index">
            <div class="list-item d-flex justify-content-between flex-wrap">
                <app-typography class="pb-3" variant="h6" color="#777E90" fontWeight="500">{{
                    item.title }}</app-typography>
                <app-typography class="pb-3" variant="h5" color="#777E90">{{ item.detail }}</app-typography>
            </div>
        </div>
        <div v-if="isFooterBtns" class="card-footer d-flex gap-3 align-items-center justify-content-center flex-wrap">
            <reuseable-button v-if="isSecondaryBtn" outline round="sm" class="px-5" textColor="#4682BE">{{ secondaryText
                }}</reuseable-button>
            <reuseable-button v-if="isPrimaryBtn" round="sm" class="px-5">{{ primaryText }}</reuseable-button>
        </div>
    </div>
</template>

<script>
import { AppTypography, ReuseableButton } from "@/components/index";
import ImageSvg from "../ImageSvg/imageSvg.vue";
export default {
    components: {
        ReuseableButton,
        AppTypography,
        ImageSvg,
    },
    props: {
        headerIcon: {
            type: String,
            default: 'success-tick-icon'
        },
        modalTitle: {
            type: String,
            default: 'Add Funds Successfully'
        },
        isSubTitle: {
            type: Boolean,
            default: true
        },
        modalSubTitle: {
            type: String,
            default: 'It might take up to 3 days to reflect that on your account'
        },
        isFooterBtns: {
            type: Boolean,
            default: true
        },
        isPrimaryBtn: {
            type: Boolean,
            default: true
        },
        isSecondaryBtn: {
            type: Boolean,
            default: true
        },
        primaryText: {
            type: String,
            default: 'Dashobard'
        },
        secondaryText: {
            type: String,
            default: 'Setup Distribution'
        },
        detailsArray: {
            type: Array,
            default: () => [
                { title: 'Payment type', detail: 'Credit Card' },
                { title: 'Card Number', detail: '9222' },
                { title: 'Transferred Amount', detail: '$500' },
                { title: 'Transaction id', detail: '127487494058' },
            ]
        },

    },
}
</script>

<style scoped>
.card {
    box-shadow: 0px 8px 16px 0px #00000014;
    box-shadow: 0px 0px 4px 0px #0000000A;
    border-radius: 16px;
}

.card-footer {
    border-top: 1px solid #DDEAF4;
    background-color: transparent;
    padding: 23px;
}

.divider {
    color: #DDEAF4
}

.list-item {
    border-bottom: 1px solid #DDEAF4;
}

.list-item .list-item:last-child {
    border-bottom: none !important;
}
</style>