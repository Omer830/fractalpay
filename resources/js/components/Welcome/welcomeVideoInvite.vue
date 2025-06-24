<template>
    <div class="video-invite-wrapper">
        <div class="video-section">
            <iframe width="1128" height="514" :src="iframeSrc" title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
        <div class="invitation-card my-4">
            <div class="div" v-for="(item, index) in senderDetails" :key="index">
                <div v-if="!isClickNo">
                    <div class="header-wrapper d-flex justify-content-between align-items-center">
                        <app-typography variant="body1" fontWeight="600">
                            {{ item.title }}
                        </app-typography>
                        <image-svg width=25 height=25 :name='item.icon' />
                    </div>
                    <hr class="horizontal-line" />
                    <div class="invite-modal text-center my-5">
                        <div class="content">
                            <app-typography v-if="!isClickYes" variant="body1" fontWeight="500" color="#3A84C6">
                                {{ modalMsg }}</app-typography>
                        </div>
                    </div>
                </div>
                <div v-else v-for="(item, index) in alternateSenderDetails" :key="index">
                    <div class="header-wrapper d-flex justify-content-between align-items-center">
                        <app-typography variant="body1" fontWeight="600">
                            {{ item.title }}
                        </app-typography>
                    </div>
                    <hr class="horizontal-line" />
                    <div class="invite-modal text-center my-5">
                        <div class="content">
                            <app-typography v-if="!isClickYes" variant="body1" fontWeight="500" color="#3A84C6">
                                {{ item.desc }}</app-typography>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <reuseable-button outline class="px-5" @click="handleSignup" textColor="">Signup</reuseable-button>
                    </div>
                </div>
                <div class="btn-wrapper">
                    <div v-if="!isClickNo" class="d-flex flex-wrap gap-3 justify-content-center">
                        <reuseable-button  outlien class="px-5">Yes</reuseable-button>
                        <reuseable-button @click="handleNo" outline class="px-5" textColor="">No</reuseable-button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ImageSvg, AppTypography, ReuseableButton } from "@/components/index";
export default {
    components: {
        AppTypography,
        ReuseableButton,
        ImageSvg,
    },
    props: {
        iframeSrc: {
            type: String,
            default: 'https://www.youtube.com/embed/IVAWBpeNAmE?si=UOK11A7BUe9Hm3s-'
        },
        senderDetails: {
            type: Array,
            default: () => [
                { title: 'Kyle sent you an invitation', icon: 'interogation-round' }
            ]
        },
        modalMsg: {
            type: String,
            default: `Thank you for taking the time to consider [Kyle]'s request for assistance with his Medical Bills through Fractal Pay. Would you like to help Kyle with his medical expenses?`
        },
    },
    data() {
        return {
            isClickNo: false,
            alternateSenderDetails: [
                { title: 'We understand!',
                desc:'We understand that you are not ready to help Kyle right now, however we can help you to manage your medical expenses better.' }
            ]
        }
    },
    methods: {
        handleNo() {
            this.isClickNo = true;
        },
        handleSignup(){
            this.$router.push('/signup'); 
        }
    }
}
</script>
<style scoped>
.video-section {
    display: grid;
    place-items: center;
}

.invitation-card {
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    background-color: #ffff;
    padding: 24px 24px 50px 24px;
    border-radius: 5px;
    max-width: 1128px;
    margin: auto;
}

.invite-modal {
    border-top: 2px solid #D0D3E8;
    border-bottom: 3px solid #D0D3E8;
}

.content {
    padding: 22px 106px;
}
</style>
