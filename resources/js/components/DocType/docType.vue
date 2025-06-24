<template>
  <logo-header @click="handleBack" />
  <div class="btn-wrapper ml-auto mr-4 w-[200px] mb-3" >
    <reuseable-button  :style="{ color: buttonTextColor + ' !important', border: '1px solid ' + buttonTextColor }" width="100" fontSize="16px" outline round="sm" :textColor="buttonTextColor"> Completed {{ kycStatus?.data?.total_points }} Points </reuseable-button>
    <app-typography variant="body2" color="#76848D" fontWeight="300" class="mt-1 ml-2">{{ textSmall }}</app-typography>
  </div>
  <div class="doc-wrapper mb-5">
    <div class="container">
      <div class="row gy-5 gx-5">
        <div :class="getCardClasses(index)" v-for="(card, index) in cardData" :key="index">
          <div class="card text-center" @click="cardClickHandler(card)">
            <div class="card-body d-flex flex-column gap-4">
              <div class="icon-wrapper d-flex align-items-center justify-content-center">
                <image-svg :width="card.image.width" :height="card.image.height" :name="card.image.icon" />
              </div>
              <app-typography variant="h5" fontWeight="500" color="'#fffff">{{ card.title }}</app-typography>
              <app-typography class="desc" variant="body3" fontWeight="500" color="'#fffff">{{ card.desc
                }}</app-typography>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<!--  <common-footer :isBackBtn="false" @continue="continueHandler" />-->
</template>

<script>
import {LogoHeader, AppTypography, ReuseableButton} from "@/components/index";
import ImageSvg from "../ImageSvg/imageSvg.vue";
import CommonFooter from "../CommonFooter/commonFooter.vue";
export default {
  name: "doc-type",
  components: {
    ReuseableButton,
    CommonFooter,
    AppTypography,
    LogoHeader,
    ImageSvg,
  },
  data() {
    return {
      textSmall: "Minimum 100 Points Required ",
      kycStatus:null,
    };
  },
  props: {
    cardData: {
      type: Array,
      default:
        () => [
          {
            image: {
              icon: "primary_doc",
              height: 38,
              width: 39
            },
            title: "Primary Documents",
            desc:
              "The 100 Point Identification Check must be lodged with the completed application to the NSW Ministry of Health Central Register.",
          },
          {
            image: {
              icon: "secondary_doc",
              height: 36,
              width: 37
            },
            title: "Secondary Documents",
            desc:
              "The 100 Point Identification Check must be lodged with the completed application to the NSW Ministry of Health Central Register.",
          },
          {
            image: {
              icon: "other_doc",
              height: 34,
              width: 36
            },
            title: "Other Documents",
            desc:
              "The 100 Point Identification Check must be lodged with the completed application to the NSW Ministry of Health Central Register.",
          },
        ],
    },
  },
  mounted() {
    this.getKycStatus()
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
    getCardClasses(index) {
      return {
        "col-lg-6": index !== 2,
        "offset-lg-3 col-lg-6": index === 2,
      };
    },
    continueHandler() {
      this.$emit('continue')
    },
    cardClickHandler(card) {
      this.$store.commit('SET_DOC_TYPE', card.title);
      this.$emit("card-click", card);
    },
    handleBack() {
      this.$emit('header-back')
    },
    getKycStatus() {
      this.loading = true;
      this.$inertia.get('/doc-type', {}, {
        preserveState: true,
        onSuccess: (page) => {
          this.loading = false
          this.kycStatus = page.props.kycStatus;

        }
      });
    }
  },
};
</script>

<style scoped>
.doc-wrapper {
  max-width: 1028px;
  margin: auto;
}

.card {
  box-shadow: 0px 3px 8px -1px rgba(50, 50, 71, 0.05);
  box-shadow: 0px 0px 1px 0px rgba(12, 26, 75, 0.24);
  border: 1px solid rgba(70, 130, 190, 0.17);
  transition: background-color 0.3s ease;
  border-radius: 16px;
  padding: 20px 20px 30px 20px;
  max-width: 350px;
  margin: auto;
}

.card:hover {
  background-color: #4682be;
}

.card:hover .card-body,
.card:hover .desc,
.card:hover app-typography {
  color: #ffffff;
}

.card:hover .icon-wrapper {
  background-color: #ffffff;
}

.icon-wrapper {
  border: 1px solid rgba(70, 130, 190, 0.17);
  border-radius: 50%;
  height: 69px;
  width: 69px;
  margin: auto;
}

.icon-wrapper img {
  text-align: center;
}

.desc {
  max-width: 310px;
  margin: auto;
}
</style>
