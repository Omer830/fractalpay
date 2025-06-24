<template>
  <div v-if="loading" class="loader-main">
    <div class="loader"></div>
  </div>
  <base-modal
      :isVisible="isVisible"
      :title="title"
      :subTitle="subTitle"
      buttonTitle="Save"
      :modalWidth="'856px'"
      iconBgColor="#205C40"
      @SUBMIT="$emit('FORM_SUBMIT', form)"
      :footerBtnStatus="footerBtnStatus"
      @update:isVisible="$emit('update:isVisible', $event)"
  >
    <div class="max-w-[300px]" @click="inviteFriendAndFamily">
    <reuseable-button outline width="75" textColor="#141519" round="lg">
      Invite Contributors
    </reuseable-button>
    </div>
    <div class="min-h-[300px] max-h[400px] overflow-auto">
      <!-- Select All and Search Section -->
      <div class="d-flex justify-between gap-2 my-3">
        <div>
          <input class="form-check-input py-2" type="checkbox" id="selectAllCheckbox" v-model="selectAll" @change="toggleSelectAll">
          <label class="form-check-label ml-3" for="selectAllCheckbox">Select All Contributors</label>
        </div>
        <div class="search">
          <input type="search" placeholder="Search" v-model="searchQuery" @input="filterContributors">
        </div>
      </div>

      <!-- No contributors available message -->
      <div v-if="filteredContributors.length === 0" class="empty-message">
        <app-typography variant="body1" color="#76848D">No contributors available to display.</app-typography>
      </div>

      <!-- Contributors List -->
      <div v-else>
        <div
          class="details-wrapper d-flex flex-wrap align-items-center justify-content-between py-2 pe-2"
          v-for="(item, index) in filteredContributors"
          :key="index"
        >
          <div class="user d-flex justify-between align-items-center gap-3 w-100">
            <div class="d-flex align-items-center gap-3">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="item.selected"
              @change="updateSelectAllStatus"
            />
            <image-svg width="36px" height="36px" :name="item.avatar" />
            <div>
              <app-typography variant="body1" color="#76848D">{{ item?.name }}</app-typography>
              <app-typography variant="body1" color="#76848D">
                Status: {{ item.invitation_status }}
              </app-typography>
            </div>
          </div>
            <!-- Display email based on conditions -->
            <app-typography
              v-if="item.invitation_status === 'pending' && item?.user?.email"
              variant="body1"
              color="#76848D"
            >
              {{ item?.user?.email }}
            </app-typography>
            <app-typography
              v-else-if="item.invitation_status === 'pending' && item?.email"
              variant="body1"
              color="#76848D"
            >
              {{ item?.email }}
            </app-typography>
          </div>
        </div>
      </div>
    </div>
    <InviteMemberPay
        title="Invite Contributors"
        :form-data="inviteUser"
        :is-visible="InvitationModalStatus"
        @FORM_SUBMIT="handleInviteContributorsSubmit"
        :visit_id="visit_id"
        v-model:is-visible="InvitationModalStatus"
    ></InviteMemberPay>
  </base-modal>

</template>


<script>
import BaseModal from "./BaseModal.vue";
import AddNewVisitModal from "../AddNewVisitModal/addNewVisitModal.vue";
import AppSelect from "@components/Select/appSelect.vue";
import ImageSvg from "../ImageSvg/imageSvg.vue";
import {FormGroupInput} from "@components";
import AppTypography from "@components/Typography/appTypography.vue";
import {  MultipleContributors,} from "@/components/index";
import CommonFooter from "@components/CommonFooter/commonFooter.vue";
import ReuseableButton from "@components/Button/reuseableButton.vue";
import InviteMemberPay from "@components/Modal/InviteMemberPay.vue";
import inviteFriendAndFamily from "@components/InviteFriendsAndFamily/inviteFriendAndFamily.vue";
export default {
  components: {

    InviteMemberPay,
    ReuseableButton,
    CommonFooter,
    AppTypography, FormGroupInput, ImageSvg, AppSelect,
    BaseModal,
    AddNewVisitModal,
    MultipleContributors
  },
  props: {
    isVisible: Boolean,
    title: {
      type: String,
      required: true,
      default: '...'
    },
    visit_id: {
      type: String,
      default: '...'
    },
    subTitle: {
      type: String,
      default: ''
    },
    formData: {
      type: Array,
      required: true
    },
    visitType:{
      type:Array,
      required: false,
    },
    contributorsArray:{
      type:Array,
      required: false,
    }
  },
  data() {
    return {
      form: this.formData,
      debouncedGetProviderDetails: null,
      loading: false,
      contributorsListStatus: true,
      footerBtnStatus: true,
      selectAll: false,
      searchQuery: "",
      filteredContributors: [],
      InvitationModalStatus: false,
      inviteUser: this.$inertia.form({
        email: [],
        bill_id:'',

      }),


    };
  },
  watch: {
    // isVisible(newValue) {
    //   if (newValue) {
    //     this.resetForm();
    //   }
    // }
    contributorsArray: {
      immediate: true,
      handler(newContributors) {
        this.filteredContributors = newContributors.map(contributor => ({...contributor}));
        this.updateSelectAllStatus();
      }
    }
  },
  created() {

  },

  computed: {


  },

  methods: {
    handleCancel() {
      this.$emit('cancel');
    },
    inviteFriendAndFamily() {
      this.InvitationModalStatus = true
    },
    handleInviteContributorsSubmit(newEmails){
      console.log(JSON.stringify(newEmails))
        this.loading = true
        this.inviteUser.email.push(newEmails.email) ;
        this.inviteUser.bill_id = this.visit_id
        this.inviteUser.post('/v1/invitation/sendEmails', {
          onSuccess: () => {
            this.$toast('Invitations sent successfully!', 'success');
            this.inviteUser.reset('emails');
            this.InvitationModalStatus = false;
            this.loading = false
          },
          onError: errors => {
            console.log('Error sending invitations:', errors);

          }
        });
    },
    toggleSelectAll() {
      this.filteredContributors.forEach(contributor => contributor.selected = this.selectAll);

      this.form.contributorsIds = this.filteredContributors
          .filter(contributor => contributor.selected)
          .map(contributor => contributor.id);
    },
    filterContributors() {
      const query = this.searchQuery.trim().toLowerCase();
      if (query === "") {
        this.filteredContributors = this.contributorsArray;
      } else {
        this.filteredContributors = this.contributorsArray.filter(contributor =>
            contributor.name.toLowerCase().includes(query) ||
            contributor.email.toLowerCase().includes(query)
        );
      }
      this.updateSelectAllStatus();
    },
    updateSelectAllStatus() {
      this.selectAll = this.filteredContributors.every(contributor => contributor.selected);


      this.form.contributorsIds = this.filteredContributors
          .filter(contributor => contributor.selected)
          .map(contributor => contributor.id);

      console.log(this.form);
    },
    getSelectedContributors() {
      return this.filteredContributors.filter(contributor => contributor.selected);
    }
  },
}
</script>

<style scoped>

.empty-message {
  text-align: center;
  color: #76848D;
  margin-top: 20px;
}
.search input{
  border: 1px solid #4682be;
  height: 30px;
  border-radius: 20px;
  padding: 0 10px 0 10px;
}
</style>
