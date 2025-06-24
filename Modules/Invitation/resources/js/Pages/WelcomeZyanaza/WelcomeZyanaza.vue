<template>
    <div class="container-fluid px-0">
        <div class="row m-0">
            <div class="col-lg-6 mx-0 px-0 d-lg-block d-none">
                <main-banner />
            </div>
            <div class="col-lg-6">
                <div v-if="!isConnect" class="content-wrapper d-flex flex-column align-items-center text-center gap-4">
                    <app-typography variant="text-38" fontWeight="600">
                        {{ title }}
                    </app-typography>
                    <app-typography variant="h6" color="#627279" class="desc">
                        {{ desc }}
                    </app-typography>
                    <!-- buttons wrapper  -->
                    <div class="col-sm-12 d-flex flex-column gap-3 align-items-center mt-2">
                        <reuseable-button width="75" fontSize="24px" @click="onConnect" btnHeight="79px"
                            round="md">Let's Connect
                        </reuseable-button>
                        <div class="w-75 d-flex align-items-center my-4">
                            <!-- Left divider -->
                            <div class="divider"></div>
                            <app-typography class="divider-text mb-4" variant="h6" color="#76848D">OR</app-typography>
                            <!-- Right divider -->
                            <div class="divider"></div>
                        </div>
                        <reuseable-button btnHeight="79px" fontSize="24px" width="75" :imageWidth="24" :imageHeight="24"
                            endIcon="external-link-icon" round="md" outline textColor="#4682BE">
                            Learn More
                        </reuseable-button>
                        <app-typography class="my-4" variant="body1" fontWeight="500" color="#76848D">{{ registerText }}
<!--                            <router-link to="/signup">{{ registerLink }}</router-link>-->
                        </app-typography>
                    </div>
                </div>
                <div v-else-if="isConnect" class="login-wrapper d-flex flex-column gap-4">
                    <login-zyanaza />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    MainBanner,
    AppTypography,
    ReuseableButton
} from "@/components/index";
import LoginZyanaza from "./LoginZyanaza.vue";
export default {
    components: {
        AppTypography,
        MainBanner,
        ReuseableButton,
        LoginZyanaza,
    },
    data() {
        return {
            title: 'Welcome to Zyanaza ',
            desc: 'Welcome to Zyanaza Wallet – your gateway to seamless transactions! As a trusted third-party wallet, Zyanaza effortlessly connects with Fractal Pay, grow your FractalPay Wallet with no efforts.',
            isConnect: false
        }
    },
    methods: {
        onConnect() {
            // this.isConnect = true
        }
    }
}
</script>

<style scoped>
.content-wrapper {
    padding: 200px 40px 0px 40px;
}

.desc {
    max-width: 638px;
}

.divider {
    border: 1px solid #d0d3e8;
    width: 100%;
    margin: 0 15px;
}

.login-wrapper {
    padding: 90px 80px 0px 80px
}
</style>