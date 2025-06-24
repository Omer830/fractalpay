<template>
    <div class="card">
        <div class="title text-center">
            <app-typography variant="h5" color="#4682BE">{{ mainTitle }}</app-typography>
        </div>
        <div v-if="firstHeading || secondHeading" class="card-header d-flex justify-content-between">
            <div class="first" v-if="firstHeading">
                <app-typography variant="body1" color="#6E6E6E" fontWeight="500">{{ firstHeading }} </app-typography>
            </div>
            <div class="second" v-if="secondHeading">
                <app-typography variant="body1" color="#6E6E6E" fontWeight="500"> {{ secondHeading }} </app-typography>
            </div>
        </div>
        <div class="card-body">
            <div v-for="(item, index) in detailsArray" :key="index" class="d-flex justify-content-between detail">
                <app-typography class="title" variant="body1" color="#6E6E6E" fontWeight="700">{{ item.title }}
                </app-typography>
                <app-typography class="title" variant="body1" color="#6E6E6E" fontWeight="700">{{ item.detail }}
                </app-typography>
            </div>
        </div>
    </div>
</template>

<script>
import AppTypography from "../Typography/appTypography.vue";
export default {
    components: {
        AppTypography
    },
    props: {
        mainTitle: {
            type: String,
            default: 'Payers Details'
        },
        firstHeading: {
            type: String,
            default: ''
        },
        secondHeading: {
            type: String,
            default: ''
        },
        detailsArray: {
            type: Array,
            default: () => [
                { title: "Frequency", detail: "One Off" },
                { title: "Type", detail: "Bill" }
            ]
        }

    }
}
</script>

<style scoped>
.crard {
    box-shadow: 0px 4.67px 9.34px 0px #00000014;
    box-shadow: 0px 0px 2.33px 0px #0000000A;
    border-radius: 10px;
}

.title {
    padding: 20px 0px;
}

.card-header {
    background-color: #F1F6FA;
    padding: 12px auto;
}

.detail {
    border-bottom: 1px solid #DDEAF4;
}
</style>