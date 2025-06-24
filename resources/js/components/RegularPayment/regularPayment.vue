<template>
    <div class="card">
        <div class="header border-bottom">
            <app-typography variant="h4" fontWeight="500">{{ cardTitle }}</app-typography>
            <app-typography variant="body3" color="#777E90" class="pb-3">{{ cardSubTitle }}</app-typography>
        </div>
        <div class="card-body">
            <div class="payment-details d-flex justify-content-between pb-3 flex-wrap"
                v-for="(item, index) in paymentDetails" :key="index">
                <app-typography variant="body1" color="#646464">{{ item.desc }}</app-typography>
                <div class="amount">
                    <app-typography variant="body3" :color="item.disable === true ? '#BEBEBE' : '#4682BE'">{{
                        "$" + item.amount
                        }}</app-typography>
                </div>

            </div>
            <div class="content">
                <app-typography variant="h4" fontWeight="500">
                    How much do you want to contribute
                </app-typography>
            </div>
            <div class="body d-flex flex-column justify-content-center align-items-center mt-5">
                <div class="field-wrapper text-center my-2">
                    <app-typography variant="body3" color="#777E90" fontWeight="500" class="mb-2">
                        Input field for amount frequency
                    </app-typography>
                    <div class="d-flex flex-wrap">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1"
                                value="option1">
                            <label class="form-check-label" for="inlineRadio1">
                                <app-typography color="#4682BE" fontWeight="600" variant="body1">Add
                                    Manually</app-typography>
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2"
                                value="option2">
                            <label class="form-check-label" for="inlineRadio2">
                                <app-typography color="#4682BE" fontWeight="600" variant="body1">Automatic
                                    Scanning</app-typography>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="text-wrapper d-flex gap-1 flex-wrap">
                    <app-typography variant="h4" color="#3A84C6" fontWeight="500">Drag to</app-typography>
                    <app-typography variant="h4" color="#777E90" fontWeight="500"> select Amount</app-typography>
                </div>
                <div class="range-wrapper w-100 mt-5 mb-2 d-flex justify-content-center flex-column align-items-center">
                    <div class="tooltip-container my-4">
                        <div class="tooltip-custom">
                            <app-typography variant="body1" color="#ffff" fontWeight="500">{{ totalAmount + `$`
                                }}</app-typography>
                        </div>
                    </div>
                    <input type="range" class="form-range " slider-tooltip="always" id="customRange1">
                </div>
                <div class="d-flex justify-content-around align-items-center w-100 my-4 flex-wrap gap-3">
                    <div class="tag px-3 py-2" v-for="(item, i) in buttonsGroup" :key="i">
                        {{ item.name }}
                    </div>
                </div>
            </div>
        </div>
        <div v-if="isFooterBtns"
            class="card-footer bg-white border-0 d-flex gap-3 align-items-center justify-content-center flex-wrap">
            <reuseable-button v-if="isSecondaryBtn" outline round="md" class="px-5" textColor="#4682BE">{{ secondaryText
                }}</reuseable-button>
            <reuseable-button v-if="isPrimaryBtn" round="md" class="px-5">{{ primaryText }}</reuseable-button>
        </div>
    </div>
</template>

<script>
import { AppTypography, ReuseableButton } from "@/components/index";
export default {
    components: {
        AppTypography,
        ReuseableButton
    },
    props: {
        cardTitle: {
            type: String,
            default: 'Regular Payment'
        },
        cardSubTitle: {
            type: String,
            default: 'Outstanding expenses '
        },
        paymentDetails: {
            type: Array,
            default: () => [
                { desc: "Harold is paying  [FREQUENCY]  his Medical Bills & Insurance premium", amount: "1000.00", disable: true },
                { desc: "Currently Harold has  amount of bills pending payment", amount: "250.00", disable: true },
                { desc: "Your Contribution [FREQUENCY]", amount: "50.00", disable: false }
            ]
        },
        totalAmount: {
            type: Number,
            default: 50
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
            default: 'Pay Now'
        },
        secondaryText: {
            type: String,
            default: 'Cancel'
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
        buttonsGroup: {
            type: Array,
            default: () => [
                { name: 'Week', disable: true },
                { name: 'Fortnight', disable: true },
                { name: 'Month', disable: false },
                { name: 'Year', disable: true },
            ]
        }

    },
}
</script>

<style scoped>
.card {
    box-shadow: 0px 8px 16px 0px #00000014;
    box-shadow: 0px 0px 4px 0px #0000000A;
    border-radius: 12px;
    padding: 25px;
}

.amount {
    height: 34px;
    border-radius: 8px;
    border: 1px solid #BEBEBE;
    padding: 6px 28px;
    width: 111px;

}

.body {
    max-width: 526px;
    margin: auto;
}

.tag {
    border-radius: 50px;
    border: 1px solid #565656
}

.card-footer {
    padding: 23px;
}

.tooltip-container {
    position: relative;
    display: inline-block;
    cursor: pointer;
    font-size: 16px;
}

.tooltip-custom {
    background-color: #3A84C6;
    max-width: 100px;
    padding: 15px 28px;
    border-radius: 50px;
    position: absolute;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1;

}

.tooltip-custom::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #3A84C6 transparent transparent transparent;
}

</style>