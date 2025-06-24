<template>
    <div class="welcome-family-card container">
        <div class="welcome-content text-center">
            <app-typography variant="h2" color="#4682BE" fontWeight="500">{{ welcomeText }}</app-typography>
            <app-typography variant="body1" color="#76848D" class="desc my-3">{{ welcomeDesc }}</app-typography>
            <app-typography variant="body1" color="#76848D" class="sub-desc my-3">{{ welcomeSubText }}</app-typography>
        </div>
        <div class="user-wrapper">
            <div class="avatar mt-5 mb-4">
                <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle img-fluid"
                    alt="Avatar" />
            </div>
            <div class="button-modal">
                <div class="btn-wrapper d-flex gap-3 justify-content-center flex-wrap">
                    <reuseable-button class="px-5" outline @click="handleLogin">Login</reuseable-button>
                    <reuseable-button class="px-5" @click="handleSignup">Signup</reuseable-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { AppTypography, ReuseableButton } from "@/components/index";
export default {
    components: {
        AppTypography,
      ReuseableButton,
    },
    data() {
        return {
            welcomeText: 'Welcome to Fractal Family',
            welcomeDesc: 'You are one step Closer to helping Kyle with his medical expenses through Fractal Pay. Only a few short steps to go.',
            welcomeSubText: 'Your readiness to support them in their time of need speaks volumes of your kindness and compassion. Thank you for taking this significant step.'
        }
    },
    methods: {
        handleLogin() {
          this.$inertia.visit('/');
        },
        handleSignup() {
            this.$inertia.visit('/signup');
        }
    }
}
</script>
<style scoped>
.welcome-family-card {
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    background-color: #ffff;
    padding: 23px 29px;
    border-radius: 5px;
    max-width: 1056px;
}

.sub-desc {
    max-width: 690px;
    margin: auto;
}

.avatar {
    border: 5px solid #4682BE;
    border-radius: 50%;
    height: 113px;
    width: 113px;
    padding: 5px;
    margin: auto;
}

.button-modal {
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    background-color: #ffff;
    padding: 50px 16px;
    border-radius: 28px;
    max-width: 560px;
    margin: auto;
}
</style>