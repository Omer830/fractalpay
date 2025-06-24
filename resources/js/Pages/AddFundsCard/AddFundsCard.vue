<template>
    <div class="container-fluid">
        <div class="row gy-4">
            <div class="col-lg-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title"
                    :subTitle="header.subTitle" :isActionButtons="false" :isUserName="false" :isDivider="true" />
            </div>
            <div class="col-lg-12">
                <add-funds-card />
            </div>
        </div>
    </div>
</template>

<script>
import { TitleActionBar, AddFundsCard } from "@/components/index";
export default {
    components: {
        TitleActionBar,
        AddFundsCard
    },
    data() {
        return {
            header: {
                title: "Add Funds",
                subTitle: "Select your Fund plan for transaction"
            }
        }
    },
}
</script>

<style scoped>
/* Your component-specific styles go here */
</style>