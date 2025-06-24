
<template>
    <div v-if="isVisible" :class="{ 'extended-modal': modelExtend }" class="modal-overlay" @click.self="closeModal">
        <div :style="{ width: modalWidth, background: modalBgColor }" class="modal-content" @click.stop>
            <div class="flex">
                <div class="w-full">
                    <div class="modal-header p-2">
                        <div class="border-bottom d-flex justify-content-between w-100 align-items-center pb-2">
                            <div class="heading">
                                <app-typography variant="h4" fontWeight="500">{{ title }}</app-typography>
                                <app-typography v-if="subTitle" variant="body3" color="#777E90">{{ subTitle
                                    }}</app-typography>
                            </div>
                            <div class="pointer" @click="closeModal">
                                <image-svg width="31px" height="31px" name="cross-icon" />
                            </div>
                        </div>
                    </div>
                    <form @submit.prevent="submitForm">
                        <div class="modal-body py-2">
                            <slot></slot>
                            <div v-if="footerBtnStatus" class="modal-footer d-flex justify-content-end gap-3 pt-3" name="footer">
                                <reuseable-button v-if="isBackBtn" class="px-5" @click="closeModal" outline
                                    textColor="#4682BE" round="md">Close</reuseable-button>
                                <reuseable-button type="submit" class="px-5" round="md">{{ buttonTitle }}</reuseable-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AppTypography from "../Typography/appTypography.vue";
import ReuseableButton from "../Button/reuseableButton.vue";
import ImageSvg from "../ImageSvg/imageSvg.vue";

export default {
    components: {
        AppTypography,
        ReuseableButton,
        ImageSvg
    },
    props: {
        isVisible: {
            type: Boolean,
            default: false
        },
        title: {
            type: String,
            required: true,
            default: 'New Modal'
        },
        subTitle: {
            type: String,
            default: ''
        },
        modelExtend: {
            type: Boolean,
            default: false
        },
      footerBtnStatus: {
            type: Boolean,
            default: true
        },
        iconName: {
            type: String,
            default: ''
        },
        iconBgColor: {
            type: String,
            default: '#F0B016'
        },
        modalWidth: {
            type: String,
            default: '500px' // Ensure there's a default value
        },
        modalBgColor: {
            type: String,
            default: 'white'
        },
        isDisable: {
            default: ''
        },
        buttonTitle: {
            type: String,
            default: 'Submit'
        },
        isBackBtn: {
            type: Boolean,
            default: true
        }
    },
    emits: ['update:isVisible', 'SUBMIT'],
    methods: {
        closeModal() {
          this.$emit("update:isVisible", false);
        },
        submitForm() {
            this.$emit('SUBMIT');
        }
    }
}
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 998;

}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 90%;
    transition: width 0.3s ease-in-out;
}

.extended-modal .modal-content {
    max-width: 800px;
    /* Adjust as needed */
}

.modal-header {
    border-bottom: 0;
}

.modal-footer {
    border-top: 0;
}

.icon-bg-color {
    padding: 0;
    border-radius: 50%;
    margin-right: 5px;
    height: 30px;
    width: 30px;
    display: grid;
    place-items: center;
}

.modal-title {
    font-size: 16px;
    color: #667085;
    font-family: Oswald;
    font-weight: normal;
    text-transform: uppercase;
}

.icon-button {
    fill: white;
    border: none;
    border-radius: 25px 0 25px 0;
    cursor: pointer;
    font-size: 14px;
    outline: none;
    box-shadow: 0 2px 2px lightgray;
    transition: background-color 0.3s, box-shadow 0.3s;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    padding: 5px 10px;
    color: #fff;
    background-color: #43b02a;
    height: 43px;
    width: 70px;
    gap: 8px;
}
</style>
