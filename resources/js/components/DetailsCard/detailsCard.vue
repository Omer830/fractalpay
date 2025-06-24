<template>
    <div :class="{ 'card': true, 'card-hover': isAdd }">
        <div class="card-header d-flex justify-content-between">
            <app-typography variant="body1" fontWeight="500" class="title">{{ headerTitle }}</app-typography>
            <div class="icon">
                <image-svg width="16px" height="16px" :name="headerIcon" />
            </div>
        </div>
        <div class="card-body">
            <slot></slot>
        </div>
        <div v-if="isAdd" @click="handleFooterClicked" class="card-footer d-flex justify-content-between pointer">
            <app-typography variant="body1" fontWeight="500" color="#4682BE" class="footer-text">
                {{ footerText }}</app-typography>
            <image-svg width="28px" height="28px" name="next-arrow-icon" />
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
        headerTitle: {
            type: String,
            default: 'custome title'
        },
        headerIcon: {
            type: String,
            default: 'wallet-icon'
        },
        footerText: {
            type: String,
            default: 'View Details'
        },
        isAdd: {
            type: Boolean,
            default: true
        }
    },
    methods: {
        handleFooterClicked() {
            this.$emit('footer-action')
        }
    }
}
</script>
<style scoped>
.card {
    box-shadow: 0px 0px 4px 0px #00000026;
    border-radius: 8px;
    height: 184px;
    border: none;
}

.card-header {
    border: none;
    background-color: transparent;
}

.card-body,
.card-header {
    padding: 8px 30px;
}

.card-footer {
    background-color: #FCFCFC;
    border-radius: 0px 0px 8px 8px;
    padding: 8px 30px;
    border: none;
}

.icon {
    background-color: #F0F9F4;
    place-items: center;
    border-radius: 50px;
    height: 30px;
    width: 30px;
    display: grid;
}

.card-hover:hover,
.card-hover:hover .card-footer {
    background-color: #4682be;
}

.card-hover:hover .title,
.card-hover:hover .card-body,
.card-hover:hover app-typography {
    color: #ffffff !important;
}

.card-hover:hover .icon {
    background-color: #009DB4;
}

.card:hover .footer-text {
    color: #57E794 !important
}
</style>