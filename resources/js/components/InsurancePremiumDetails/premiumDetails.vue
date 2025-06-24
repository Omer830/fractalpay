<template>
  <div class="pre-details-wrapper container">
    <div v-if="loading" class="loader-main">
      <div class="loader"></div>
    </div>
    <div class="box-wrapper">
      <app-typography
          variant="body1"
          fontWeight="600">
        {{ title }}
      </app-typography>
      <hr />
      <form @submit.prevent="handleSubmit">
        <div class="row gy-3">
          <div class="col-md-12">
            <!-- Insurance Company -->
            <app-select
              type="text"
              label="Insurance Company"
              borderRadiusClass="border-sm"
              :options="insuranceNames"
              v-model="formData.company_name"
              optionLabel="name"
              optionValue="slug"
              placeholder="Select an option"
              :error="errors.company_name"
              :required="true"
              @change="clearError('company_name')"
              @blur="validateInput('company_name')"
            />
          </div>
          <div class="col-md-12">
            <!-- Card Number -->
            <form-group-input
              type="text"
              label="Card Number"
              placeholder="Enter card number"
              border-radius-class="border-sm"
              v-model="formData.card_number"
              :value="formData.card_number"
              :error="errors.card_number"
              v-mask="'#### #### #### ####'"
              :required="true"
              @change="clearError('card_number')"
              @blur="validateInput('card_number')"
            >
            </form-group-input>
          </div>
          <div class="col-md-6">
            <!-- Policy Number -->
            <form-group-input
              type="text"
              label="Insurance Policy Number"
              placeholder="5xx-xxx-xxx"
              border-radius-class="border-sm"
              v-model="formData.policy_number"
              :value="formData.policy_number"
              :error="errors.policy_number"
              @change="clearError('policy_number')"
              @blur="validateInput('policy_number')"
              :required="true"
              :maxlength="20"
            >
            </form-group-input>
          </div>
          <div class="col-sm-6 col-md-3">
            <!-- Premium Terms -->
            <app-select
              type="text"
              label="Premium Terms"
              borderRadiusClass="border-sm"
              :options="termsPeriods"
              v-model="formData.terms"
              optionLabel="name"
              optionValue="slug"
              placeholder="Select an option"
              :error="errors.terms"
              :required="true"
              @change="clearError('terms')"
              @blur="validateInput('terms')"
            />
          </div>
          <div class="col-sm-6 col-md-3">
            <!-- Amount -->
            <form-group-input
              type="number"
              label="Amount"
              placeholder="Enter amount"
              border-radius-class="border-sm"
              v-model="formData.amount"
              :value="formData.amount"
              :error="errors.amount"
              @change="clearError('amount')"
              @blur="validateInput('amount')"
              :required="true"
              :max="99999"
              :maxlength="6"
              @input="limitAmountInput"
            />
          </div>
          <div class="col-md-12 upload-wrapper mt-5">
            <input type="file" hidden @change="handleFileChange" class="custom-file-input" ref="fileInput" />
            <button  type="button" class="upload-btn" @click="$refs.fileInput.click()">
              Click here to Upload your Document
            </button>
            <div v-if="user.file || insuranceDocument" class="uploaded-file items-center justify-center d-flex">
  <span class="truncate w-[90%]">
    {{ user.file ? user.file.name : getFileName(insuranceDocument) }}
  </span>
              <div class="pointer" @click="removeFile">
                <image-svg width="18px" height="18px" name="cancel-icon"/>
              </div>
            </div>
            <div v-if="insuranceDocument" class="text-primary cursor-pointer mt-2" @click="toggleDocumentModal">
              View Document
            </div>

          </div>
          <!--                    <div class="col-md-12 d-flex justify-content-center">-->
          <!--                        <reuseable-button class="w-75 mt-5">Scan Document</reuseable-button>-->
          <!--                    </div>-->
        </div>
      </form>
    </div>
    <div v-if="showDocumentModal" class="modal-overlay">
      <div class="modal-content">
        <div class="modal-header">
<!--          <span @click="toggleDocumentModal" class="close-btn">&times;</span>-->
          <button  @click="toggleDocumentModal" class="close-btn">×</button>
        </div>
        <iframe
            v-if="insuranceDocument"
            :src="insuranceDocument"
            width="100%"
            height="500px"
            frameborder="0"
        ></iframe>
      </div>
    </div>

  </div>
</template>

<script>
import { AppTypography, FormGroupInput,ReuseableButton } from "@/components/index";
import AppSelect from "@components/Select/appSelect.vue";
import ImageSvg from "../ImageSvg/imageSvg.vue";
import Swal from "sweetalert2";

export default {

  components: {
    ImageSvg,
    AppSelect,
    AppTypography,
    FormGroupInput,
    ReuseableButton
  },
  props: {
    insuranceNames: {
      type: Array,
      default: () => ([]),
    },
    termsPeriods: {
      type: Array,
      default: () => ([]),
    },
    errors:Object,
    insuranceData:Object,
  },
  data() {
    return {
      title: 'Insurance Premium Details',
      formData: {
        company_name: '',
        card_number: '',
        policy_number: '',
        terms: '',
        amount: ''
      },
      user: this.$inertia.form({
        file: null,
        file_type: 'insurance-certificate',
      }),
      insuranceDocument:'',
      loading:false,
      showDocumentModal: false,
      validationRules: {
        company_name: [
          {
            validator: (value) => (value ? value.toString().trim().length > 0 : false), // Ensure the field is not empty
            message: 'Company Name is required.',
          },
        ],
        card_number: [
          {
            validator: (value) => (value ? value.toString().trim().length > 0 : false), // Ensure the field is not empty
            message: 'Card Number is required.',
          },
          {
            validator: (value) => {
              const cleanCardNumber = value ? value.replace(/\s+/g, '') : ''; // Remove spaces
              return /^\d{16}$/.test(cleanCardNumber); // Ensure exactly 16 digits
            },
            message: 'Card Number must be exactly 16 digits.',
          },
        ],
        policy_number: [
          {
            validator: (value) => (value ? value.toString().trim().length > 0 : false), // Ensure the field is not empty
            message: 'Policy Number is required.',
          },
          {
            regex: /^[0-9a-zA-Z-]{5,20}$/, // Allow alphanumeric and hyphen, 5-20 characters
            message: 'Policy Number must be between 5 and 20 characters.',
          },
        ],
        terms: [
          {
            validator: (value) => (value ? value.toString().trim().length > 0 : false), // Ensure the field is not empty
            message: 'Premium Terms are required.',
          },
        ],
        amount: [
          {
            validator: (value) => (value ? value.toString().trim().length > 0 : false), // Ensure the field is not empty
            message: 'Amount is required.',
          },
          {
            validator: (value) => !isNaN(value) && parseFloat(value) > 0, // Ensure amount is a valid number greater than zero
            message: 'Please enter a valid amount greater than zero.',
          },
          {
            validator: (value) => parseFloat(value) <= 999999, // Ensure amount does not exceed 999,999
            message: 'Amount must not exceed 999,999.',
          },
        ],
      },
    }
  },
  mounted() {
    if (this.insuranceData) {
      this.setFormData(this.insuranceData);
    }
  },

  methods: {
    toggleDocumentModal() {
      this.showDocumentModal = !this.showDocumentModal;
    },
    validateField(value, rules) {
  for (const rule of rules) {
    // If regex validation is present
    if (rule.regex && !rule.regex.test(value ? value.toString() : '')) {
      return rule.message;
    }
    // If custom validator is present
    if (rule.validator && !rule.validator(value)) {
      return rule.message;
    }
  }
  return ''; // Empty string indicates no error
},

    handleSubmit() {
    let isValid = true;

    Object.keys(this.validationRules).forEach((field) => {
      const error = this.validateField(this.formData[field], this.validationRules[field]);
      if (error) {
        this.errors[field] = error;
        isValid = false;
      } else {
        this.errors[field] = ''; // Clear error if validation passes
      }
    });

    if (!isValid) {
      return; // Stop submission if validation fails
    }

    this.$emit('submit', this.formData); // Submit the form if validation passes
  },

    removeFile() {
      this.user.file = null;
      this.insuranceDocument = ''
    },

    //  removeFile(documentId = null) {
    //   if (!this.insuranceDocument && !this.user.file) {
    //     this.$toast('No document to delete', 'error');
    //     return;
    //   }

    //   const docId = documentId || this.insuranceData?.document_id || this.insuranceData?.id;
      
    //   if (!docId) {
    //     this.$toast('Document ID not found', 'error');
    //     return;
    //   }

    //   Swal.fire({
    //     title: 'Are you sure?',
    //     text: "Do you want to delete this insurance document? This action cannot be undone.",
    //     icon: 'warning',
    //     showCancelButton: true,
    //     confirmButtonColor: '#4682BE',
    //     cancelButtonColor: '#bcbcbc',
    //     confirmButtonText: 'Yes, delete it!'
    //   }).then((result) => {
    //     if (result.isConfirmed) {
    //       this.loading = true; // Set loading state during deletion

    //       this.$inertia.delete(`/delete-document/${docId}`, {
    //         preserveScroll: true,
    //         preserveState: true,
    //         onSuccess: () => {
    //           this.insuranceDocument = ''; // Clear the document reference
    //           this.user.file = null; // Clear the file reference
    //           this.loading = false; // Reset loading state
    //           this.$toast('Insurance document deleted successfully', 'success');
    //         },
    //         onError: () => {
    //           this.loading = false; // Reset loading state on error
    //           this.$toast('Failed to delete insurance document', 'error');
    //         },
    //         onFinish: () => {
    //           this.loading = false; // Ensure loading is reset after the request finishes
    //         }
    //       });
    //     }
    //   });
    // },

    handleFileChange(event) {
      const file = event.target.files[0];
      const maxFileSize = 5 * 1024 * 1024; // 5 MB in bytes

      if (file) {
        // Validate file size
        if (file.size > maxFileSize) {
          this.$toast('File size exceeds the maximum limit of 5 MB.', 'error');
          return;
        }

        this.user.file = file; // Store the file
        this.handleDone(); // Proceed with upload
      }
    },
    handleDone() {
      if (!this.user.file) {
        this.$toast('No file selected', 'error');
        return;
      }

      // this.loading = true; // Set loading state to true during upload

      const formData = new FormData();
      formData.append('file', this.user.file);
      formData.append('file_type', this.user.file_type);

      this.user.post('/uploadKyc', formData, {
        preserveState: true,
        preserveScroll: true,
        headers: { 'Content-Type': 'multipart/form-data' },
        onSuccess: (response) => {
          this.loading = false; 
          if (response.props.success) {
            this.$toast(`Upload successful for ${this.user.side} side`, 'success');
          }
        },
        onError: () => {
          this.loading = false; // Reset loading state on error
          this.$toast('Upload failed', 'error');
        },
        forceFormData: true,
    });
    },
    clearError(fieldName) {
      if (this.errors[fieldName]) {
        this.errors[fieldName] = null;
      }
    },


    setFormData(data) {
      this.formData.company_name = data.company_name || '';
      this.formData.card_number = data.card_number || '';
      this.formData.policy_number = data.policy_number || '';
      this.insuranceDocument = data?.document_url || '';
      this.formData.terms = data.terms || '';
      if (data.amount) {
        this.formData.amount = parseInt(data.amount, 10);
      } else {
        this.formData.amount = '';
      }
    },
    getFileName(path) {
      return path?.split('/').pop() || 'Uploaded file';
    },
    limitAmountInput(event) {
      let val = event.target.value.replace(/\D/g, '').slice(0, 6); // Only digits, max 6
      this.formData.amount = val;
    },
    validateInput(fieldName) {
  const error = this.validateField(this.formData[fieldName], this.validationRules[fieldName]);
  if (error) {
    this.errors[fieldName] = error; // Set the error message
  } else {
    this.errors[fieldName] = ''; // Clear the error if validation passes
  }
},

  },

}
</script>

<style scoped>
.box-wrapper {
  box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
  background-color: #ffff;
  padding: 31px 24px;
  border-radius: 10px;
  max-width: 768px;
  margin: auto;
}

.upload-wrapper {
  max-width: 393px;
  margin: auto;
  border: 1px solid #4682BE;
  border-radius: 4px;
  border-style: dashed;
  padding: 30px 0px;
  text-align: center;
  background-color: #F7F9FD;
}

.custom-file-input::-webkit-file-upload-button {
  visibility: hidden;
}

/*.custom-file-input::before {
  content: 'Select some files';
 display: inline-block;
}
 */

.custom-file-input:hover::before {
  border-color: black;
}

.custom-file-input:active::before {
  background: -webkit-linear-gradient(top, #e3e3e3, #f9f9f9);
}
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

.modal-content {
  background: white;
  border-radius: 8px;
  max-width: 800px;
  width: 90%;
  position: relative;
  padding: 1rem;
}

.modal-header {
  display: flex
}
.close-btn {
  position: absolute;
  right: 10px;
  top: 10px;
  background: red;
  color: white;
  border: none;
  padding: 1px 6px 3px 6px;
  cursor: pointer;
  border-radius: 50%;
}

</style>