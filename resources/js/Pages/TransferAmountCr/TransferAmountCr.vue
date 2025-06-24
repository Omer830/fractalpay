<template>
    <div>
        <logo-header @back-clicked="handleHeaderBack" />
        <div class="container mb-4">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <title-action-bar textWeight="600" textVariant="h4" :title="header.title" :isSubTitle="true"
                        :subTitle=header.subTitle :isActionButtons="false" :isUserName="false" :isDivider="true" />
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-white border-bottom">
                            <app-typography variant="h4" fontWeight="500">
                                {{ cardDetails.title }}
                            </app-typography>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row gy-4">
                                    <div class="col-lg-12">
                                        <app-typography variant="body1" fontWeight="500" class="my-3">
                                            Transfer Amount
                                        </app-typography>
                                        <div class="amount-input positive-relative">
                                            <div
                                                class="icon position-absolute d-flex align-items-center justify-content-center">
                                                <app-typography variant="h4" fontWeight="500" color="#09BB30">
                                                    $
                                                </app-typography>
                                            </div>
                                            <input type="text" class="border-1" placeholder="250">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <app-typography variant="h4" fontWeight="500">
                                            Add Card
                                        </app-typography>
                                    </div>
                                    <div class="col-lg-6">
                                        <form-group-input type="text" label="Card Number"
                                            v-model="formDetails.cardNumber">
                                        </form-group-input>
                                    </div>
                                    <div class="col-lg-6">
                                        <form-group-input type="text" label="Expiry Date"
                                            v-model="formDetails.expiryDate">
                                        </form-group-input>
                                    </div>

                                    <div class="col-lg-6">
                                        <form-group-input type="text" label="Card Holder Name"
                                            v-model="formDetails.holderName">
                                        </form-group-input>
                                    </div>
                                    <div class="col-lg-6">
                                        <form-group-input type="text" label="Phone Number"
                                            v-model="formDetails.phoneNumber">
                                        </form-group-input>
                                    </div>
                                    <div class="col-lg-6">
                                        <form-group-input type="text" label="CVV" v-model="formDetails.cvv">
                                        </form-group-input>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div
                            class="card-footer d-flex flex-wrap justify-content-center border-top bg-white gap-3 pt-4 pb-5">
                            <reuseable-button @click="handleBack" class="px-5" outline
                                textColor="#4682BE">Back</reuseable-button>
                            <reuseable-button @click="handleContinue" class="px-5">Continue</reuseable-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    LogoHeader, TitleActionBar, AppTypography, FormGroupInput, ReuseableButton
} from "@/components/index";
export default {
    components: {
        LogoHeader,
        TitleActionBar,
        AppTypography,
        FormGroupInput,
        ReuseableButton
    },
    data() {
        return {
            header: {
                title: "Transfer Amount Through Credit Card",
                subTitle: "Select your Fund plan for transaction"
            },
            cardDetails: {
                title: 'Transfer Amount'
            },
            formDetails: {
                cardNumber: "",
                expiryDate: "",
                holderName: "",
                phoneNumber: "",
                cvv: ""
            }
        }
    },
    methods: {
        handleHeaderBack() {
            alert('back')
        },
        handleBack() {
            alert('back')
        },
        handleContinue() {
            alert('continue')
        }
    }
}
</script>

<style scoped>
.card {
    box-shadow: 0px 4.92px 9.83px 0px #00000014;
    box-shadow: 0px 0px 2.46px 0px #0000000A;
    border-radius: 12px;
}

.card-header {
    border-radius: 12px 12px 0px 0px;
    padding: 43px 34px 23px 34px;
}

.card-body {
    padding: 16px 34px 23px 43px;
}

.amount-input input {
    border-radius: 50px;
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
    border-radius: 50px;
    height: 44px;
    width: 44px;
}

.card-footer {
    border-radius: 0px 0px 12px 12px;
}
</style>