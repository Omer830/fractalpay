<template>
    <div class="container-fluid">
        <div class="row gy-4">
            <div class="col-lg-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title"
                    :subTitle="header.subTitle" :isActionButtons="false" :isUserName="false" :isDivider="true" />
            </div>
            <div class="col-lg-8 offset-lg-2">
                <div class="card">
                    <div class="card-header bg-white px-0">
                        <app-typography variant="h4" fontWeight="500">{{ cardDetails.title }}</app-typography>
                    </div>
                    <div class="card-body px-0">
                        <app-typography variant="h6" class="py-2">{{ cardDetails.subTitle }}</app-typography>
                        <form>
                            <div class="row gy-4">
                                <div class="col-md-6 col-xl-6">
                                    <form-group-input type="text" label="Account Name"
                                        v-model="formFields.accountName" />
                                </div>
                                <div class="col-md-6 col-xl-6">
                                    <form-group-input type="number" label="Account Number"
                                        v-model="formFields.accountNumber" />
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <form-group-input type="number" label="Bank BSB" v-model="formFields.bsb" />
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <form-group-input type="text" label="Swift Code (Optional)"
                                        v-model="formFields.swiftCode" />
                                </div>
                                <div class="col-md-6 col-xl-4">
                                    <form-group-input type="text" label="Country" v-model="formFields.country" />
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="footer d-flex gap-3 justify-content-end pt-3 flex-wrap">
                        <reuseable-button @click="handleBack" outline textColor="#4682BE" class="px-5"
                            round="sm">Cancel</reuseable-button>
                        <reuseable-button @click="handleContinue" round="sm" class="px-5">Continue</reuseable-button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import { TitleActionBar, AppTypography, CapsuleField, FormGroupInput, ReuseableButton } from "@/components/index";
export default {
    components: {
        ReuseableButton,
        FormGroupInput,
        AppTypography,
        TitleActionBar,
        CapsuleField,
    },
    data() {
        return {
            header: {
                title: "Add Funds",
                subTitle: "Select your Fund plan for transaction"
            },
            cardDetails: {
                title: 'Bank Account Details',
                subTitle: 'Add Your Account Details',
            },
            formFields: {
                accountName: '',
                accountNumber: '',
                bsb: '',
                swiftCode: '',
                country: ''
            }
        }
    },
    methods: {
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
    border-radius: 10px;
    padding: 24px;
}

.card-header {
    border-radius: 10px 10px 0px 0px
}
</style>