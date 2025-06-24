<template>
    <div class="card">
        <div class="header ">
            <div class="row">
                <div class="col-lg-6 px-0">
                    <div class="trans-info px-2 border-bottom">
                        <app-typography class="title" variant="h5" color="#4682BE" fontWeight="500">{{ transInfoTitle }}
                        </app-typography>
                    </div>
                    <div class="d-flex justify-content-between px-2" v-for="(item, index) in transInfoArray"
                        :key="index">
                        <app-typography class="title" variant="body1" color="#6E6E6E" fontWeight="700">{{ item.title }}
                        </app-typography>
                        <app-typography class="title" variant="body1" color="#6E6E6E" fontWeight="700">{{
                            "$" + item.amount }}
                        </app-typography>
                    </div>
                </div>
                <div class="col-lg-6 px-0">
                    <div class="trans-no px-2 border-bottom">
                        <app-typography class="title" variant="h5" color="#6E6E6E" fontWeight="700"> {{ transNumTitle }}
                        </app-typography>
                    </div>
                    <div class="d-flex justify-content-between px-2" v-for="(item, index) in transNumArray"
                        :key="index">
                        <app-typography class="title" variant="body1" color="#6E6E6E" fontWeight="700">{{ item.title }}
                        </app-typography>
                        <app-typography class="title" variant="body1" color="#6E6E6E" fontWeight="700">{{ item.date }}
                        </app-typography>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import AppTypography from "../Typography/appTypography.vue"
export default {
    components: {
        AppTypography
    },
    props: {
        transInfoTitle: {
            type: String,
            default: "Transaction Information"
        },
        transNumTitle: {
            type: String,
            default: "Transaction Number"
        },
        transInfoArray: {
            type: Array,
            default: () => [
                { title: "Amount", amount: "200" }
            ]
        },
        transNumArray: {
            type: Array,
            default: () => [
                { title: "Date", date: "02/03/2024" }
            ]
        },
    }
}
</script>

<style scoped>
.card {
    box-shadow: 0px 4.67px 9.34px 0px #00000014;
    box-shadow: 0px 0px 2.33px 0px #0000000A;
    border-radius: 10px;
    padding: 0px 12px;
}

.trans-no {
    background-color: #F1F6FA;
    border-radius: 0px 10px 0px 0px;
}

.title {
    padding: 20px 0px;
}
</style>