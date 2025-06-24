<template>
    <div class="contibution-wrapper">
        <div class="card">
            <div class="header d-flex flex-column justify-content-center align-items-center">
                <div class="avatar-wrapper d-flex align-items-center justify-content-center">
                    <image-svg width="32px" height="32px" name="contributors-icon" />
                </div>
                <app-typography variant="body1" fontWeight="500" class="my-3">{{ cardTitle }}</app-typography>
            </div>
            <div class="max-h-[25vh] overflow-auto">
                <div class="empty" v-if="noDataFound">
                    <app-typography variant="body1" fontWeight="500">Empty</app-typography>
                </div>
                <table v-else class="table table-responsive mb-0">
                    <tbody v-for="(item, index) in tableData" :key="index">
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                  <img
                                      v-if="item.avatar"
                                      :src="item.avatar"
                                      alt="image"
                                      class="img-fluid rounded-circle"
                                      width="25"
                                  />
                                  <img
                                      v-else
                                      src="@/assets/images/img-placeholder.jpg"
                                      alt="image"
                                      class="img-fluid rounded-circle"
                                      width="25"
                                  />
                                    <app-typography variant="body4">{{ item.name }}</app-typography>
                                </div>
                            </td>
                            <td>
                                <app-typography variant="text-8" color="#AEAEAE">{{
                                    item.paymentPeriod }}</app-typography>
                            </td>
                            <td>
                                <app-typography variant="text-8" color="#AEAEAE">{{ item.amount }}</app-typography>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { AppTypography } from "@/components/index";
import ImageSvg from "../ImageSvg/imageSvg.vue";
export default {
    components: {
        AppTypography,
        ImageSvg
    },
    props: {
        cardTitle: {
            type: String,
            default: 'Contributors list'
        },
        tableData: {
            type: Array,
            default: () => [
                { avatar: 'user-avatar', name: 'Lucas', paymentPeriod: 'weekly', amount: '50$' },
                { avatar: 'user-avatar', name: 'David', paymentPeriod: 'Monthly', amount: '100$' },
                { avatar: 'user-avatar', name: 'Daniel', paymentPeriod: 'yearly', amount: '100$' },
                { avatar: 'user-avatar', name: 'Daniel', paymentPeriod: 'yearly', amount: '100$' },
                { avatar: 'user-avatar', name: 'Daniel', paymentPeriod: 'yearly', amount: '100$' },
                { avatar: 'user-avatar', name: 'Daniel', paymentPeriod: 'yearly', amount: '100$' },
                { avatar: 'user-avatar', name: 'Daniel', paymentPeriod: 'yearly', amount: '100$' },
                { avatar: 'user-avatar', name: 'Daniel', paymentPeriod: 'yearly', amount: '100$' },
                { avatar: 'user-avatar', name: 'Daniel', paymentPeriod: 'yearly', amount: '100$' },
                { avatar: 'user-avatar', name: 'Daniel', paymentPeriod: 'yearly', amount: '100$' },

            ]
        },
        noDataFound: {
            type: Boolean,
            default: false
        }
    }
}
</script>

<style scoped>
.card {
    box-shadow: 0px 0px 4px 0px #00000026;
    border-radius: 12px;
    padding: 20px 0px;
    height: 100%;

}

.empty {
    display: grid;
    place-items: center;
}

.avatar-wrapper {
    background-color: #DDEDF6;
    border-radius: 50px;
    height: 48px;
    width: 48px;
}
/* Scroll bar stylings */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

/* Track */
::-webkit-scrollbar-track {
  background: transparent;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #3A84C6;
  border-radius: 5px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #3A84C6;
}
tbody, td, tfoot, th, thead, tr{
  vertical-align: middle;
}
</style>