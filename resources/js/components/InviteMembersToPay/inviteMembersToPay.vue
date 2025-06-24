<template>
    <div class="container">
        <div class="row gy-3">
            <div class="col-sm-12">
                <form-group-input type="text" label="Name" v-model="formData.name" borderRadiusClass="border-sm" />
            </div>
            <div class="col-sm-12">
                <form-group-input type="email" label="Email Address" v-model="formData.email"
                    borderRadiusClass="border-sm" />
            </div>
        </div>
    </div>
</template>
<script>
import { FormGroupInput } from "@/components/index";
export default {
    components: {
        FormGroupInput,
    },
    data() {
        return {
            formData: {
                name: "",
                email: "",
            },

        }
    },
}
</script>
<style scoped></style>