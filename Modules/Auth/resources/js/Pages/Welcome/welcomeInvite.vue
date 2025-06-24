<template>
    <logo-header />
    <div v-if="!isHelpCard && !isMoreInfo" class="container">
        <div class="main-card p-3">
            <app-typography variant="text-60" fontWeight="700" color="#FFFFFF">
                {{ title }}
            </app-typography>
        </div>
        <div class="details-card my-4">
            <div class="div" v-for="(item, index) in cardDetails" :key="index">
                <div class="header-wrapper d-flex justify-content-between align-items-center">
                    <app-typography variant="h4" fontWeight="500" color="#4682BE">
                        {{ item.title }}
                    </app-typography>
                    <image-svg width=25 height=25 :name='item.icon' />
                </div>
                <hr class="horizontal-line" />
                <div v-if="item.coloredTexts" class="content">
                    <div v-for="(text, i) in item.coloredTexts" :key="i"
                        class="d-flex align-items-center mb-3 flex-wrap">
                        <app-typography variant="body1" color="#4682BE" fontWeight="500" class="me-2">
                            {{ text }}:
                        </app-typography>
                        <app-typography variant="body1" fontWeight="500">{{ item.simpleTexts[i] }}</app-typography>
                    </div>
                </div>
                <app-typography v-else variant="body1" class="content">
                    {{ item.desc }}
                </app-typography>
            </div>
            <div class="card-footer text-center">
                <app-typography variant="body2" fontWeight="500">
                    {{ footerContent }}
                </app-typography>
                <app-typography variant="body2" fontWeight="500">
                    Oceans of Gratitude,
                </app-typography>
                <div class="btn-wrapper d-flex gap-3 justify-content-center mt-5 flex-wrap">
                    <reuseable-button @click="handleMoreInfo" outline class="px-4" textColor="#4682BE">More info</reuseable-button>
                    <reuseable-button @click="handleHelpClick" class="px-4">Happy to Help</reuseable-button>
                </div>
            </div>
        </div>
    </div>
    <div v-else-if="isHelpCard">
        <welcome-family />
    </div>
    <div v-else>
        <welcome-video-invite />
    </div>
</template>

<script>
import { LogoHeader, AppTypography, ImageSvg, ReuseableButton } from "@/components/index";
import WelcomeFamily from "./welcomeFamily.vue";
import WelcomeVideoInvite from "./welcomeVideoInvite.vue";

export default {
    components: {
        WelcomeVideoInvite,
        ReuseableButton,
        AppTypography,
        WelcomeFamily,
        LogoHeader,
        ImageSvg
    },
    props: {
        title: {
            type: String,
            default: 'WELCOME TO FRACTAL PAY'
        },
        cardDetails: {
            type: Array,
            default: () => [
                {
                    title: 'Join Harold’s Healthcare Journey',
                    desc: `I hope this message finds you well. Your family member,[Harold Johnson], is currently
                    navigating their healthcare journey and has chosen Fractal Pay to manage their medical expenses.
                    They have reached out to you through our platform, seeking your support.`,
                    icon: 'invitation-mail'
                },
                {
                    title: 'How You Can Help!',
                    coloredTexts: ['One Time Contribution', 'Regular Contribution'],
                    simpleTexts: ['You can make a single payment to help with their immediate medical expenses.',
                        'Alternatively, yo can set up regular payments to provide ongoing support.'],
                    icon: 'interogation-round'
                },
                {
                    title: 'Invite Loved Ones to Join Your Contribution Tree',
                    coloredTexts: [' Sign Up', 'Choose Your Contribution', 'Stay Connected'],
                    simpleTexts: ['Create your account on fractal pay in just a few simple steps.',
                        ' Choose Your Contribution: Decide on a one-time payment or set up a recurring contribution.',
                        'Track your support is making a difference right from your dashboard.'],
                    icon: 'settings'
                },
            ],
        },
        footerContent: {
            type: String,
            default: `Your support can make a significant difference in Harold's healthcare journey. 
            Every contribution, big or small, helps in alleviating the financial stress of medical expenses.`
        }
    },
    data() {
        return {
            isHelpCard: false,
            isMoreInfo: false,
          refereeCode: null,
          user: this.$inertia.form({
            referee_code: '',

          }),

        }
    },
  created() {
    // Get the code from the URL
    this.refereeCode = this.getUrlParameter('code');

    if (this.refereeCode) {
      this.openInvitation(this.refereeCode);
    }
  },
    methods: {
        handleHelpClick() {
            this.isHelpCard = true;
        },
        handleMoreInfo() {
            this.isMoreInfo = true;
        },
      getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
      },
      openInvitation(code) {
          this.user.referee_code = code
        this.loading = true;
        this.user.post('v1/invitation/openInvitation', {
          onSuccess: () => {
            this.$toast('Invitation opened successfully', 'success');
          },
          onFinish: () => {
            this.loading = false;
          },
          onError: (error) => {
            this.$toast(`Error: ${error.message || 'An error occurred'}`, 'error');
            console.log('Error opening invitation:', error);
          }
        });
      },
    }
};
</script>

<style scoped>
.main-card {
    background-image: url('@/assets/images/welcome-banner.png');
    background-repeat: no-repeat;
    background-size: cover;
    border-radius: 20px;
    place-items: center;
    height: 500px;
    display: grid;
}

.details-card {
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    background-color: #ffff;
    padding: 23px 29px;
    border-radius: 10px;
}

.content {
    padding: 23px 0px 30px 0px;
    max-width: 1025px;
}

.card-footer {
    max-width: 860px;
    margin: auto;
}

.horizontal-line {
    margin: 9px 0px;
}
</style>