<template>
    <div class="biller-wrapper">
        <logo-header @back-clicked="handleBack" />
        <div v-if="!isConfirm" class="container">
            <div class="modal-wrapper">
                <app-typography variant="h4" fontWeight="700">
                    {{ title }}
                </app-typography>
                <app-typography variant="body3" color="#76848D" fontWeight="300" class="mt-2">{{ desc
                    }}</app-typography>
                <hr />
                <div class="form-wrapper">
                    <form>
                        <div class="row d-flex justify-content-center gap-3 align-items-center">
                            <div class="col-sm-12">
                                <form-group-input type="text" label="Biller Code" v-model="billerData.billerCode" />
                            </div>
                            <div class="col-sm-12">
                                <form-group-input type="text" label="Reference Number" v-model="billerData.refNo" />
                            </div>
                            <div class="col-sm-12 d-flex justify-content-center gap-3 flex-wrap btn-wrap">
                                <reuseable-button @click="handleCancel" outline class="w-25"
                                    textColor="#4682BE">Cancel</reuseable-button>
                                <reuseable-button @click="hanldeContinue" class="w-25">Confirm</reuseable-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div v-else>
            <success-alert :title='alertTitle' :desc='alertDesc' :isBackButton='true' />
        </div>
    </div>
</template>

<script>
import { LogoHeader, AppTypography, FormGroupInput, ReuseableButton } from "@/components/index";
import SuccessAlert from "../SuccessAlert/successAlert.vue";
export default {
    components: {
        AppTypography,
        FormGroupInput,
        ReuseableButton,
        LogoHeader,
        SuccessAlert,
    },
    props: {
        title: {
            type: String,
            default: 'Enter Your Biller Details'
        },
        desc: {
            type: String,
            default: 'Select your Payment Method for transaction'
        },
    },
    data() {
        return {
            billerData: {
                billerCode: '',
                refNo: ''
            },
            isConfirm: false,
            alertTitle: 'Successfully Added ',
            alertDesc: 'Your Bank Details Added Successfully',
        }
    },
    methods: {
        handleBack() {
            this.isConfirm = false
        },
        handleCancel() {
            this.$emit('cancel')
        },
        hanldeContinue() {
            this.isConfirm = true
        }
    }
}
</script>

<style scoped>
.modal-wrapper {
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    background-color: #ffff;
    border-radius: 5px;
    max-width: 552px;
    padding: 32px;
    margin: auto;
}

.btn-wrap {
    margin-top: 41px;
}
</style>