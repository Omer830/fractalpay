<template>
    <div class="invite-wrapper">
        <logo-header  @back-clicked="handleBack" />
        <div v-if="!isSend" class="container">
            <div class="modal-wrapper">
                <div class="title d-flex gap-2">
                    <app-typography variant="h2" fontWeight="600">
                        {{ basicTitle }}
                    </app-typography>
                    <app-typography variant="h2" fontWeight="600" color="#3A84C6">
                        {{ coloredTitle }}
                    </app-typography>
                </div>

                <app-typography variant="body1" color="#76848D" class="my-2">{{ desc }}</app-typography>

                <div class="form-wrapper">
                    <form>
                        <div class="row d-flex justify-content-center gap-3 align-items-center">
                            <div class="col-sm-12">
                                <div class="mt-2" v-for="(email, index) in invitationData.emails" :key="index">
                                    <form-group-input
                                        type="email"
                                        label="Email Address"
                                        :value="invitationData.emails[index]"
                                        v-model="invitationData.emails[index]" />
                                </div>
                                <div class="btn-wrapper d-flex align-items-center  gap-2">
                                    <image-svg :width="width" :height="height" :name="btnIcon" />
                                    <app-typography variant="body1" color="#6EC392" fontWeight="500" class="my-3"
                                        @click="addEmailField">Add
                                        Members</app-typography>
                                </div>
                            </div>

                            <div class="col-sm-12 d-flex justify-content-center gap-3 flex-wrap actions-btn">
                                <reuseable-button @click="handleModalBack" outline class="w-25"
                                    textColor="#4682BE">Back</reuseable-button>
                                <reuseable-button @click="handleSend" class="w-25">Send</reuseable-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import { LogoHeader, AppTypography, FormGroupInput, ReuseableButton } from "@/components/index";
import SuccessAlert from "../SuccessAlert/successAlert.vue";
import ImageSvg from "../ImageSvg/imageSvg.vue";

export default {
    components: {
        AppTypography,
        ReuseableButton,
        FormGroupInput,
        LogoHeader,
        SuccessAlert,
        ImageSvg
    },
    props: {
        basicTitle: {
            type: String,
            default: "Invite"
        },
        coloredTitle: {
            type: String,
            default: "Friends and Family members?"
        },
        desc: {
            type: String,
            default: 'Invite Friends or Family'
        },
        btnIcon: {
            type: String,
            default: "add-round-icon",
        },
        width: {
            type: [String, Number],
            default: '16px',
        },
        height: {
            type: [String, Number],
            default: '16px',
        },
    },
    data() {
        return {
            invitationData: {
                emails: [''],
            },
            isSend: false,
            alertTitle: 'Successfully send ',
            alertDesc: 'Your invitation send Successfully',
        }
    },
    methods: {
        handleBack() {
          window.history.back()
            this.isSend = false
        },
      handleSend() {

        const validEmails = this.invitationData.emails.filter(email => email.trim() !== '');

        if (validEmails.length > 0) {
          this.$emit('send', validEmails);
          // this.isSend = true;
        } else {
          this.$toast('Please enter at least one valid email address', 'error');
        }
      },
        handleModalBack() {
            this.$emit('modal-back')
        },
        addEmailField() {
            this.invitationData.emails.push('');
        },
    }
}
</script>

<style scoped>
.modal-wrapper {
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    background-color: #ffff;
    border-radius: 5px;
    max-width: 770px;
    padding: 32px;
    margin: auto;
}

.actions-btn {
    margin-top: 41px;
}

.btn-wrapper {
    cursor: pointer;
}
</style>