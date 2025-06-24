<template>
    <footer class="footer">
        <div class="row">
            <div class="col-xl-3 col-md-4 col-sm-12">
                <image-svg width="215" height="55" :name="footerLogo" />
                <div class="footer-icon my-4 d-flex gap-2">
                    <image-svg v-for="(item, index) in socialMediaIcons" :key="index" width="42" height="42"
                        :name="item.icon" />
                </div>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-12">
                <app-typography variant="body1" fontWeight="500" class="my-3">General</app-typography>
                <ul v-for="(item, index) in generalLinks" :key="index">
                    <li>
                        <router-link :to='item.path'>
                            <app-typography variant="body1">{{ item.name }}</app-typography>
                        </router-link>
                    </li>
                </ul>

            </div>
            <div class="col-xl-2 col-md-4 col-sm-12">
                <app-typography variant="body1" fontWeight="500" class="my-3">Connect</app-typography>
                <ul v-for="(item, index) in connectLinks" :key="index">
                    <li>
                        <router-link :to='item.path'>
                            <app-typography variant="body1">{{ item.name }}</app-typography>
                        </router-link>
                    </li>
                </ul>
            </div>
            <div class="col-xl-2 col-md-4 col-sm-12">
                <app-typography variant="body1" fontWeight="500" class="my-3">Legal</app-typography>
                <ul v-for="(item, index) in termsAndCon" :key="index">
                    <li>
                        <router-link :to='item.path'>
                            <app-typography variant="body1">{{ item.name }}</app-typography>
                        </router-link>
                    </li>
                </ul>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-12">
                <app-typography variant="body1">
                    Fractal Pay (Private) Limited is authorised and regulated by the State Bank of Australia as a
                    Electronic Money Institution.
                </app-typography>
                <div class="download-wrapper mt-5">
                    <app-typography variant="body1" color="#ED5637">Download the app now!</app-typography>
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-play">
                            <image-svg width="24" height="24" name="google-play" />
                            Google Play
                        </button>
                        <button class="btn btn-apple">
                            <image-svg width="23" height="28" name="apple" />
                            App Store
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <hr class="hr" />
        <div class="copy-right">
            <app-typography variant="body1" fontWeight="500" class="my-3">Fractal© 2023. All rights
                reserved.</app-typography>
        </div>
    </footer>
</template>
<script>
import { AppTypography } from "@/components/index";
import ImageSvg from "../ImageSvg/imageSvg.vue";
export default {
    components: {
        AppTypography,
        ImageSvg,
    },
    props: {
        footerLogo: {
            type: String,
            default: 'logo'
        },
        socialMediaIcons: {
            type: Array,
            default: () => [
                { icon: 'facebook-round' }, { icon: 'linkedin-round' }, { icon: 'twitter-round' }, { icon: 'instagram-round' },
            ]
        },
        generalLinks: {
            type: Array,
            default: () => [
                { name: 'Personal', path: '' },
                { name: 'Business', path: '' },
                { name: 'Help Center', path: '' },
                { name: 'Newsroom', path: '' },
                { name: 'Schedule of charges', path: '' },
            ]
        },
        connectLinks: {
            type: Array,
            default: () => [
                { name: 'About', path: '' },
                { name: 'Contact us', path: '' },
            ]
        },
        termsAndCon: {
            type: Array,
            default: () => [
                { name: 'Terms and Conditions', path: '' },
            ]
        }
    },
}
</script>
<style scoped>
.footer {
    background-color: #F4F7FA;
    padding: 66px 100px 20px 100px;
}

ul {
    padding: 0;
}

ul li {
    list-style: none;
}

li a {
    text-decoration: none;
}

.hr {
    border: 1px solid #000000
}

.btn-apple,
.btn-play {
    background-color: #000000;
    padding: 7px 11px;
    font-weight: 500;
    color: #ffffff;
}
</style>