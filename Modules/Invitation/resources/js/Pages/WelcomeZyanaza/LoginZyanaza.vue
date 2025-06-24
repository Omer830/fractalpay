<template>
    <form class="my-4" @submit.prevent="onSubmit">
        <div v-if="!isLogin" class="row d-flex justify-content-center align-items-center">
            <div class="header d-flex gap-2 mb-5">
                <app-typography variant="text-38" fontWeight="600">
                    {{ loginTitle }}
                </app-typography>
                <app-typography variant="text-38" fontWeight="600" color="#4682BE">
                    {{ appName }}
                </app-typography>
            </div>
            <div class="col-sm-12 mb-2">
                <form-group-input type="email" label="Email" image-source="mail" :width="24" :height="24"
                    v-model="user.email" :validation-rules="emailValidationRules" :error="emailError"
                    @blur="validateInput('email')" />
            </div>
            <div class="col-sm-12 mb-2">
                <form-group-input type="password" label="Password" image-source="eye" :width="20" :height="16"
                    v-model="user.password" :validation-rules="passwordValidationRules" :error="passwordError"
                    @blur="validateInput('password')" />
            </div>
            <div class="col-sm-12 mb-3 d-flex justify-content-end">
                <app-typography variant="body1">
<!--                    <router-link to="/forgotpassword" class="forgot-password">Forgot Password?</router-link>-->
                </app-typography>
            </div>
            <!-- buttons wrapper  -->
            <div class="col-sm-12 d-flex flex-column gap-3 align-items-center mt-2">
                <reuseable-button width="100" @click="onSubmit">Login </reuseable-button>
                <app-typography class="my-4 d-flex gap-1" variant="body1" fontWeight="500" color="#76848D">{{
                    registerText }}
<!--                    <router-link class="link" to="/signup">{{ registerLink }}</router-link>-->
                    <image-svg width="24" height="24" name="external-link-icon" />
                </app-typography>
            </div>
        </div>
        <div v-else>
            <welcome-back />
        </div>
    </form>
</template>
<script>
import { AppTypography, FormGroupInput, ReuseableButton, ImageSvg } from "@/components/index";
import WelcomeBack from "./WelcomeBack.vue";

export default {
    name: "LoginForm",
    components: {
        AppTypography,
        FormGroupInput,
        ReuseableButton,
        WelcomeBack,
        ImageSvg
    },
    data() {
        return {
            registerText: "Don’t have an account? ",
            registerLink: "Signup",
            user: {
                email: "",
                password: "",
            },
            emailValidationRules: [
                {
                    regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                    message: 'Please enter a valid email address.'
                }
            ],
            passwordValidationRules: [
                {
                    regex: /^.{6,}$/, // At least 6 characters long
                    message: 'Password must be at least 6 characters long.'
                }
            ],
            emailError: '',
            passwordError: '',
            isLogin: false,
            loginTitle: 'Login to',
            appName: 'Zyanaza',

        };
    },
    methods: {
        onSubmit() {
            const isEmailValid = !this.validateInput('email'); // Ensuring it returns 'true' for valid
            const isPasswordValid = !this.validateInput('password'); // Ensuring it returns 'true' for valid

            if (isEmailValid && isPasswordValid) {
                // this.isLogin = true
            } else {
                console.log("Validation errors", { emailError: this.emailError, passwordError: this.passwordError });
            }
        },

        validateInput(inputField) {
            switch (inputField) {
                case 'email':
                    return this.emailError = this.validateField(this.user.email, this.emailValidationRules);

                case 'password':
                    return this.passwordError = this.validateField(this.user.password, this.passwordValidationRules);
            }
        },
        validateField(value, rules) {
            for (const rule of rules) {
                if (!rule.regex.test(value)) {
                    return rule.message;
                }
            }
            return ''; // Empty string indicates no error
        }
    }
};
</script>
<style scoped>
.forgot-password {
    text-decoration: none;
    color: #4682BE
}

.divider {
    border: 1px solid #d0d3e8;
    width: 100%;
    margin: 0 15px;
}

.link {
    text-decoration: none;
    color: #4682BE;
    font-weight: 500 !important;
}
</style>