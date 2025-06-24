<template>
    <base-modal :isVisible="isVisible" :title="title" :subTitle="subTitle" :modalWidth="'500px'" iconBgColor="#205C40"
        buttonTitle="Send Invitation" @SUBMIT="$emit('FORM_SUBMIT', form)" :isBackBtn="false"
        @update:isVisible="$emit('update:isVisible', $event)">
<!--        <invite-members-to-pay />-->
      <div class="col-sm-12">
        <form-group-input type="email" label="Email Address"
                          v-model="form.email"
                          :value="form.email"
                          borderRadiusClass="border-sm" />
      </div>
    </base-modal>
</template>

<script>
import InviteMembersToPay from "../InviteMembersToPay/inviteMembersToPay.vue";
import BaseModal from "./BaseModal.vue";
import {FormGroupInput} from "@components";

export default {
    components: {
      FormGroupInput,
        BaseModal,
        InviteMembersToPay
    },
    props: {
        isVisible: Boolean,
        title: {
            type: String,
            required: true,
            default: '...'
        },
        subTitle: {
            type: String,
            default: ''
        },
        formData: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            form: this.formData,
            error: {
                status: false,
                message: ""
            },
            options: {
                type: Array,
                required: true
            }
        };
    },
    watch: {
        isVisible(newValue) {
            if (newValue) {
                this.resetForm();
            }
        }
    },
    methods: {
        closeModal() {
            this.$emit("close-modal-event");
        },
        resetForm() {
            this.form = {
                ...this.formData
            };
        },
    }
}
</script>

<style scoped></style>
