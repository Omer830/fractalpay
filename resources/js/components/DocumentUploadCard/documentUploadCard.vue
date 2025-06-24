<template>
  <div class="card  m-auto" :class="uploadArray.length === 1 ? 'col-md-6' : ''">
    <div class="card-header d-flex align-items-center justify-content-between bg-white border-bottom">
      <div class="heading">
        <app-typography variant="h5">Upload {{ $store.getters.getDocumentName.name }}</app-typography>
<!--        <app-typography variant="body3">{{ subTitle }}</app-typography>-->
      </div>
      <image-svg width="24px" height="24px" name="black-cross-icon"/>
    </div>
    <div class="card-body ">
      <div class="row gy-3">
        <div v-for="(item, index) in uploadArray" :key="index"
             :class="uploadArray.length === 1 ? 'col-lg-12' : 'col-lg-6'">
          <div class="d-flex flex-column gap-3">
            <div class="file" @change="handleFileChange($event, index)" @dragover.prevent
                 @drop="handleFileDrop($event, index)">
              <div v-if="item.imageUrl" class="image-preview m-auto">
                <img :src="item.imageUrl" alt="Uploaded Image" class="uploaded-image m-auto" >

                <div class="delete-overlay" @click="removeImage(index,item.id)">
                  <image-svg width="24px" height="24px" name="cross-icon"/>
                </div>
              </div>
              <div v-else>
                <div class="text-center ">
                  <div>
                    <img class="m-auto"  @click="triggerFileInput" src="../../assets/images/Picture.png" alt="Placeholder" style="cursor: pointer;">

                    <input type="file"  id="fileInput" style="display: none;" hidden accept="image/*" ref="image">
                  </div>
                </div>

                <div class="d-flex flex-wrap gap-2 py-2">
                  <app-typography variant="h6" color="#132A00">{{ item.DropZoneText }}</app-typography>
                  <app-typography class="cursor-pointer" variant="h5" color="#1F4690" @click="triggerFileInput">browse</app-typography>
                </div>
              </div>
            </div>
            <div class="upload-progress" v-if="item.imageUrl">
              <div class="d-flex justify-content-between">
                <div class="details d-flex gap-1">
                  <image-svg width="40px" height="30px" name="image-mock-icon"/>
                  <div class="desc">
                    <app-typography variant="body3" color="#132A60" fontWeight="500">{{ item.name }}</app-typography>
<!--                    <app-typography variant="body4" color="#969DB2" fontWeight="500">{{ item.size }}</app-typography>-->
                  </div>
                </div>
                <div class="cursor-pointer" @click="removeImage(index,item.id)">
                  <image-svg width="24px" height="24px" name="cross-icon"/>
                </div>
              </div>
              <div class="progress-wrapper flex gap-1 items-center pt-2">
                <div class="progress w-full">
                  <div class="progress-bar" role="progressbar" :style="{width: `${item.progress}%`}"
                       :aria-valuenow="item.progress" aria-valuemin="0" aria-valuemax="100"></div>

                </div>
                <app-typography variant="body4" color="#969DB2" fontWeight="500">{{ formattedProgress[index].displayProgress }}</app-typography>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer d-flex justify-content-center gap-3 bg-white border-0">
      <reuseable-button @click="handleCancel" class="px-4" btnHeight="41px" outline textColor="#000000">Cancel
      </reuseable-button>
      <reuseable-button @click="handleDone" class="px-4" btnHeight="41px">Done</reuseable-button>
    </div>
  </div>
</template>


<script>
import AppTypography from '../Typography/appTypography.vue';
import ImageSvg from '../ImageSvg/imageSvg.vue';
import ReuseableButton from '../Button/reuseableButton.vue';
import Swal from "sweetalert2";

export default {
  components: {
    ImageSvg,
    AppTypography,
    ReuseableButton,
  },
  props: {
    title: {
      type: String,
      default: '',
    },
    subTitle: {
      type: String,
      default: '(ABC)',
    },
    uploadArray: {
      type: Array,
      default: () => []
    },
    documents: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      isUploading: false,
      uploadProgress: 0,
      uploadInterval: null,
    };
  },
  mounted() {

  },
  computed: {
    formattedProgress() {
      return this.uploadArray.map(item => {
        return {
          ...item,
          displayProgress: item.progress === 100 ? '100%' : `${item.progress.toFixed(2)}%`
        };
      });
    }
  },

  methods: {
    handleCancel() {
      if(this.$store.getters.getDocType === "Primary Documents" ){
        this.$inertia.visit('/primary-documents')
      }if(this.$store.getters.getDocType === "Secondary Documents" ){
        this.$inertia.visit('/secondary-documents')
      }if(this.$store.getters.getDocType === "Other Documents" ){
        this.$inertia.visit('/other-documents')
      }
      // this.$emit('cancel');
    },
    handleDone() {
      // Check if there are images in the uploadArray
      if (this.uploadArray.length === 0) {
        // Display error message if no images are present
        this.$toast('Failed to delete document. No images to upload.', 'error');
      } else {

        const allImagesUploaded = this.uploadArray.every(item => item.imageUrl);

        if (allImagesUploaded) {
          if(this.$store.getters.getDocType === "Primary Documents" ){
            this.$inertia.visit('/primary-documents')
          }if(this.$store.getters.getDocType === "Secondary Documents" ){
            this.$inertia.visit('/secondary-documents')
          }if(this.$store.getters.getDocType === "Other Documents" ){
            this.$inertia.visit('/other-documents')
          }
        } else {

          this.$toast('Please upload all required images before proceeding.', 'error');
        }
      }
    },

    triggerFileInput() {
      // this.$refs.fileInput.click();
      document.getElementById("fileInput").click()
    },
    handleFileDrop(event, index) {
      event.preventDefault();
      const files = event.dataTransfer.files;
      if (files.length > 0) {
        this.processFile(files[0], index);
        this.startFauxProgress(index);
      }
    },
    handleFileChange(event, index) {
      const file = event.target.files[0];
      if (file) {
        this.processFile(file, index);
        this.startFauxProgress(index);
      }
    },
    processFile(file, index) {
      this.uploadArray[index].imageUrl = URL.createObjectURL(file);
      this.uploadArray[index].name = file.name;
      this.uploadArray[index].size = `${(file.size / 1024 / 1024).toFixed(2)} MB`;
      this.$emit('file-selected', {file, index});
    },
    removeImage(index, id) {

      Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to delete this document? This action cannot be undone",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4682BE',
        cancelButtonColor: '#bcbcbc',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {

          this.$inertia.delete(`/delete-document/${id}`, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: (response) => {
              this.deleteDocument(index, id);
              this.$toast('Document deleted successfully', 'success');
            },
            onError: (errors) => {
              console.error('Error deleting document:', errors);
              this.$toast('Failed to delete document', 'error');
            }
          });
        }
      });
    },

    deleteDocument(index,id) {
      console.log(index , id)
      this.uploadArray[index].name = "No file selected";
      this.uploadArray[index].size = "0 MB";
      this.uploadArray[index].imageUrl = null;
      this.uploadArray[index].progress = 0;
      this.$emit('file-removed', index);
      // this.refreshDocuments()
    },


    refreshDocuments() {
      this.$inertia.visit('/document-upload', {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
          this.uploadArray = page.props.documents;
        }
      });
    },
    startFauxProgress(index) {
      const uploadDuration = 3000;
      let progressInterval = setInterval(() => {
        if (this.uploadArray[index].progress < 100) {
          this.uploadArray[index].progress += 100 / (uploadDuration / 100);
        } else {
          clearInterval(progressInterval);
          this.uploadArray[index].progress = 100;
          this.$toast(`Upload complete for "${this.uploadArray[index].name}"`, 'success');
        }
      }, 50);
    },

  }
}
</script>


<style scoped>
.card {
  box-shadow: 0px 4.79px 25.14px 1.2px #2463EB0D;
  border-radius: 15px;
  padding: 30px 32px;
}

.file {
  border: 1.5px dashed #B1BFD0;
  border-radius: 10px;
  place-items: center;
  padding: 46px;
  display: grid;
  min-height: 30vh;
}

.file ::-webkit-file-upload-button {
  visibility: hidden;
}

.file::after {
  content: 'Supports: PNG, JPG, JPEG, WEBP';
  display: inline-block;
  color: #969DB2;
  font-size: 12px;
}

.file:hover::before {
  border-color: black;
}

.file:active::before {
  background: -webkit-linear-gradient(top, #e3e3e3, #f9f9f9);
}

.image-preview {
  position: relative;
  width: 100%;
  border-radius: 10px;
  overflow: hidden;
}

.uploaded-image {
  width: 100%;
  display: block;
  height: 20vh;
  object-fit: cover;
}

.delete-overlay {
  position: absolute;
  top: 10px;
  right: 10px;
  background-color: rgba(255, 255, 255, 0.8);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  width: 24px;
  height: 24px;
}

.upload-progress {
  border: 1px solid #E4E9F0;
  padding: 16px 12px;
  border-radius: 6px;
  max-width: 354px;
  width: 100%;
  margin: auto;
}

.progress-bar {
  background-color: #4682BE;
}

.progress,
.progress-bar {
  height: 10px;
}
</style>
