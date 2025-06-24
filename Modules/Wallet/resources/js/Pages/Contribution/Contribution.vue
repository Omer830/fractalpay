<template>
  <auth-layout>
  <div class="container-fluid mb-4">
        <div class="row gy-4">
            <div class="col-lg-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title" :isSubTitle="false"
                    :isActionButtons="false" :isUserName="false" :isDivider="true" />
            </div>
            <div class="col-lg-12">
                <invitation-method
                     />
            </div>
            <div class="col-lg-12">
                <common-footer @back="goBackHandler" :isContinueBtn="false"  />
            </div>
        </div>
    </div>
  </auth-layout>
</template>
<script>
import { TitleActionBar, InvitationMethod, CommonFooter } from "@/components/index";
export default {
    components: {
        TitleActionBar,
        InvitationMethod,
        CommonFooter
    },
    data() {
        return {
            header: {
                title: "Contribution",
            },
        }
    },
    methods: {
        goBackHandler() {
            window.history.back()
        }
    }
}
</script>

<style scoped></style>