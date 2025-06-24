<template>
  <div v-if="isAddContributors" class="card contributor-card" @click="clickAdd">
    <div class="wrapper d-flex justify-content-between align-items-center gap-2 pointer">
      <image-svg width="16px" height="16px" name="plus-rounded-success-icon" />
      <app-typography variant="body1" color="#6EC392">{{ addText }}</app-typography>
    </div>
  </div>
  <div v-else  class="card">
    <div class="wrapper d-flex justify-content-between align-items-center">
      <div class="img-wrapper d-flex gap-3 ">
        <div class="blue-box">
          <div v-if="file?.type === 'image'">
            IMG
          </div>
          <div v-else-if="file?.type === 'pdf'">
            PDF
          </div>
        </div>
        <!--        <image-svg width="40px" height="40px" :name="file.type === 'pdf' ? 'pdf-preview-icon' : 'image-preview-icon'" />-->
        <div class="details">
          <app-typography class="truncate max-w-[230px]"  variant="body1">{{ file?.name }}</app-typography>
          <app-typography variant="body4" color="#36454F">{{ fileSize }}</app-typography>
        </div>
      </div>
      <div class="pointer" @click="removeFile">
        <image-svg width="18px" height="18px" name="cancel-icon" />
      </div>
    </div>
  </div>



</template>
<script>
import { AppTypography } from "@/components/index";
import ImageSvg from "../ImageSvg/imageSvg.vue";

export default {
  components: {
    ImageSvg,
    AppTypography
  },
  props: {
    file: {
      type: Object,
      required: true
    },
    isAddContributors: {
      type: Boolean,
      required: false,
      default: false
    },
    addText:{
      required:false,
      default: 'Add Contributors'
    }
  },
  computed: {
    fileSize() {
      const sizeInKB = (this.file?.size / 1024).toFixed(2);
      return `${sizeInKB} KB`;
    }
  },
  methods: {
    removeFile() {
      this.$emit('remove-file');
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
}
.contributor-card {
  border: 1px dashed #D0D3E8;
  display: grid;
  place-items: center;
}

.img-fluid {
  max-width: 50px;
  height: auto;
  border-radius: 5px;
}

.img-wrapper {
  display: flex;
  align-items: center;
}

.pointer {
  cursor: pointer;
}
.blue-box{
  height: 40px;
  width: 40px;
  display: grid;
  place-items: center;
  background: #c1d5ea;
  color: #4e5e6a;
}
</style>
