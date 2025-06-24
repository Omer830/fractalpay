<template>
    <div class="container-fluid mb-4">
        <div class="row gy-4">
            <div class="col-lg-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title"
                    :subTitle="header.subTitle" :isActionButtons="true" :isUserName="false" :isPrimaryBtn="true"
                    :isDivider="true" :primaryButtonText="header.priBtnText" />
            </div>
            <div class="col-lg-12">
                <summary-main-card />
            </div>
            <div class="col-lg-6">
                <summary-card class="h-100" mainTitle="Payers Details" firstHeading="Name" secondHeading="City Hospital"
                    :detailsArray="payersArray" />
            </div>
            <div class="col-lg-6">
                <summary-card class="h-100" mainTitle="Payment Method" firstHeading="Method" secondHeading="Card"
                    :detailsArray="paymentArray" />
            </div>
        </div>
    </div>
</template>
<script>
import {
    TitleActionBar, SummaryCard, SummaryMainCard
} from "@/components/index.js";
export default {
    components: {
        SummaryMainCard,
        TitleActionBar,
        SummaryCard,
    },
    data() {
        return {
            header: {
                title: "Summary",
                subTitle: "See your bill and BPAY summary",
                priBtnText: "Report"
            },
            payersArray: [
                { title: "frequency", detail: "Monthly" },
                { title: "Name", detail: "Hammad Sheikh" },
                { title: "Type", detail: "Contribution Recieved" }
            ],
            paymentArray: [
                { title: "Card Number", detail: "**** 1234" },
                { title: "Expiry", detail: "12/30" }
            ]
        }
    }
}
</script>
<style scoped></style>