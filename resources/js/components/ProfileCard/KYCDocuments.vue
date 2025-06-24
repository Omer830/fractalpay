<template>
  <div class="container">
    <div v-if="loading" class="loader-main">
      <div class="loader"></div>
    </div>
    <div class="card">
      <div class="form-wrapper">
        <div class="flex justify-between">
        <app-typography variant="h4" fontWeight="500" class="my-4">
          Uploaded Documents
        </app-typography>
        <div class="w-[200px]" >
           <reuseable-button  width="100" btnHeight="40" @click.native="handleGotoEditDocuments">
              Edit Documents
        </reuseable-button>
        </div>
</div>
        <!-- Document Display Section -->
        <div class="document-section">

          <div v-if="!userProfile.data.userDocument || userProfile.data.userDocument.length === 0">
            <p>No documents uploaded yet.</p>
          </div>
          <div v-else>
            <!-- Custom Table -->
            <table class="custom-table">
              <thead>
                <tr>
                  <th>Document Name</th>
                  <th>Document Type</th>
                  <th>Side</th>
                  <th>Uploaded At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="doc in userProfile.data.userDocument" :key="doc.id">
                  <td>{{ doc.name }}</td>
                  <td>{{ doc.collection_name }}</td>
                  <td>{{ doc.custom_properties?.side || 'N/A' }}</td>
                  <td>{{ new Date(doc.created_at).toLocaleDateString() }}</td>
                  <td>
                    <a :href="doc.url" target="_blank" class="doc-link">View</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- <common-footer @back="goBackHandler" :isBackBtn="false" buttonTitle="Submit" @continue="handleSecuritySubmit"/> -->
</template>

<script>
import { AppTypography, FormGroupInput, ReuseableButton } from "@/components/index";
import PROFILE_AVATAR from "../../assets/images/owner-profile.png";
import MALE_AVATAR from "../../assets/images/Male.png";
import FEMALE_AVATAR from "../../assets/images/Female.png";
import CommonFooter from "../CommonFooter/commonFooter.vue";
import AppSelect from "../Select/appSelect.vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import api from "@services/api.js";

const userData = usePage();

export default {
  components: {
    AppTypography,
    FormGroupInput,
    ReuseableButton,
    CommonFooter,
    AppSelect
  },
  props: {
    avatar: {
      type: String,
      default: PROFILE_AVATAR,
    },
    userName: {
      type: String,
      default: "User Name",
    },
    userStatus: {
      type: String,
      default: "offline",
    },
    isVerificationBtn: {
      type: Boolean,
      default: false
    },
    errors: {
      type: Object,
      default: () => ({})
    },
    userProfile: {
      type: Object,
      default: () => ({})
    }
  },
  emits: ['form-submitted', 'back-btn', 'continue-btn', 'verify'],
  data() {
    return {
      userData: userData.props.auth.user,
      mainTitle: 'User KYC',
      loading: false
    };
  },
  mounted() {
    console.log(JSON.stringify(this.userProfile));
    console.log(JSON.stringify(this.userProfile.data.userDocument));
  },
  methods: {
    handleGotoEditDocuments() {
      this.$inertia.visit('/doc-type');
    },
   
  },
  
};
</script>

<style scoped>
.card {
  box-shadow: rgba(0, 0, 0, 0.25);
  padding: 30px 25px;
  border-radius: 10px;
  max-width: 1018px;
  margin: auto;
}

.profile-img {
  width: 80px;
  height: 80px;
  object-fit: cover;
}

.custom-date-picker {
  width: 100% !important;
}

.profile-container {
  position: relative;
  display: inline-block;
}

.profile-img {
  display: block;
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 10px;
}

.cam-icon {
  position: absolute;
  bottom: 8px;
  right: 8px;
  z-index: 10;
  cursor: pointer;
  background-color: white;
  border-radius: 50%;
  padding: 5px;
}

/* Document Section Styles */
.document-section {
  margin-top: 30px;
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

.custom-table th,
.custom-table td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: left;
}

.custom-table th {
  background-color: #f2f2f2;
  font-weight: 500;
}

.doc-link {
  font-size: 14px;
  color: #4682BE;
  text-decoration: underline;
}
</style>
