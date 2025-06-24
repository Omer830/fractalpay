<template>
  <div v-if="loading" class="loader-main">
    <div class="loader"></div>
  </div>
  <base-modal
      :isVisible="isVisible"
      :title="title"
      :subTitle="subTitle"
      :modalWidth="'856px'"
      iconBgColor="#205C40"
      @SUBMIT="$emit('FORM_SUBMIT', form)"
      :footerBtnStatus="footerBtnStatus"
      @update:isVisible="$emit('update:isVisible', $event)">

    <div v-if="contributorsListStatus" class="row gy-3">
      <div class="col-sm-12">
        <form-group-input
            type="text"
            label="Reason of Visit (20 Characters)"
            maxlength="20"
            v-model="form.visit_reason"
            :value="form.visit_reason"
            :required="true"
            borderRadiusClass="border-sm" />
      </div>
      <div class="col-sm-12">
        <textarea
          class="textarea"
          rows="4"
          placeholder="Visit Details (Long Text)"
          v-model="form.visit_details"
        />

      </div>
      <div class="col-lg-6">

        <form-group-input
            type="text"
            label="Provider Number"
            v-model="form.provider_number"
            :value="form.provider_number"
            :required="true"
            borderRadiusClass="border-sm" />

      </div>
      <div class="col-lg-6">
        <form-group-input
            type="text"
            label="Doctor Name"
            v-model="form.name"
            :value="form.name"
            borderRadiusClass="border-sm" />


      </div>

      <div class="col-lg-6">
        <form-group-input
            type="text"
            label="Hospital/Clinic Name"
            v-model="form.organisation"
            :value="form.organisation"

            borderRadiusClass="border-sm" />

        <div class="contributors d-flex align-items-center gap-2" v-if="form.contributorsIds?.length === 0">
          <image-svg width="12px" height="12px" name="plus-rounded-success-icon" />
          <app-typography variant="body1" color="#6EC392" class="py-1 pointer" @click="handleAddContributors">
            Add Contributors
          </app-typography>
        </div>
        <div v-else class="contributors d-flex align-items-center mt-3">
          <div v-for="(contributor, index) in selectedContributors" :key="index" class="contributor-item">
            <div v-if="!contributor.avatarUrl" class="avatar-initials" :style="{ backgroundColor: getColor(index) }">
              {{ getInitials(contributor.name) }}
            </div>
            <img v-else :src="contributor.avatarUrl" alt="Contributor Avatar" class="avatar rounded-circle" />
          </div>
<!--          <contributor-avatar :contributors="selectedContributors" />-->
          <div class="contributor-item add-contributor" @click="handleAddContributors">
            <span>+</span>
          </div>
        </div>

      </div>
      <div class="col-lg-6">
        <app-select type="text"
                    label="Visit Type"
                    :height="25"
                    :width="25"
                    borderRadiusClass="border-sm"
                    :options="visitType"
                    optionLabel="name"
                    optionValue="name"
                    :required="true"
                    v-model="form.visit_type"
                    :value="form.visit_type" />
      </div>
    </div>
    <div v-else>

<!--    <multiple-contributors-->
<!--        ref="contributorsComponent"-->
<!--        :contributorsArray="contributorsArray"-->
<!--        @cancel="handleCancel"-->

<!--        @save-contributors="handleSaveContributors"-->

<!--    />-->
      <common-footer buttonTitle="Save" :isBackBtn="false" @continue="userSave"></common-footer>
    </div>
    <contributors-list-modal
        title="Select Contributors"
        :subTitle="subTitle"
        :form-data="formDatas"
        :is-visible="contributorsModalStatus"
        :visitType="visitType"
        @FORM_SUBMIT="handleContributorsSubmit"
        :contributorsArray="contributorsArray"
        v-model:is-visible="contributorsModalStatus"
    />
<!--    <add-new-visit-modal :form-data="formData" @update:formData="updateFormData" />-->

  </base-modal>
</template>

<script>
import BaseModal from "./BaseModal.vue";
import AddNewVisitModal from "../AddNewVisitModal/addNewVisitModal.vue";
import AppSelect from "@components/Select/appSelect.vue";
import ImageSvg from "../ImageSvg/imageSvg.vue";
import {FormGroupInput} from "@components";
import AppTypography from "@components/Typography/appTypography.vue";
import {  MultipleContributors,  } from "@/components/index";
import CommonFooter from "@components/CommonFooter/commonFooter.vue";
import ContributorAvatar from "../ContributorAvatar/ContributorAvatar.vue";
import ContributorsListModal from "@components/Modal/ContributorsListModal.vue";
export default {
  components: {
    ContributorsListModal,
    CommonFooter,
    AppTypography, FormGroupInput, ImageSvg, AppSelect,
    BaseModal,
    AddNewVisitModal,
    MultipleContributors,
    ContributorAvatar
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
      contributorsModalStatus:false,
      contributorsIds: [],
      contributorsList: [],
      formDatas: this.$inertia.form({

        contributorsIds: [],

      }),


    };
  },
  watch: {
    isVisible(newValue) {
      if (newValue) {
        this.resetForm();
      }
    }
  },

  created() {
    this.debouncedGetProviderDetails = this.debounce(this.getProviderDetails, 1000);
  },

  computed: {
    selectedContributors() {
      return this.contributorsArray.filter(contributor =>
          this.form.contributorsIds.includes(contributor.id)
      );
    }
  },

  methods: {

    resetForm() {
      this.formDatas.contributorsIds = []
      this.form.contributorsIds = []
    },
    debounce(fn, delay) {
      let timeoutID;
      return function (...args) {
        if (timeoutID) {
          clearTimeout(timeoutID);
        }
        timeoutID = setTimeout(() => {
          fn.apply(this, args);
        }, delay);
      };
    },
    getProviderDetails() {
      if (!this.form.provider_number) {
        this.$toast('Please enter a provider number', 'error');
        return;
      }
      this.loading = true
      this.$inertia.post('/expense-tracker', {
        provider_number: this.form.provider_number
      }, {
        preserveState: true,
        onSuccess: (page) => {
          this.providerDetails = page.props.providerDetails;
          if(this.providerDetails){

            this.form.name = this.providerDetails.name;
            this.form.provider_id = this.providerDetails.id;
            this.form.organisation = this.providerDetails.organisation.name;

          } else {

            this.form.name = '';
            this.form.provider_id = '';
            this.form.organisation = '';
            this.$toast('Provider details not found', 'error');

          }
          this.loading = false
        },
        preserveScroll: true,
        onError: (error) => {
          this.showErrors(error);

          this.loading = false
        }
      });
    },
    showErrors(errors) {
      if (errors && Object.keys(errors).length > 0) {
        Object.keys(errors).forEach(key => {
          this.$toast(`Error: ${errors[key]}`, 'error');
        });
      }
    },
    handleAddContributors() {
      this.contributorsModalStatus = true
      // this.footerBtnStatus = false
      // this.$inertia.visit('add-contributors')
    },
    handleCancel(){
      this.contributorsModalStatus = true
      // this.footerBtnStatus = true
    },
    handleContributorsSubmit(selectedIds) {
      console.log(JSON.stringify(selectedIds), 'was')
      this.form.contributorsIds = selectedIds.contributorsIds
      this.contributorsModalStatus = false


    },
    userSave() {
      const selectedContributorIds = this.$refs.contributorsComponent.filteredContributors
          .filter(contributor => contributor.selected)
          .map(contributor => contributor.id);
      if (selectedContributorIds.length === 0) {
        this.$toast('Please select at least one contributor', 'error');
        return;
      }
      this.form.contributorsIds = selectedContributorIds;
      this.contributorsListStatus = true;
      this.footerBtnStatus = true;
      console.log('Selected contributor IDs:', JSON.stringify(this.form.contributorsIds));

    },
    handleSaveContributors(selectedContributorIds) {
      // Update the form with the selected contributor IDs
      this.form.contributorsIds = selectedContributorIds;
      // Return back to the previous modal view
      this.contributorsListStatus = true;
      this.footerBtnStatus = true;
    },
    getInitials(name) {
      if (!name) return '';
      const nameParts = name.split(' ');
      const initials = nameParts.map(part => part[0]).join('').toUpperCase();
      return initials.slice(0, 2); // Display up to two initials
    },
    getColor(index) {
      // Define a set of colors to rotate through
      const colors = ['#FF8A80', '#FFD180', '#CFD8DC', '#B2DFDB', '#FFAB91', '#FFCC80'];
      return colors[index % colors.length];
    }
  }
}
</script>

<style scoped>

.textarea{
  display: block;
  width: 100%;
  padding: .375rem .75rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: var(--bs-body-color);
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background-color: var(--bs-body-bg);
  background-clip: padding-box;
  border: var(--bs-border-width) solid var(--bs-border-color);
  border-radius: var(--bs-border-radius);
  transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
  resize: none;
}
.contributor-item {
  display: flex;
  align-items: center;
  margin-right: 8px;
}

.contributor-item {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: -16px;
  border: 5px solid white;
  border-radius: 50%;
  cursor: pointer;
  width: 60px;
  height: 55px;
}

.avatar {
  width: 60px;
  height: 55px;
  border-radius: 50%;
  object-fit: cover;
}

.avatar-initials {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
  font-weight: bold;
  color: #ffffff;
  background-color: #ccc;
}

.add-contributor {

  border-radius: 50%;
  background-color: #e0f2f1;
  display: grid;
  place-items: center;
  font-size: 1.5rem;
  color: #26a69a;
  cursor: pointer;
}
</style>
