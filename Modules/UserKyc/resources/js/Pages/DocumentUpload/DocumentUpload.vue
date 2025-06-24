<template>
    <div>
        <logo-header @back-clicked="handleBack" :hasDivider="true" />
        <div class="container-fluid mb-4">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <document-upload-card
                        :documents="documents"
                        :uploadArray="uploadArray"
                        @file-selected="onFileSelected"
                        @file-removed = "fileRemove"
                        @cancel="handleCancel"
                        @done="handleDone" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { LogoHeader, DocumentUploadCard } from "@/components/index.js";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    components: {
        LogoHeader,
        DocumentUploadCard,
    },
  props:{
    documents:Array,
  },
    data() {
        return {
          user: this.$inertia.form({
            file: null,
            file_type: '',
            side: 'front'
          }),

          uploadArray: [
            {
              name: 'No file selected',
              size: '0 MB',
              progress: 0,
              DropZoneText: 'Drop your Front image here, or',
              side: 'front'
            },
            {
              name: 'No file selected',
              size: '0 MB',
              progress: 0,
              DropZoneText: 'Drop your Back image here, or',
              side: 'back'
            }
          ]
        };
    },
  watch: {
    'this.$store.getters.getDocumentName.attributes.number_of_files': {
      immediate: true,
      handler(newVal) {
        this.initializeUploadArray();
      }
    },
    documents: {
      immediate: true,
      handler(newDocuments) {
        newDocuments.forEach(doc => {
          const index = doc.custom_properties.side === 'back' ? 1 : 0;
          this.uploadArray[index].id =  doc.id;
          this.uploadArray[index].name = doc.name;
          this.uploadArray[index].size = `${(doc.size / 1024 / 1024).toFixed(2)} MB`;
          this.uploadArray[index].imageUrl = doc.url;
          this.uploadArray[index].progress = 100; // Assuming the image is fully uploaded
          this.uploadArray[index].id =  doc.id;
        });
      }
    }
  },
    methods: {
      initializeUploadArray() {
        const numFiles = this.$store.getters.getDocumentName.attributes.number_of_files || 1; // Default to 1 if undefined
        this.uploadArray = Array.from({ length: numFiles }, (_, index) => ({
          name: 'No file selected',
          size: '0 MB',
          progress: 0,
          DropZoneText: `Drop your ${index === 0 ? 'Front' : 'Back'} image here, or`,
          side: index === 0 ? 'front' : 'back',
        }));
      },
      handleBack() {
        window.history.back();
      },
        handleCancel() {
          window.history.back();
        },
      fileRemove(index) {
          this.user.file = null;
          this.user.file_type = '';
          this.user.side = '';
      },
      onFileSelected({ file, index }) {
        const side = this.uploadArray[index].side;
        this.user.file = file;
        this.user.file_type = this.$store.getters.getDocumentName.slug;
        this.user.side = side;
        this.uploadArray[index].name = file.name;
        this.uploadArray[index].size = `${(file.size / 1024 / 1024).toFixed(2)} MB`;
        this.uploadArray[index].progress = 0;
        this.handleDone();
      },
      handleDone() {
        if (!this.user.file) {
          this.$toast('No file selected', 'error');
          return;
        }
        const formData = new FormData();
        formData.append('file', this.user.file);
        formData.append('file_type', this.user.file_type);
        formData.append('side', this.user.side);

        this.user.post('/uploadKyc', formData, {
          headers: { 'Content-Type': 'multipart/form-data' },

          onSuccess: (response) => {
            this.$toast(`Upload successful for ${this.user.side} side`, 'success');
            this.refreshDocuments();
            if (response.props.success) {

            }
          },
          onError: () => alert('Upload failed'),
          forceFormData: true
        });
      },
      refreshDocuments() {
        this.$inertia.visit('/document-upload', {
          replace: true, // This prevents adding a new entry to the history stack
          preserveScroll: true,
          preserveState: true,
          onSuccess: (page) => {
            // Optionally update local state with new documents if needed
            this.uploadArray = page.props.documents;
          }
        });
      }

    }
}
</script>

<style scoped></style>