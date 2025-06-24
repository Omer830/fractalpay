<template>
  <div class="container doc-wrapper">
    <div class="doc-header d-flex justify-content-between flex-wrap">
      <div class="text-wrapper">
        <app-typography variant="h1" fontWeight="600">
          {{ title }}
        </app-typography>
        <app-typography variant="body1" color="#76848D">
          {{ desc }}
        </app-typography>
      </div>
      <div class="btn-wrapper" v-if="completeBtn">
        <reuseable-button  :style="{ color: buttonTextColor + ' !important', border: '1px solid ' + buttonTextColor }" width="100" outline round="sm" fontSize="16px" :textColor="buttonTextColor"> Completed {{ kycStatus?.data?.total_points }} Points </reuseable-button>
        <app-typography variant="body2" color="#76848D" fontWeight="300" class="mt-1">{{ textSmall }}</app-typography>
      </div>
    </div>
    <app-typography variant="h5" fontWeight="600" class="my-3">{{ step }}</app-typography>
    <div class="row gx-5 gy-4">
      <div class="col-lg-6" v-for="(card, index) in cardsData" :key="index">
        <div class="card"  :class="{'is-uploaded': isUploaded(card.slug)}" @click="handleCardClick(card, index)">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div class="wrapper d-flex align-items-center gap-3">
              <div class="icon-wrapper">
                <image-svg v-bind="$attrs" :width="41" :height="51" :name="card?.attributes?.document_icons" class="icon" />
              </div>
              <div class="desc">
                <app-typography variant="body2" fontWeight="500" color="'#fffff">
                  {{ card.name }}
                </app-typography>
              </div>
            </div>
            <div class="d-flex align-items-center gap-2">
              <image-svg v-bind="$attrs" v-if="isUploaded(card.slug)" :width="15" :height="15" name="green-ticket-icon" class="icon" />
              <app-typography variant="body2" fontWeight="500" color="'#fffff" class="card-points text-end">
                {{ card?.attributes?.document_points }} points
              </app-typography>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <common-footer :isBackBtn="false" @continue="continueHandler" />
</template>

<script>
import { AppTypography, ReuseableButton,ImageSvg } from "@/components/index";
import CommonFooter from "../CommonFooter/commonFooter.vue";

export default {
  components: {
    AppTypography,
    ReuseableButton,
    CommonFooter,
    ImageSvg
  },
  inheritAttrs: false,
  emits: ['cardClick', 'headerBack', 'cardClicked', 'continue'],
  props: {
    title: {
      type: String,
      default: "Primary Documents",
    },
    desc: {
      type: String,
      default: "we need valid document to verify your identity.",
    },
    completeBtn: {
      type: Boolean,
      default: true,
    },
    kycStatus:Object,
    cardsData: [Array , Object]
  },
  data() {
    return {
      textSmall: "Minimum 100 Points Required ",
      step: "Take Your First Step",
    };
  },

  computed: {
    buttonTextColor() {
      const points = this.kycStatus?.data?.total_points;
      if (points >= 100) {
        return '#008000'; // Green color when points are 100 or more
      }
      return '#4682BE'; // Default color
    }
  },
  methods: {
    isUploaded(documentName) {
      return this.kycStatus?.data?.uploaded_documents.includes(documentName);
    },
    handleCardClick(card, index) {
      console.log(JSON.stringify(card) )
      this.$store.commit("SET_DOCUMENT_NAME",card );
      this.$emit('card-clicked',  {card, index });
      this.handleFetchDocuments(card.slug)
    },
    handleFetchDocuments(file_type) {
      this.$inertia.visit(`/document-upload`, {
        preserveState: true,
        preserveScroll: true,
        method: 'get',
        data: { file_type }, // Pass any necessary parameters
        onSuccess: page => {
          console.log('Documents fetched successfully', page);
        },
        onError: errors => {
          console.error('Error fetching documents', errors);
        }
      });
    },
    continueHandler() {
      this.$emit('continue')
    }
  }
};
</script>

<style scoped>
.doc-wrapper {
  max-width: 1028px;
}

.card {
  border-radius: 10px;
  height: 120px;
  box-shadow: 0px 0px 4px 0px #00000040;
  transition: background-color 0.3s ease;
}

.card:hover {
  background-color: #4682be;
}

.card:hover .card-body,
.card:hover .desc,
.card:hover app-typography {
  color: #ffffff;
}

.icon-wrapper {
  width: 60px;
}
.is-uploaded{
  color: #0E9D4B !important;
}

.card-points {
  min-width: 20%;
}

.desc {
  min-width: 60%;
}
</style>
