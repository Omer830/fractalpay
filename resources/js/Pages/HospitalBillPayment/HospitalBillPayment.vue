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
                        <app-typography variant="h4" fontWeight="500" color="#3A84C6">{{ cardDetails.title
                            }}</app-typography>
                        <app-typography variant="body2" color="#6E6E6E">
                            {{ cardDetails.subTitle }}
                        </app-typography>
                    </div>
                    <div class="card-body">
                        <div class="capsule-wrapper" v-for="(item, index) in capsulesArray" :key="index">
                            <capsule-field capPadding="15px" :label="item.title" :image-source="item.icon" :width="41"
                                :height="41" />
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-12">
                <common-footer @back="handleBack" :isContinueBtn="false" />
            </div>
        </div>
    </div>
</template>

<script>
import { TitleActionBar, AppTypography, CapsuleField, CommonFooter } from "@/components/index";
export default {
    components: {
        AppTypography,
        CommonFooter,
        TitleActionBar,
        CapsuleField,
    },
    data() {
        return {
            header: {
                title: "Hospital Bill Payment",
            },
            cardDetails: {
                title: 'City Hospital Bill Payment Method',
                subTitle: 'Please select payment method to pay this bill',
            },
            capsulesArray: [
                { icon: 'bank-icon', title: 'Bank Account' },
                { icon: 'bpay-icon', title: 'BPay' },
            ],
        }
    },
    methods: {
        handleCancel() {
            this.$router.push("./investment-dashboard")
        },
        handleBack() {
            alert('back')
        },
    }
}
</script>

<style scoped>
.card {
    box-shadow: 0px 4.92px 9.83px 0px #00000014;
    box-shadow: 0px 0px 2.46px 0px #0000000A;
    border-radius: 6px
}

.card-header {
    padding: 33px;
}

.card-body {
    border-radius: 0px 0px 6px 6px;
    padding: 33px;
}
</style>