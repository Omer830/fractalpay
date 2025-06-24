<template>
    <div class="card d-flex gap-3">
        <div>
            <app-typography variant="body1" color="#ffff" fontWeight="500">{{ title }}</app-typography>
        </div>
        <div>
            <app-typography variant="h5" color="#57E794" fontWeight="700"> {{ "$" + amount }}</app-typography>
        </div>
        <div v-if="isFooter" class="d-flex justify-content-between align-items-center">
            <div class="id">
                <app-typography variant="body1" color="#ffff" fontWeight="500"> Zyanaza ID: {{ zyanazaId
                    }}</app-typography>
            </div>
            <div class="action-icon d-flex align-items-center justify-content-center">
                <image-svg width="16px" height="16px" :name="actionIcon" />
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
            default: 'Investment Wallet'
        },
        amount: {
            type: String,
            default: '1200.00'
        },
        isFooter: {
            type: Boolean,
            default: true
        },
        zyanazaId: {
            type: String,
            default: '24579485'
        },
        actionIcon: {
            type: String,
            default: 'copy-addressoutline-icon'
        },
    }
}
</script>

<style scoped>
.card {
    box-shadow: 0px 0px 4px 0px #00000040;
    background-color: #4682BE;
    padding: 18px 15px;
    border-radius: 8px;
}

.action-icon {
    background-color: #2894B4;
    border-radius: 50px;
    height: 30px;
    width: 30px;

}
</style>