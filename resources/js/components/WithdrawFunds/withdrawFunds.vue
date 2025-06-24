<template>
    <div class="card">
        <div class="card-header bg-white">
            <app-typography variant="h4" fontWeight="500">{{ cardTitle }}</app-typography>
        </div>
        <div class="card-body mb-5">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <app-typography variant="body1" fontWeight="500" class="my-3">
                        {{ inputLabel }}
                    </app-typography>
                    <div class="amount-input positive-relative">
                        <div class="icon position-absolute d-flex align-items-center justify-content-center">
                            <app-typography variant="h4" fontWeight="500" color="#09BB30">
                                $
                            </app-typography>
                        </div>
                        <input type="text" class="border-1" placeholder="250">
                    </div>
                </div>
                <div class="col-lg-4">
                    <investment-card :headerTitle='investmentCardTitle' @footer-action="hanldeFooterAction"
                        :footerText="footerDetails">
                        <app-typography variant="h5" color="#57E794" fontWeight="700" class="mb-3">{{
                            cardAmount }}</app-typography>
                        <div class="content d-flex justify-content-between mb-4">
                            <app-typography variant="body2" fontWeight="500" color="#ffffff">Total</app-typography>
                            <div class="d-flex gap-2">
                                <app-typography variant="body3" fontWeight="500" color="#ffffff">Wallet ID:
                                    {{ walletId }}</app-typography>
                                <image-svg width="16px" height="16px" name="copy-address-icon" />
                            </div>
                        </div>
                    </investment-card>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex gap-3 justify-content-center">
                <reuseable-button outline @click="handleCancel" textColor=" #4682BE" class="px-5"
                    round="md">Cancel</reuseable-button>
                <reuseable-button @click="handleContinue" class="px-5" round="md">Coninue</reuseable-button>
            </div>
        </div>
    </div>
</template>

<script>
import AppTypography from '../Typography/appTypography.vue';
import ReuseableButton from '../Button/reuseableButton.vue';
import DetailsCard from '../DetailsCard/detailsCard.vue';
import InvestmentCard from '../InvestmentCard/investmentCard.vue';
import ImageSvg from '../ImageSvg/imageSvg.vue';
export default {
    components: {
        AppTypography,
        ReuseableButton,
        DetailsCard,
        InvestmentCard,
        ImageSvg
    },
    props: {
        cardTitle: {
            type: String,
            default: "Withdraw Funds"
        },
        inputLabel: {
            type: String,
            default: "Withdraw  Funds"
        },
        cardAmount: {
            type: String,
            default: "$525.00"
        },
        walletId: {
            type: String,
            default: "24579485"
        },
        investmentCardTitle: {
            type: String,
            default: "Investment Wallet"
        },
        footerDetails: {
            type: String,
            default: "Zyanaza ID: 24579485"
        }
    },
    methods: {
        handleCancel() {
            this.$emit('cancel')
        },
        handleContinue() {
            this.$emit('continue')
        }
    }
}
</script>

<style scoped>
.card {
    box-shadow: 0px 8px 16px 0px #00000014;
    box-shadow: 0px 0px 4px 0px #0000000A;
    border-radius: 12px;
}

.card-header {
    border-radius: 12px 12px 0px 0px;
    padding: 27px 34px;
}

.card-body {
    padding: 10px 34px;
}

.card-footer {
    border-radius: 0px 0px 12px 12px;
    padding: 40px 34px;
}

.amount-input input {
    border-radius: 6px;
    height: 44px;
    width: 124px;
}

.amount-input input[type="text"] {
    padding-left: 50px;
    font-weight: 500;
    font-size: 24px;
}

.icon {
    background-color: #B3EFCD;
    border: 1px solid #D0D3E8;
    border-radius: 6px;
    height: 44px;
    width: 44px;
}
</style>