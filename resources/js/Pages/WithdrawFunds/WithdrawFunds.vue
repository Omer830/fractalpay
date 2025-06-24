<template>
    <div class="container-fluid">
        <div class="row gy-4">
            <div class="col-lg-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title"
                    :subTitle="header.subTitle" :isActionButtons="false" :isUserName="false" :isDivider="true" />
            </div>
            <div class="col-lg-12">
                <withdraw-funds />
            </div>
        </div>
    </div>
</template>

<script>
import { TitleActionBar, WithdrawFunds } from "@/components/index";
export default {
    components: {
        TitleActionBar,
        WithdrawFunds
    },
    data() {
        return {
            header: {
                title: "Withdraw Funds",
                subTitle: "Withdraw Funds to your Farctal Pay Wallet"
            }
        }
    },
}
</script>

<style scoped>
/* Your component-specific styles go here */
</style>