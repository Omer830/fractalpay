<template>
    <div class="container-fluid mb-4">
        <div v-if="isContinue" class="row gy-4">
            <div class="col-lg-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title" :isSubTitle="false"
                    :isActionButtons="false" :isUserName="false" :isDivider="true" :isHelp="true" />
            </div>
            <div class="col-lg-6 offset-lg-3">
                <add-fund-modal modalTitle="All Set" :detailsArray="modalDetails" :isSubTitle="false"
                    :isSecondaryBtn="false" />
            </div>
        </div>
        <div v-else class="row gy-4">
            <div class="col-lg-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title" :isSubTitle="false"
                    :isActionButtons="false" :isUserName="false" :isDivider="true" :isHelp="true" />
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3" v-for="(item, index) in cardsArray" :key="index">
                <dividend-card :title="item.title" :icon="item.icon" :percentage="item.percent" />
            </div>
            <div class="col-lg-12">
                <dividend-summary-card :cardTitle="summaryCard.title" :cardDesc="summaryCard.description"
                    @back="handleBack" @continue="handleContinue" />
            </div>
        </div>
    </div>
</template>

<script>
import { TitleActionBar, DividendCard, DividendSummaryCard, AddFundModal } from "@/components/index";
export default {
    components: {
        DividendSummaryCard,
        AddFundModal,
        TitleActionBar,
        DividendCard,
    },
    data() {
        return {
            header: {
                title: "Setup Dividend Distribution"
            },
            cardsArray: [
                { title: 'Add to Wallet', icon: 'wallet-outline-icon', percent: '10' },
                { title: 'Re Invest', icon: 'investment-icon', percent: '10' }
            ],
            summaryCard: {
                title: 'Summary',
                description: 'Following contributors will receive your dividend automatically as soon Zyanza issue to your account.'
            },
            modalDetails: [
                { title: 'Dividend Setup ID', detail: 'Fractal pay Wallet' },
                { title: 'Next Dividend Payout Date', detail: '$500' },
            ],
            isContinue: false
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