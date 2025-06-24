<template>
    <div class='card'>
        <div class="header d-flex justify-content-between border-0">
            <app-typography variant="body1" fontWeight="500" class="title" color="#ffffff">{{ headerTitle
                }}</app-typography>
            <div class="icon">
                <image-svg width="16px" height="16px" :name="headerIcon" />
            </div>
        </div>
        <div class="body">
            <slot></slot>
            <div v-if="isAdd" @click="handleFooterClicked" class="d-flex justify-content-between pointer">
                <app-typography variant="body1" fontWeight="500" color="#ffffff" class="footer-text">
                    {{ footerText }}
                </app-typography>
                <image-svg width="29px" height="29px" name="next-arrow-light-icon" />
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
            default: 'Zyanaza ID: 24579485'
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
    background-color: #4682BE;
    border-radius: 8px;
    height: 196px;
    border: none;
    padding: 16px 29px;
}

.icon {
    background-color: #009DB4;
    border-radius: 50px;
    place-items: center;
    height: 30px;
    width: 30px;
    display: grid;
}
</style>