<template>
    <div class="card">
        <div class="card-header d-flex flex-column align-items-center bg-white">
            <app-typography variant="h4" fontWeight="500">
                {{ title }}
            </app-typography>
            <app-typography variant="body1" color="#777E90">
                {{ subTitle }}
            </app-typography>
        </div>
        <div class="card-body">
            <slot name="content"></slot>
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
        title: {
            type: String,
            default: 'card title'
        },
        subTitle: {
            type: String,
            default: 'card subtitle'
        }
    }
}
</script>

<style scoped>
.card {
    box-shadow: 2px 2px 12px 0px #0000000A;
    background-color: #ffff;
    border-radius: 10px;

}

.card-header {
    padding: 40px 36px;
    border-radius: 10px 10px 0 0;
}

.card-body {
    padding: 40px 36px;
}
</style>