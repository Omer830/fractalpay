<template>
    <div class="container">
        <!-- <form> -->
            <div class="row gy-3">
                <div class="col-sm-12">
                  {{formData.reasonOfVisit}}
                    <form-group-input
                        type="text"
                        label="Reason of Visit (20 Characters)"
                        maxlength="20"
                        v-model="formData.reasonOfVisit"
                        :value="formData.reasonOfVisit"
                        borderRadiusClass="border-sm" />
                </div>
                <div class="col-sm-12">
                    <textarea
                        class="form-control"
                        rows="5"
                        placeholder="Visit Details (Long Text)"
                        v-model="formData.visitDetails"
                    ></textarea>
                </div>
                <div class="col-lg-6">
                    <app-select
                        type="text"
                        label="Doctor Name"
                        :height="25"
                        :width="25"
                        borderRadiusClass="border-sm"
                        :options="selectDocOption"
                        v-model="formData.docName"
                        :value="formData.docName"
                    />
                </div>
                <div class="col-lg-6">
                    <app-select
                        type="text"
                        label="Provider Number"
                        :height="25" :width="25"
                        borderRadiusClass="border-sm"
                        :options="selectProviderOption"
                        v-model="formData.providerNumber"
                        :value="formData.providerNumber"
                    />
                </div>
                <div class="col-lg-6">
                    <app-select
                        type="text"
                        label="Hospital/Clinic Name "
                        :height="25" :width="25"
                        borderRadiusClass="border-sm"
                        :options="selectHospitalOption"
                        :value="formData.hospital"
                    />
                    <div class="contributors d-flex align-items-center gap-2">
                        <image-svg width="12px" height="12px" name="plus-rounded-success-icon" />
                        <app-typography variant="body1" color="#6EC392" class="py-1 pointer"
                            @click="handleAddContributors">
                            Add Contributors
                        </app-typography>
                    </div>
                </div>
                <div class="col-lg-6">
                    <app-select type="text" label="Visit Type" :height="25" :width="25" borderRadiusClass="border-sm"
                        :options="selectVisitOption" v-model="formData.visitType" :value="formData.visitType" />
                </div>
            </div>
        <!-- </form> -->
    </div>
</template>
<script>
import { FormGroupInput } from "@/components/index";
import AppSelect from "../Select/appSelect.vue";
import AppTypography from "../Typography/appTypography.vue";
import ImageSvg from "../ImageSvg/imageSvg.vue";
export default {
    components: {
        FormGroupInput,
        AppTypography,
        AppSelect,
        ImageSvg
    },
    data() {
        return {
            formData: {
                reasonOfVisit: "",
                visitDetails: "",
                docName: "",
                providerNumber: "",
                hospital: "",
                visitType: "",
            },
            selectDocOption: [
                { label: 'Usman khalid', value: '1' },
                { label: 'Muhammed Ali', value: '2' }
            ],
            selectProviderOption: [
                { label: '1234567', value: '1' },
                { label: '9876543 ', value: '2' }
            ],
            selectVisitOption: [
                { label: 'cash', value: '1' },
                { label: 'card ', value: '2' }
            ],
            selectHospitalOption: [
                { label: 'Sheikh Zaid', value: '1' },
                { label: 'Ali Fatima ', value: '2' }
            ],
        }
    },
    methods: {
        handleAddContributors() {
          this.$inertia.visit('add-contributors')
        }
    }
}
</script>
<style scoped></style>