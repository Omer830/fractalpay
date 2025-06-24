<template>
  <div class="main-card bg-white">
    <div class="header d-flex flex-wrap align-items-center justify-content-between border-bottom pb-3">
      <app-typography variant="h3">{{ cardHeader }}</app-typography>
      <div class="close pointer" @click="handleCancel">
        <image-svg width="31px" height="31px" name="cross-icon"/>
      </div>
    </div>
    <div class="px-0 py-2">
      <form>
        <div class="row gy-4">

          <div class="col-lg-4">
            <app-select
                type="text"
                label="Visit"
                :height="25" :width="25"
                borderRadiusClass="border-sm"
                optionLabel="visit_reason"
                optionValue="id"
                :options="allVisits"
                v-model="formData.visit_id"
                :value="formData.visit_id"
            />
          </div>
          <div class="col-lg-2 pt-5">
            <div class="d-flex gap-2 pointer" @click="addNewVisit">
              <image-svg width="16px" height="16px" name="plus-icon"/>
              <app-typography variant="body3" color="#4682BE">Add New</app-typography>
            </div>
          </div>
          <div class="col-lg-6">
            <form-group-input
                type="text"
                label="Hospital/Clinic Name"
                v-model="formData.organisation"
                :value="formData.organisation"
                borderRadiusClass="border-sm"/>

          </div>
          <div class="col-lg-6">
            <!--            <form-group-input-->
            <!--                type="text"-->
            <!--                label="Bill Category"-->
            <!--                v-model="formData.category"-->
            <!--                :value="formData.category"-->
            <!--                borderRadiusClass="border-sm"/>-->
            <app-select
                type="text"
                label="Bill Category"
                :height="25"
                :width="25"
                borderRadiusClass="border-sm"
                :options="billCategory"
                v-model="formData.category"
                :value="formData.category"
                optionLabel="name"
                optionValue="name"
            />
          </div>
          <div class="col-lg-6">
            <form-group-input
                type="text"
                label="Amount"
                v-model="formData.amount"
                :value="formData.amount"
                borderRadiusClass="border-sm"/>
          </div>
          <div class="col-lg-6">
            <form-group-input
                type="text"
                label="Bill Description"
                v-model="formData.description"
                :value="formData.description"
                borderRadiusClass="border-sm"/>
          </div>
          <div class="col-lg-6">
            <form-group-input
                type="text"
                label="Invoice Number"
                v-model="formData.invoice_number"
                :value="formData.invoice_number"
                borderRadiusClass="border-sm"/>
          </div>
          <div class="col-lg-6">
            <app-typography variant="body1" class="control-label mb-2"> Bill due date
              <span class="required-asterisk">*</span>
            </app-typography>

            <el-date-picker
                v-model="formData.due_date"
                type="date"
                placeholder=""
                format="YYYY-MM-DD"
                value-format="YYYY-MM-DD"
                class="w-full custom-date-picker"
                :required="true"
                :clearable="false"
            />
          </div>

          <div class="col-lg-6 pt-5">
            <input
                class="form-check-input"
                type="checkbox"
                value=""
                id="flexCheckDefault"
                v-model="formData.already_paid"
                :value="formData.already_paid"
            /> &nbsp;
            <label class="form-check-label" for="flexCheckDefault">
              <app-typography variant="h6" color="#4682BE">Already Paid</app-typography>
            </label>
          </div>
          <div class="col-lg-12">
            <app-typography variant="h4">Uploaded Documents</app-typography>
          </div>
          <!-- Hidden File Input -->


          <div class="col-lg-6">

            <input
                type="file"
                ref="fileInput"
                class="d-none"
                @change="handleFileSelection"
            />

            <!-- Button to Trigger File Selection -->
            <div  v-if="!formData.billFile">
<!--              <div class="d-flex gap-2 pointer" @click="triggerFileUpload">-->
<!--                <image-svg width="16px" height="16px" name="plus-icon"/>-->
<!--                <app-typography variant="body3" color="#4682BE">Upload Bill</app-typography>-->
<!--              </div>-->
              <div class="card contributor-card" @click="triggerFileUpload">
                <div class="wrapper d-flex justify-content-between align-items-center gap-2 pointer">
                  <image-svg width="16px" height="16px" name="plus-rounded-success-icon"/>
                  <app-typography variant="body1" color="#6EC392">Upload Bill</app-typography>
                </div>
              </div>
            </div>

            <div v-else class="card">
              <div class="wrapper d-flex justify-content-between align-items-center">
                <div class="img-wrapper d-flex gap-3">
                  <div class="blue-box">
                    <div v-if="formData?.billFile?.file?.type === 'image'">IMG</div>
                    <div v-else-if="formData.billFile?.file?.type === 'pdf'">PDF</div>
                  </div>
                  <div class="details">
                    <app-typography class="truncate max-w-[230px]" variant="body1">
                      {{ formData.billFile?.name }}
                    </app-typography>
                    <app-typography variant="body4" color="#36454F">
                      <!--                                          {{ fileSize }}-->
                    </app-typography>
                  </div>
                </div>
                <div class="pointer" @click="removeFile">
                  <image-svg width="18px" height="18px" name="cancel-icon"/>
                </div>
              </div>
            </div>
          </div>


          <div class="col-lg-6">

            <div class="contributors d-flex align-items-center mt-3" v-if="formData.contributorsIds.length >0">
              <contributor-avatar :contributors="selectedContributors"/>
              <div class="contributor-item add-contributor" @click="addContributors">
                <span>+</span>
              </div>
            </div>
            <file-preview v-else @click="addContributors" :isAddContributors="true"/>
          </div>
        </div>
      </form>
    </div>

    <add-new-visit title="Add New Visit"
                   :subTitle="subTitle"
                   :form-data="visitFormData"
                   :is-visible="modalStatus"
                   @FORM_SUBMIT="handleFormSubmit"
                   v-model:is-visible="modalStatus"
                   :visitType="visitType"
                   :contributorsArray="contributorsList"
    ></add-new-visit>
    <contributors-list-modal
        title="Select Contributors"
        :subTitle="subTitle"
        :form-data="formData"
        :is-visible="contributorsModalStatus"
        :visitType="visitType"
        @FORM_SUBMIT="handleContributorsSubmit"
        :contributorsArray="contributorsList"
        v-model:is-visible="contributorsModalStatus"
    />
  </div>
</template>

<script>
import AppTypography from "../Typography/appTypography.vue";
import ImageSvg from "../ImageSvg/imageSvg.vue";
import AppSelect from "../Select/appSelect.vue";
import {FormGroupInput, CommonFooter} from "@/components/index";
import FilePreview from "../FilePreview/filePreview.vue";
import AddNewVisit from "@components/Modal/AddNewVisit.vue";
import Contribution from "@modules/Wallet/resources/js/Pages/Contribution/Contribution.vue";
import ContributorsListModal from "@components/Modal/ContributorsListModal.vue";
import ContributorAvatar from "../ContributorAvatar/ContributorAvatar.vue";

export default {
  components: {
    ContributorsListModal,
    Contribution,
    AddNewVisit,
    AppTypography,
    ImageSvg,
    AppSelect,
    FormGroupInput,
    FilePreview,
    CommonFooter,
    ContributorAvatar
  },
  props: {
    cardHeader: {
      type: String,
      default: "Upload or Enter Manually"
    },

  },
  data() {
    return {
      formData: {
        visit_id: "",
        category: "",
        amount: "",
        description: "",
        invoice_number: "",
        due_date: "",
        already_paid: false,
        organisation: "",
        contributorsIds: [],
        billFile: null,
        filePreview: this.$store.getters.getBillDoc,
      },
      contributorsIds:[],
      visitFormData: this.$inertia.form({
        visit_reason: "",
        visit_details: "",
        name: "",
        provider_number: '',
        organisation: "",
        visit_type: "",
        contributorsIds: [],

      }),
      allVisits: [],
      visitType: [],
      billCategory: [],
      contributorsList: [],
      modalStatus: false,
      contributorsModalStatus: false,
      loading: false,
      clinicOptions: [
        {label: 'NMC Hospital', value: '1'},
        {label: 'NMF Hospital ', value: '2'},
        {label: 'Haider Clinic', value: '3'},
      ],
      visitOptions: [
        {label: 'Having pain in my neck', value: '1'},
        {label: 'Having pain in my back', value: '2'},
      ],
      billCategoryOptions: [
        {label: 'Bill Category', value: '1'},
        {label: 'Bill Category', value: '2'},
      ],
      subTitle: 'Kindly fill in the visit information, amount, and due date sections to facilitate effective record-keeping.'
    }
  },

  mounted() {
    this.getVisitList()
  },
  methods: {
    handleCancel() {
      this.$emit('cancel')
    },
    addNewVisit() {
      this.modalStatus = true
    },

    triggerFileUpload() {
      this.$refs.fileInput.click();
    },

    // Handle file selection event
    handleFileSelection(event) {
      const file = event.target.files[0];
      if (!file) return;

      // Validate file type
      // const allowedTypes = ["image/jpeg", "image/png", "application/pdf"];
      // if (!allowedTypes.includes(file.type)) {
      //   this.$toast("Only JPG, PNG, and PDF files are allowed.", "error");
      //   return;
      // }

      // Store file in formData
      this.formData.billFile = file;
    },

    // Remove uploaded file
    removeFile() {
      this.formData.billFile = null;
      this.$refs.fileInput.value = ""; // Clear input
    },


    submitForm() {
      this.$emit('formSubmit', this.formData);
    },
    addContributors() {
      this.contributorsModalStatus = true
      // this.$inertia.visit('add-contributors')
    },
    getVisitList() {
      this.loading = true;
      this.$inertia.get('/add-manual-bills', {}, {
        preserveState: true,
        onSuccess: (page) => {
          this.allVisits = page.props.allVisits.data;
          this.visitType = page.props.visitType;
          this.contributorsList = page.props.contributorsList.data;
          this.billCategory = page.props.billCategory;
          this.loading = false
        },
        onError: (error) => {
          this.loading = false
          console.error('Failed to fetch visit list:', error);
        }
      });
    },
    handleContributorsSubmit(selectedIds) {
      console.log(JSON.stringify(selectedIds), 'was')

      console.log('Selected contributor IDs:', JSON.stringify(this.formData));
      this.contributorsModalStatus = false

    },
    handleFormSubmit(form) {
      if (!this.visitFormData.visit_reason) {
        this.$toast('The visit reason field is required', 'error');
        return;
      }

      if (!this.visitFormData.provider_number) {
        this.$toast('The provider Number field is required', 'error');
        return;
      }

      if (!this.visitFormData.organisation) {
        this.$toast('The organisation ID field is required', 'error');
        return;
      }

      if (!this.visitFormData.visit_type) {
        this.$toast('The visit type ID field is required', 'error');
        return;
      }
      this.loading = true;
      this.formData = form;

      this.visitFormData.post('/add-visit', {
        onFinish: () => this.formData.reset('loading'),

        onSuccess: (response) => {
          this.$toast('Visit added successfully', 'success');
          this.loading = false;
          this.modalStatus = false;
          this.getVisitList()
          if (response.props.success) {


          }
        },
        onError: (error) => {
          this.showErrors(error);
          this.loading = false;
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
  },
  computed: {
    selectedContributors() {
      return this.contributorsList.filter(contributor =>
          this.formData.contributorsIds.includes(contributor.id)
      );
    }
  },

  watch: {
    'formData.visit_id': function (newVisitId) {
      const selectedVisit = this.allVisits.find(visit => visit.id === newVisitId);
      if (selectedVisit) {
        this.formData.organisation = selectedVisit.organisation;
      } else {
        this.formData.organisation = ""; // Clear if no match found
      }
    }
  }
}
</script>

<style scoped>
.card {
  background-color: #F6F9FD;
  border-radius: 20px;
  padding: 13px 12px;
  max-width: 348px;
  height: 66px;
  display: grid;
}

.main-card {
  box-shadow: 0px 0px 4px 0px #00000026;
  padding: 37px 53px;
  border-radius: 10px;
}

.contributor-card {
  border: 1px dashed #D0D3E8;
  display: grid;
  place-items: center;
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