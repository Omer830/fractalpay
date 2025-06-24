<template>
  <auth-layout>
    <div v-if="loading" class="loader-main">
      <div class="loader"></div>
    </div>
    <div v-if="isCompleteET">
      <complete-expense-tracker :billList="dataManage"
                                :contributorsList="contributorsList"
                                :visitType="visitType"
                                :expenseStats="expenseStats"
                                @assigndBill="assigndBill"
      />
    </div> 
    <div v-else class="container-fluid mb-4">
      <div class="row gy-4">
        <div class="col-sm-12">
          <title-action-bar :title="header.title" :isSubTitle="false" isActionButtons="true" btnRound="md"
                            :isPrimaryBtn="true" :isSecondaryBtn="true" :isUserName="false" :isDivider="true"
                            textVariant="text-38" textWeight="600" :secondaryButtonText="header.secBtnText"
                            :primaryButtonText="header.priBtnText" @primary-click="handlePrimaryBtn"
                            @secondary-click="handleSecondaryBtn"/>
        </div>
        <div class="col-12   col-xl-6">
          <expense-add-visit-card title="Add your Visits" subTitle="Add Your Visit Details" class="h-100">
            <template v-slot:content>
              <div class="d-flex flex-column align-items-center">
                <img src="../../../../../../resources/js/assets/images/undraw-mock.png" alt="mock"
                     class="image-responsive card-img">
                <reuseable-button width="100" @click="handleAddVisit">Add Visit</reuseable-button>
              </div>
            </template>
          </expense-add-visit-card>
        </div>
        <div class="col-12  col-xl-6">
          <expense-add-visit-card title="Upload or Enter Manually" subTitle="Upload your bills" class="h-100">
            <template v-slot:content>
              <div class="d-flex flex-column align-items-center gap-2">
                <div class="file"
                     @click="triggerFileInput"
                     @drop.prevent="handleFileDrop"
                     @dragover.prevent>
                  <div v-if="filePreview">
                    <img v-if="filePreview.type === 'image'" :src="filePreview.src" alt="Image Preview"
                         class="img-fluid" @click="openFullViewer"/>
                    <iframe v-else-if="filePreview.type === 'pdf'" :src="filePreview.src"
                            class="pdf-preview" @click="openFullViewer"></iframe>
                  </div>
                  <div v-else class="text-center m-auto">

                    <div class="flex justify-center m-auto">
                      <image-svg class="" name="upload-bill-icon"/>
                    </div>
                    <p class="upload-your-bill">Upload your Bill</p>
                  </div>
                  <input ref="fileInput" type="file" hidden @change="handleFileChange"
                         accept="image/*,application/pdf"/>
                </div>
                <app-typography variant="h6">Drag & drop any image or pdf file here</app-typography>
                <app-typography variant="body1" color="#777E90">or <span @click="triggerFileInput"
                                                                         class="text-blue-500 cursor-pointer">browse</span>
                  files on your computer
                </app-typography>


                <file-preview v-if="filePreview" :file="filePreview" @remove-file="clearFile" class="w-100 my-3"/>
                <!--                  <div class="d-flex">-->
                <!--                    <div class="form-check form-check-inline">-->
                <!--                      <input class="form-check-input" type="radio" name="inlineRadioOptions"-->
                <!--                             id="inlineRadio1" value="option1">-->
                <!--                      <label class="form-check-label" for="inlineRadio1">-->
                <!--                        <app-typography color="#4682BE" fontWeight="600" variant="body1">Add-->
                <!--                          Manually</app-typography>-->
                <!--                      </label>-->
                <!--                    </div>-->
                <!--                    <div class="form-check form-check-inline">-->
                <!--                      <input class="form-check-input" type="radio" name="inlineRadioOptions"-->
                <!--                             id="inlineRadio2" value="option2">-->
                <!--                      <label class="form-check-label" for="inlineRadio2">-->
                <!--                        <app-typography color="#4682BE" fontWeight="600" variant="body1">Automatic-->
                <!--                          Scanning</app-typography>-->
                <!--                      </label>-->
                <!--                    </div>-->
                <!--                  </div>-->
              </div>
            </template>
          </expense-add-visit-card>
        </div>

        <div class="col-12">
          <common-footer @back="handleBackClick" @continue="handleContinueClick"/>
        </div>
      </div>
    </div>
    <div v-if="isViewerOpen" class="full-page-viewer" @click="closeFullViewer">
      <div v-if="filePreview.type === 'image'">
        <img :src="filePreview.src" alt="Full Image" class="full-image"/>
      </div>
      <div v-else-if="filePreview.type === 'pdf'" class="full-pdf-container">
        <iframe :src="filePreview.src" class="full-pdf"></iframe>
      </div>
      <div class="close-button" @click="closeFullViewer">X</div>
    </div>
    <add-new-visit title="Add New Visit"
                   :subTitle="subTitle"
                   :form-data="formData"
                   :is-visible="modalStatus"
                   @FORM_SUBMIT="handleFormSubmit"
                   v-model:is-visible="modalStatus"
                   :visitType="visitType"
                   :contributorsArray="contributorsList"
    ></add-new-visit>
  </auth-layout>
</template>

<script>
import {
  TitleActionBar,
  ExpenseAddVisitCard,
  ReuseableButton,
  AppTypography,
  FilePreview,
  CommonFooter
} from "@/components/index.js";
import CompleteExpenseTracker from "./CompleteExpenseTracker.vue"
import AddNewVisit from "@/components/Modal/AddNewVisit.vue";;
import {usePage} from "@inertiajs/vue3";
const userData = usePage();
export default {
  components: {
    AddNewVisit,
    CompleteExpenseTracker,
    ExpenseAddVisitCard,
    ReuseableButton,
    AppTypography,
    CommonFooter,
    TitleActionBar,
    FilePreview,
  },
  data() {
    return {
      userData: userData.props.auth,
      isCompleteET: false,
      header: {
        title: "View All Visits",
        secBtnText: "Add Bills",
        priBtnText: "Add New Visits"
      },
      formData: this.$inertia.form({
        visit_reason: "",
        visit_details: "",
        name: "",
        provider_number: '',
        organisation: "",
        visit_type: "",
        contributorsIds: [],

      }),
      visitType: [],
      contributorsList: [],
      billList: [],
      contributorsBillList: [],
      dataManage: [],
      expenseStats: {},
      modalStatus: false,
      loading: false,
      isViewerOpen: false,
      filePreview: null,
      subTitle: 'Kindly fill in the visit information, amount, and due date sections to facilitate effective record-keeping.'
    }
  },
  created() {
    this.isCompleteET =  this.userData.isOwnerVisit
  },
  mounted() {
    this.getExpenseTracker()

    // this.isCompleteET =  this.userData.isOwnerVisit
  },
  watch: {
    '$store.getters.getExpenseType': {
      handler(newValue) {
        if (newValue === 'Share with me') {
          this.assigndBill({ label: 'Share with me' });
        }
      },
      immediate: true // Mounted pe bhi call ho jayega
    }
  },
  methods: {
    handlePrimaryBtn() {
      this.modalStatus = true
      this.resetForm()
    },
    resetForm() {
      console.log(this.formData.contributorsIds , 'hello zain')
      this.formData = this.$inertia.form({
        visit_reason: "",
        visit_details: "",
        name: "",
        provider_number: '',
        organisation: "",
        visit_type: "",
        contributorsIds: [],
      });
    },
    handleSecondaryBtn() {
      // if (!this.filePreview) {
      //   this.$toast('Please first select an image or PDF file', 'error');
      //   return;
      // }
      this.$inertia.visit('/add-manual-bills')
    },
    handleContinueClick() {
      if (!this.filePreview) {
        this.$toast('Please first select an image or PDF file', 'error');
        return;
      }
      this.$store.commit('SET_BILL_DOC', this.filePreview);
      this.$inertia.visit('add-manual-bills')
    },
    handleBackClick() {
      this.$router.push('/dashboard')
    },
    handleAddVisit() {
      this.modalStatus = true
      // this.isCompleteET = true
    },

    handleFormSubmit(form) {
      this.formData = form;
      if (!this.formData.visit_reason) {
        this.$toast('The visit reason field is required', 'error');
        return;
      }

      if (!this.formData.provider_number) {
        this.$toast('The provider Number field is required', 'error');
        return;
      }

      if (!this.formData.organisation) {
        this.$toast('The organisation ID field is required', 'error');
        return;
      }

      if (!this.formData.visit_type) {
        this.$toast('The visit type ID field is required', 'error');
        return;
      }
      this.loading = true;
      this.formData = form;

      this.formData.post('/add-visit', {
        onFinish: () => this.formData.reset('loading'),

        onSuccess: (response) => {
          this.$toast('Visit added successfully', 'success');
          this.loading = false;
          this.modalStatus = false
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
    triggerFileInput() {
      this.$refs.fileInput.click();
    },
    handleFileChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.generateFilePreview(file);
      }
    },
    handleFileDrop(event) {
      const file = event.dataTransfer.files[0];
      if (file) {
        this.generateFilePreview(file);
      }
    },
    generateFilePreview(file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        const fileType = file.type.startsWith('image/') ? 'image' : 'pdf';
        this.filePreview = {
          type: fileType,
          src: e.target.result,
          name: file.name,
          size: file.size,
        };
      };
      reader.readAsDataURL(file);
    },

    clearFile() {
      this.filePreview = null;
    },
    openFullViewer() {
      this.isViewerOpen = true;
    },
    closeFullViewer() {
      this.isViewerOpen = false;
    },

    getExpenseTracker() {
      this.loading = true;
      this.$inertia.get('/expense-tracker', {}, {
        preserveState: true,
        onSuccess: (page) => {
          this.loading = false
          this.visitType = page.props.visitType;
          this.contributorsList = page.props.contributorsList
          this.billList = page.props.visitsList.data
          this.contributorsBillList = page.props.contributorVisitList.data;
          this.dataManage = page.props.visitsList.data
          this.expenseStats = page.props.expenseStats
          if (this.billList?.length > 0 || this.contributorsBillList?.length > 0) {
            this.isCompleteET = true
          }
        }
      });

    },

    assigndBill(value) {
      if(value.label === 'My Visits'){
        this.dataManage = this.billList
      }else if(value.label === 'Share with me'){
        this.dataManage = this.contributorsBillList
      }if(value.label === 'Contribution'){
        this.$inertia.visit('/contribution-tree')
      }


    },

  }


}
</script>

<style scoped>
.card-img {
  margin: 97px 78px;
  max-height: 257px;
  max-width: 370px;
}

.file {
  border: 1px dashed #4682BE;
  background-color: #F6F9FD;
  width: 457px;
  height: 326px;
  place-items: center;
  border-radius: 4px;
  display: grid;

}

.img-fluid {
  width: 100%;
  height: 310px;
  object-fit: cover;
}

.file ::-webkit-file-upload-button {
  visibility: hidden;
}


.upload-your-bill {

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

.full-page-viewer {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.full-image {
  max-width: 90%;
  max-height: 90%;
}

.pdf-preview {
  width: 100%;
  height: 320px;
  border: none;
}

.full-pdf-container {
  width: 90%;
  height: 90%;
}

.full-pdf {
  width: 100%;
  height: 100%;
}

.close-button {
  position: absolute;
  top: 20px;
  right: 20px;
  font-size: 30px;
  color: white;
  cursor: pointer;
}

</style>