<template>
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between flex-wrap">
            <app-typography variant="h4" fontWeight="500">{{ cardTitle }}</app-typography>
            <div class="btn-wrapper d-flex gap-3">
                <reuseable-button btnHeight="46px" outline @click="handleAddNew" textColor=" #4682BE" class="px-3"
                    round="sm" :imageWidth="14" :imageHeight="14" startIcon="plus-icon">Add New
                    Card
                </reuseable-button>
                <reuseable-button btnHeight="46px" outline @click="handleDelete" textColor=" #4682BE" class="px-3"
                    round="sm" :imageWidth="20" :imageHeight="20" startIcon="trash-icon">Delete Card</reuseable-button>
            </div>
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
                <div class="col-12 col-lg-6 zayanaza">
                    <zayanaza-card />
                </div>
                <div class="col-12 col-lg-6 zayanaza">
                    <zayanaza-card />
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex gap-3 justify-content-center">
                <reuseable-button outline @click="handleBack" textColor=" #4682BE" class="px-5"
                    round="md">Back</reuseable-button>
                <reuseable-button @click="handleConfirm" class="px-5" round="md">Confirm</reuseable-button>
            </div>
        </div>
    </div>
</template>

<script>
import AppTypography from '../Typography/appTypography.vue';
import ReuseableButton from '../Button/reuseableButton.vue';
import ZayanazaCard from '../ZyanazaCard/zyanazaCard.vue';
export default {
    components: {
        AppTypography,
        ReuseableButton,
        ZayanazaCard
    },
    props: {
        cardTitle: {
            type: String,
            default: "Transfer Amount"
        },
        inputLabel: {
            type: String,
            default: "Withdraw  Funds"
        },

    },
    methods: {
        handleBack() {
            this.$emit('back')
        },
        handleConfirm() {
            this.$emit('confirm')
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

.zayanaza {
    z-index: 1;
}
</style>