<template>
    <div class="container-fluid">
        <div class="row gy-4">
            <div class="col-lg-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title" :isSubTitle="false"
                    :isActionButtons="false" :isUserName="false" :isDivider="true" />
            </div>
            <div class="col-lg-6 offset-lg-3">
                <div class="card">
                    <div class="card-header bg-white text-center">
                        <app-typography variant="h4" fontWeight="500">{{ cardDetails.title
                            }}</app-typography>
                        <app-typography variant="body2" color="#6E6E6E">
                            {{ cardDetails.subTitle }}
                        </app-typography>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="row gy-4">
                                <div class="col-12">
                                    <form-group-input type="text" label="Account Name"
                                        v-model="formFields.accountName" />
                                </div>
                                <div class="col-12">
                                    <form-group-input type="number" label="Account Number"
                                        v-model="formFields.accountNumber" />
                                </div>
                                <div class="col-12">
                                    <form-group-input type="number" label="Bank BSB" v-model="formFields.bsb" />
                                </div>
                                <div class="col-12">
                                    <form-group-input type="text" label="Swift Code (Optional)"
                                        v-model="formFields.swiftCode" />
                                </div>
                                <div class="col-12">
                                    <form-group-input type="text" label="Country" v-model="formFields.country" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <common-footer @back="handleBack" @continue="handleContinue" />
            </div>
        </div>
    </div>
</template>

<script>
import { TitleActionBar, AppTypography, CapsuleField, FormGroupInput, CommonFooter } from "@/components/index";
export default {
    components: {
        FormGroupInput,
        AppTypography,
        CommonFooter,
        TitleActionBar,
        CapsuleField,
    },
    data() {
        return {
            header: {
                title: "Bank Account Details",
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
    border-radius: 10px
}

.card-header {
    padding: 33px;
    border-radius: 10px 10px 0px 0px
}

.card-body {
    padding: 26px 87px;
}

@media screen and (max-width: 778px) {
    .card-body {
        padding: 20px;
    }
}
</style>