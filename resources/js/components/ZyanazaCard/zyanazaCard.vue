<template>
    <div class="position-relative wrapper my-5">
        <div class="card">
            <image-svg width="38px" height="27px" name="visa-card-icon" />
            <app-typography class="card-no my-4" color="#676767" variant="h5">{{ cardNo }}</app-typography>
            <div class="footer">
                <app-typography color="#B64129" variant="text-7" fontWeight="600">1234</app-typography>
                <div class=" d-flex justify-content-between">
                    <div>
                        <app-typography class="letter-space" color="#FFD8CF" variant="text-7">CARD
                            HOLDER</app-typography>
                        <app-typography color="#FBF9F9" variant="body3">{{ ownerName }}</app-typography>
                    </div>
                    <div>
                        <app-typography class="letter-space" color="#FFD8CF" variant="text-7">EXPIRE</app-typography>
                        <app-typography color="#FBF9F9" variant="body3">{{ expiryDate }}</app-typography>
                    </div>
                </div>
            </div>
        </div>
        <div class="cvv-card">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-sm cvv-btn">CVV</button>
                <button type="button" class="btn btn-sm">{{ cvvNo }}</button>
            </div>
        </div>
    </div>

</template>

<script>
import { AppTypography } from "@/components/index";
import ImageSvg from "../ImageSvg/imageSvg.vue";

export default {
    components: {
        ImageSvg,
        AppTypography
    },
    props: {
        cardNo: {
            type: String,
            default: 'XXXX XXXX XXXX 1156'
        },
        ownerName: {
            type: String,
            default: 'Zayanaza'
        },
        expiryDate: {
            type: String,
            default: '12/5'
        },
        cvvNo: {
            type: String,
            default: '123'
        }
    },

}
</script>

<style scoped>
.wrapper {
    max-width: 451px;
}

.card {
    background: linear-gradient(251.03deg, #3A84C6 2.18%, #FF8E76 98.66%);
    box-shadow: 0px 6.54px 13.07px 0px #00000014;
    border: 1px solid white;
    padding: 30px 30px;
    border-radius: 20px;
    max-width: 331px;
    height: 200px;
}

.cvv-card {
    box-shadow: 0px 6.54px 13.07px 0px #00000014;
    background-color: #4682BE;
    border: 1px solid white;
    border-radius: 20px;
    position: absolute;
    height: 200px;
    max-width: 331px;
    z-index: -1;
    right: 0;
    left: 120px;
    top: 0;
}

.card-no {
    text-shadow:
        1px 1px #fff,
        -1px 1px #fff,
        1px -1px #fff,
        -1px -1px #fff,
        1px 1px 5px #555;
}

.letter-space {
    letter-spacing: 4px;
}

.btn-group {
    background-color: #FFFFFF;
    position: absolute;
    right: 20px;
    top: 90px;
}

.cvv-btn {
    background-color: #D0D3E8;
    color: #09BB30;
    font-weight: 500;
}

@media screen and (max-width: 480px) {
    .cvv-card {
        position: absolute;
    }
}
</style>