<template>
    <div class="container mb-4">
        <div class="row gy-4">
            <div class="col-sm-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title" :isSubTitle="false"
                    :isActionButtons="false" :isUserName="false" :isDivider="true" />
            </div>
            <div class="col-md-12">
                <wallet-summary cardTitle="Summary" />
            </div>
        </div>
    </div>
</template>

<script>
import { TitleActionBar, WalletSummary } from "@/components/index";
export default {
    components: {
        WalletSummary,
        TitleActionBar,
    },
    data() {
        return {
            header: {
                title: "Wallet Summary"
            }
        }
    },
    methods: {
        handleBack() {
            alert('back')
        },
        handleContinue() {
            this.isContinue = true
        }
    },
}
</script>

<style scoped></style>