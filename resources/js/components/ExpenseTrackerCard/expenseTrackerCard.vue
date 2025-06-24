<template>
    <div class="card d-flex gap-3" @click="addClick">
        <div class="header d-flex justify-content-between align-items-center">
            <div class="title">
                <app-typography variant="h5" color="#4682BE" fontWeight="500">{{ title }}</app-typography>
            </div>
            <div class="icon d-flex align-items-center justify-content-center">
                <image-svg width="14px" height="14px" :name="icon" />
            </div>
        </div>
        <div class="footer d-flex justify-content-between align-items-center">
            <div class="amount">
                <app-typography variant="body1"> {{ "$" + amount }}</app-typography>
            </div>
            <div class="action">
                <image-svg width="14px" height="14px" :name="actionIcon" />
            </div>
        </div>
    </div>
</template>

<script>
import { AppTypography, ImageSvg } from "@/components/index";
export default {
    components: {
        AppTypography,
        ImageSvg
    },
    props: {
        title: {
            type: String,
            default: 'Total Payable'
        },
        icon: {
            type: String,
            default: 'payable-icon'
        },
        amount: {
          type: [String, Number],
            default: '480'
        },
        actionIcon: {
            type: String,
            default: 'arrow-next-icon'
        }
    },
   methods:{
      clickAdd(){
        this.$emit('click')
      }
   }
}
</script>

<style scoped>
.card {
    box-shadow: 0px 0px 4px 0px #00000040;
    border: 1px solid #4682BE;
    border-radius: 10px;
    padding: 18px 15px;
}

.icon {
    box-shadow: 0px 0px 4px 0px #00000040;
    border: 1px solid #4682BE;
    border-radius: 50px;
    height: 24px;
    width: 24px;
}
</style>