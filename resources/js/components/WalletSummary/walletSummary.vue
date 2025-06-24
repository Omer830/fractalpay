<template>
    <div class="card">
        <div class="header border-bottom">
            <app-typography variant="h4" fontWeight="500"> {{ cardTitle }}</app-typography>
        </div>
        <div class="body">
            <div v-for="(item, index) in summaryDetails" :key="index" class="d-flex gap-5">
                <div class="amount">
                    <app-typography variant="body1" fontWeight="500" color="#76848D" class="mb-3"> {{
                        item.title }}</app-typography>
                </div>
                <div class="amount">
                    <app-typography variant="body1" fontWeight="600" :color="item.textColor" class="mb-3">
                        {{ item.amount }}</app-typography>
                </div>
            </div>
        </div>
        <div class="footer border-top d-flex gap-3 justify-content-center flex-wrap">
            <reuseable-button v-if="isCancelBtn" @click="goBack" textColor="#4682BE" class="continue-btn px-5" outline
                round="md" btnHeight="62px">
                Cancel
            </reuseable-button>
            <reuseable-button v-if="isConfirmBtn" @click="goContinue" class="primary-btn  continue-btn px-5" round="md"
                btnHeight="62px">
                Confirm
            </reuseable-button>
        </div>
    </div>
</template>

<script>
import ReuseableButton from "../Button/reuseableButton.vue";
import AppTypography from "../Typography/appTypography.vue";

export default {
    components: {
        AppTypography,
        ReuseableButton,
    },
    props: {
        cardTitle: {
            type: String,
            default: 'card title'
        },
        summaryDetails: {
            type: Array,
            default: [
                { title: 'Wallet Amount', amount: '$525', textColor: '#36454F' },
                { title: 'Transferring', amount: '$500', textColor: '#3A84C6' },
                { title: 'Wallet After Transfer', amount: '$25', textColor: '#C63A3A' }

            ]
        },
        isCancelBtn: {
            type: Boolean,
            default: true,
        },
        isConfirmBtn: {
            type: Boolean,
            default: true,
        },
    },
    methods: {
        goBack() {
            this.$emit("cancel");
        },
        goContinue() {
            this.$emit("confirm");
        },
    }
}
</script>

<style scoped>
.card {
    border-radius: 12px;
    box-shadow: 0px 8px 16px 0px #00000014;
    box-shadow: 0px 0px 4px 0px #0000000A;
}

.header,
.body {
    padding: 25px 34px;
}

.footer {
    padding: 40px;
}

.amount {
    width: 200px;
}
</style>