<template>
    <div class="trasaction-wrapper">
        <div class="actions d-flex justify-content-between align-items-center" v-if="isActionHeader">
            <app-typography v-if="isTitle" class="my-3" variant="h6" fontWeight="600">
                {{ mainTitle }}
            </app-typography>
            <app-typography class="pointer" variant="body1" fontWeight="500" color="#3A84C6" @click="handleViewAll">
                View All
            </app-typography>
        </div>

        <div class="" :class="{ 'card' : noDataFound }">
            <div v-if="noDataFound" class="card-body no-data">
                <app-typography variant="body1" fontWeight="600">You don’t have any history here.</app-typography>
            </div>
            <div v-else >
             <common-table
                 :header="header"
                 :data="tableData"
                 headerBgColor="#ffff"
                 headerTextColor="#36454F"
                 tableHeight="45vh"
                 :paginationStatus="false"

             ></common-table>
            </div>
        </div>
    </div>
</template>

<script>
import { ReuseableButton, AppTypography } from "@/components/index";
import CommonTable from "@components/CommonTable/commonTable.vue";


export default {
    components: {
      CommonTable,
        ReuseableButton,
        AppTypography
    },
    props: {
        isActionHeader: {
            type: Boolean,
            default: true
        },
        isTitle: {
            type: Boolean,
            default: true
        },
        mainTitle: {
            type: String,
            default: 'Transaction History'
        },
      header: Array,
      tableData: Array,
        noDataFound: {
            type: Boolean,
            default: true
        }
    },
    methods: {
        handleViewAll() {
            this.$emit('view-all')
        }
    }
}
</script>

<style scoped>
.card {
    border-radius: 10px;
    min-height: 210px;
}

.action-btn {
    height: 34px !important;
    width: 88px !important;
    border: 1px solid #76848D;
    font-size: 16px !important;
}

.table-col {
    border-radius: 10px;
}

.no-data {
    display: grid;
    place-items: center;
}
</style>