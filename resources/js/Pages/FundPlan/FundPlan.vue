<template>
    <div class="container-fluid">
        <div class="row gy-4">
            <div class="col-lg-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title"
                    :subTitle="header.subTitle" :isActionButtons="false" :isUserName="false" :isDivider="true" />
            </div>
            <div class="col-lg-6 offset-lg-3">
                <div class="card">
                    <div class="header">
                        <app-typography variant="h4" fontWeight="500">{{ cardDetails.title }}</app-typography>
                        <app-typography variant="body3" color="#76848D" fontWeight="300">
                            {{ cardDetails.subTitle }}
                        </app-typography>
                    </div>
                    <hr />
                    <div class="capsule-wrapper" v-for="(item, index) in capsulesArray" :key="index">
                        <capsule-field capPadding="15px" :label="item.title" :image-source="item.icon" :width="41"
                            :height="41" />
                    </div>
                    <div>
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" /> &nbsp;
                        <label class="form-check-label" for="flexCheckDefault">
                            <app-typography variant="body3" color="#4682BE">{{ cardDetails.default }}</app-typography>
                        </label>
                    </div>
                    <div class="btn-wrapper d-flex gap-3 justify-content-center my-4 flex-wrap">
                        <reuseable-button @click="handleCancel" class="px-5" outline
                            textColor="#4682BE">Cancel</reuseable-button>
                        <reuseable-button class="px-5">Confirm</reuseable-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { TitleActionBar, AppTypography, CapsuleField, ReuseableButton } from "@/components/index";
export default {
    components: {
        ReuseableButton,
        AppTypography,
        TitleActionBar,
        CapsuleField,
    },
    data() {
        return {
            header: {
                title: "Select your Fund plan",
                subTitle: "Select your Fund plan for transaction"
            },
            cardDetails: {
                title: 'Select your Fund Plan',
                subTitle: 'Select your Payment Method for transaction',
                default: 'Set as default'
            },
            capsulesArray: [
                { icon: 'bank-icon', title: 'Wallet' },
                { icon: 'credit-card-icon', title: 'Credit card' },
                { icon: 'bank-icon', title: 'Bank' },
            ],
        }
    },
    methods: {
        handleCancel() {
            this.$router.push("./investment-dashboard")
        }
    }
}
</script>

<style scoped>
.card {
    box-shadow: 0px 4.92px 9.83px 0px #00000014;
    box-shadow: 0px 0px 2.46px 0px #0000000A;
    border-radius: 6px;
    padding: 32px;
}

hr {
    color: #4682BE;
}
</style>