<template>
  <auth-layout>
    <div v-if="loading" class="loader-main">
      <div class="loader"></div>
    </div>
  <div class="container-fluid">
        <div class="row gy-4">
            <div class="col-lg-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title" :isSubTitle="false"
                    :isActionButtons="false" :isUserName="false" :isDivider="true" />
            </div>
            <div class="col-lg-10 offset-lg-1">
              <add-bill-manual-card ref="addBillCard" @cancel="cancel" @formSubmit="handleFormSubmit" />

            </div>
            <div class="col-lg-12">
                <common-footer @back="handleBack" @continue="handleContinue" />
            </div>
        </div>
    </div>
  </auth-layout>
</template>

<script>
import { TitleActionBar, CommonFooter, AddBillManualCard } from "@/components/index";
export default {
    components: {
        TitleActionBar,
        CommonFooter,
        AddBillManualCard
    },
    data() {
        return {
            header: {
                title: "Add Bill"
            },
          formData: this.$inertia.form({
            visit_id: "",
            category: "",
            amount: "",
            description: "",
            invoice_number: "",
            due_date: "",
            already_paid: "",
            filePreview: this.$store.getters.getBillDoc,
          }),
          loading:false,
        }
    },
    methods: {
        handleBack() {
            window.history.back()
        },
      handleContinue() {

        this.$refs.addBillCard.submitForm();
      },

      handleFormSubmit(formData) {
        this.loading = true;
        this.formData = formData;
        this.$inertia.post('/store-bill',this.formData, {
          onFinish: () => {

          },
          onSuccess: () => {
            this.$toast('Bill added successfully!', 'success');
            this.loading = false;
            this.$inertia.visit('/expense-tracker')

          },
          onError: (errors) => {
            this.showErrors(errors);
            this.loading = false;
          }
        });
      },

      showErrors(errors) {
        if (errors && Object.keys(errors).length > 0) {
          Object.keys(errors).forEach(key => {
            this.$toast(`Error: ${errors[key]}`, 'error');
          });
        }
      },
      cancel(){
          window.history.back()
      }
    }
}
</script>

<style scoped></style>