<template>
    <div class="card">
        <div class="header ">
            <app-typography variant="h4"> {{ cardTitle }}</app-typography>
            <app-typography variant="h6" fontWeight="400" color="#76848D"> {{ cardDesc }}</app-typography>
        </div>
        <div class="card-body px-0 py-0">
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th v-for="(i, k) in tableColumns" :key="k"
                            :style="{ backgroundColor: headerBgColor, color: headerTextColor }" class="p-3" scope="col">
                            {{ i }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in tableData" :key="index">
                        <td class="p-3 d-flex gap-3 align-items-center">
                            <image-svg width="36px" height="36px" :name="item.avatar" />
                            {{ item.name }}
                        </td>
                        <td class="p-3">
                            <div class="percent d-flex justify-content-center">
                                <app-typography variant="body1" fontWeight="400" color="#76848D"> {{ item.percentage
                                    }}</app-typography>

                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer px-4">
            <common-footer :divider="false" @back="handleBackClick" @continue="handleContinueClick" />
        </div>
    </div>
</template>

<script>
import CommonFooter from "../CommonFooter/commonFooter.vue";
import AppTypography from "../Typography/appTypography.vue";
import ImageSvg from "../ImageSvg/imageSvg.vue";

export default {
    components: {
        AppTypography,
        CommonFooter,
        ImageSvg
    },
    props: {
        cardTitle: {
            type: String,
            default: 'card title'
        },
        cardDesc: {
            type: String,
            default: 'card description'
        },
        headerBgColor: {
            type: String,
            default: '#F7FAFC'
        },
        headerTextColor: {
            type: String,
            default: '#36454F'
        },
        tableColumns: {
            type: Array,
            default: () => ["Name", "Dividend %"]
        },
        tableData: {
            type: Array,
            default: () => [
                { avatar: "user-avatar", name: "David Johnson", percentage: "20%" },
                { avatar: "user-avatar", name: "Emily Johnson", percentage: "10%" },
                { avatar: "user-avatar", name: "Samantha Johnson", percentage: "10%" },
                { avatar: "user-avatar", name: "Daniel Johnson", percentage: "10%" },
                { avatar: "user-avatar", name: "Lily Johnson", percentage: "0%" }
            ]
        }
    },
    methods: {
        handleBackClick() {
            this.$emit('back')
        },
        handleContinueClick() {
            this.$emit('continue')
        }
    }
}
</script>

<style scoped>
.card {
    box-shadow: 0px 8px 16px 0px #00000014;
    box-shadow: 0px 0px 4px 0px #0000000A;
    border-radius: 12px;
}

.header {
    padding: 20px 0px 30px 33px
}

.percent {
    border: 1px solid #4682BE;
    border-radius: 6px;
    padding: 3px 4px;
    max-width: 60px;
}

.card-footer {
    border: none;
    background-color: #ffff;
}
</style>