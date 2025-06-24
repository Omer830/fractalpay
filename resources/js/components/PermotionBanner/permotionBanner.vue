<template>
    <div class="banner-wrapper">
        <image-svg class="img-responsive" :width="imgWidth" :height="imgHeight" :name="bannerImg" />
    </div>
</template>
<script>
import ImageSvg from "../ImageSvg/imageSvg.vue";

export default {
    components: {
        ImageSvg
    },
    props: {
        bannerImg: {
            type: String,
            default: "copy-addressoutline-icon"
        },
        imgWidth: {
            type: String,
            default: ""
        },
        imgHeight: {
            type: String,
            default: ""
        }
    }
}
</script>

<style scoped>
.banner-wrapper {
    display: grid;
    place-items: center;
    border-radius: 8px;
    box-shadow: 0px 8px 16px 0px #00000014;
    box-shadow: 0px 0px 4px 0px #0000000A;
    max-height: 120px;
    background-color: #4682BE;
    padding: 10px;
}
</style>