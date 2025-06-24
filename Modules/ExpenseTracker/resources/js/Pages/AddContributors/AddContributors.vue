<template>
<auth-layout>
    <div class="container-fluid mb-4">
        <div class="row gy-4">
            <div class="col-sm-12">
                <title-action-bar :title="header.title" :isSubTitle="false" isActionButtons="true" btnRound="md"
                    :isPrimaryBtn="false" :isSecondaryBtn="false" :isUserName="false" :isDivider="true"
                    textVariant="text-38" textWeight="600" :primaryButtonText="header.priBtnText"
                    @primary-click="handleAddNewVisit" />
            </div>
            <div class="col-lg-8 offset-lg-2">
                <multiple-contributors @cancel="handleCancel" />
            </div>
            <div class="col-sm-12">
                <common-footer :isSaveBtn="true" :isContinueBtn="false" @back="handleBackClick"
                    @saveBtn="handleSaveClick" />
            </div>
        </div>
        <add-new-visit :formData="FormData" :isVisible="addNewVisit" title="Add New Visit"
            subTitle="Kindly fill in the visit information, amount, and due date sections to facilitate effective record-keeping."
            @update:isVisible="addNewVisit = $event">
        </add-new-visit>

<!--        <button class="btn btn-danger" @click="handleInviteMemberPay">invite member pay</button>-->
<!--        <invite-member-pay :formData="FormData" :isVisible="inviteMemberPay" title="Invite Member to pay easily"-->
<!--            @update:isVisible="inviteMemberPay = $event">-->
<!--        </invite-member-pay>-->
    </div>
</auth-layout>
</template>

<script>
import { TitleActionBar, CommonFooter, MultipleContributors, AddNewVisit, InviteMemberPay } from "@/components/index";


export default {
    computed: {
        addNewVisit() { return AddNewVisit },
        inviteMemberPay() { return InviteMemberPay }
    },

    components: {
        TitleActionBar,
        CommonFooter,
        MultipleContributors,
        AddNewVisit,
        InviteMemberPay
    },
    data() {
        return {
            header: {
                title: "Add Contributors",
                priBtnText: "Add New Visits"
            },
            FormData: {},
            addNewVisit: false,
            inviteMemberPay: false
        }
    },

    methods: {
        handleAddNewVisit() {
            this.addNewVisit = !this.addNewVisit;
        },
        handleInviteMemberPay() {
            this.inviteMemberPay = !this.inviteMemberPay;
        },
        handleSaveClick() {
            this.$router.push('')
        },
        handleBackClick() {
            window.history.back()
        },
      handleCancel() {
            window.history.back()
        },
    }
}
</script>

<style scoped>
.file {
    border: 1px dashed #4682BE;
    background-color: #F6F9FD;
    place-items: center;
    border-radius: 4px;
    height: 326px;
    width: 457px;
    display: grid;
}

.file ::-webkit-file-upload-button {
    visibility: hidden;
}

.file::after {
    content: 'Upload your Bill';
    display: inline-block;
    color: #4682BE;
    font-weight: 700;
    font-size: 24px;
}

.file:hover::before {
    border-color: black;
}

.file:active::before {
    background: -webkit-linear-gradient(top, #e3e3e3, #f9f9f9);
}
</style>