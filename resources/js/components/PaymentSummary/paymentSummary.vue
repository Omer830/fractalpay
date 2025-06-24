<template>
    <div class="card">
        <div class="card-header text-center bg-white">
            <app-typography variant="h4" fontWeight="500">
                {{ cardTitle }}
            </app-typography>
        </div>
        <div class="card-body">
            <div v-for="(item, i) in detailsArray" :key="i"
                class="d-flex flex-wrap justify-content-between py-3 border-bottom">
                <app-typography variant="body1" fontWeight="500" color="#76848D">
                    {{ item.title }}
                </app-typography>
                <app-typography variant="body1" fontWeight="500" :color="item.defaultColor ? '#0C266C' : '#3A84C6'">
                    {{ item.info }}
                </app-typography>
            </div>
        </div>
        <div class="card-footer bg-white border-0 d-flex gap-3 align-items-center justify-content-center flex-wrap">
            <reuseable-button @click="handleCancel" v-if="isSecondaryBtn" outline round="md" class="px-5"
                textColor="#4682BE">{{ secondaryText
                }}</reuseable-button>
            <reuseable-button @click="handleConfirm" v-if="isPrimaryBtn" round="md" class="px-5">{{ primaryText
                }}</reuseable-button>
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
            default: 'Details'
        },
        detailsArray: {
            type: Array,
            default: () => [
                { title: 'Receiver', info: "Harold Johnson", defaultColor: true },
                { title: 'Card Number', info: "****5336", defaultColor: false },
                { title: 'Contribution', info: "$200 / Month", defaultColor: true },
                { title: 'CVV', info: "***", defaultColor: false },

            ]
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
            default: 'Confirm'
        },
        secondaryText: {
            type: String,
            default: 'Cancel'
        },
    },
    methods: {
        handleCancel() {
            this.$emit('cancel')
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
    padding: 15px;
}

.card-footer {
    border-radius: 0px 0px 12px 12px;
}
</style>