<template>
    <div class="card d-flex gap-3">
        <div class="header d-flex justify-content-between align-items-center">
            <div class="title">
                <app-typography variant="h5" color="#4682BE" fontWeight="500">{{ title }}</app-typography>
            </div>
            <div class="d-flex align-items-center justify-content-center">
                <image-svg width="25px" height="22px" :name="icon" />
            </div>
        </div>
        <div class="footer d-flex justify-content-between align-items-center">
            <div class="amount">
                <app-typography variant="h5" color="#4682BE" fontWeight="500">Total</app-typography>
            </div>
            <div class="action">
                <app-typography variant="body1" color="#76848D">{{ percentage + "%" }}</app-typography>
            </div>
        </div>
    </div>
</template>

<script>
import { AppTypography } from "@/components/index";
import ImageSvg from "../ImageSvg/imageSvg.vue";
export default {
    components: {
        AppTypography,
        ImageSvg
    },
    props: {
        title: {
            type: String,
            default: 'Add to Wallet'
        },
        icon: {
            type: String,
            default: 'wallet-outline-icon'
        },
        percentage: {
            type: String,
            default: '40'
        },
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

.action {
    border: 1px solid #4682BE;
    border-radius: 6px;
    padding: 5px 15px;
}
</style>