<template>
  <auth-layout>
    <div v-if="loading" class="loader-main">
      <div class="loader"></div>
    </div>
    <div class="container-fluid mb-4">
      <div class="row gy-4">
        <div class="col-lg-12">
          <title-action-bar title="Summary" :isSubTitle="true" subTitle="see your bill detail" :isActionButtons="true" btnRound="md"
                            :isPrimaryBtn="false" :isSecondaryBtn="true" :isUserName="false" :isDivider="true"
                            textVariant="text-38" textWeight="600"
                            secondaryButtonText="View Details"
                            @secondary-click="handleViewDetail"
          />
        </div>

        <div class="card mt-4 flex justify-center m-auto w-[98%]">
          <div class="header ">
            <div class="row gy-4">

              <div class="col-lg-6 px-0">
                <div class="trans-info px-2 border-bottom">
                  <app-typography class="title" variant="h6" color="#4682BE" fontWeight="500">{{ transInfoTitle }}
                  </app-typography>
                </div>
                <div class="d-flex justify-content-between px-2"
                    >
                  <app-typography class="title" variant="body1" color="#6E6E6E" fontWeight="700">Amount
                  </app-typography>
                  <app-typography class="title" variant="body1" color="#6E6E6E" fontWeight="700">{{
                      "$" + billsSummary?.transaction_information?.amount }}
                  </app-typography>
                </div>
              </div>
              <div class="col-lg-6 px-0">
                <div class="trans-no flex justify-between px-2 border-bottom">
                  <app-typography
                      class="title"
                      variant="h6"
                      color="#6E6E6E"
                      fontWeight="700"
                  >
                    {{ transNumTitle }}
                  </app-typography>
                  <app-typography
                      class="title"
                      variant="h6"
                      fontWeight="700"
                      color="#6E6E6E"
                  >
                    {{ billsSummary?.transaction_information?.transaction_number }}
                  </app-typography>

                </div>
                <div class="d-flex justify-content-between px-2"
                     >
                  <app-typography class="title" variant="body1" color="#6E6E6E" fontWeight="700">Date
                  </app-typography>
                  <app-typography class="title" variant="body1" color="#6E6E6E" fontWeight="700">{{ billsSummary?.transaction_information?.date }}
                  </app-typography>
                </div>
              </div>


            </div>
          </div>
        </div>


        <div class="col-lg-6">
          <summary-card class="h-[35rem]" mainTitle="Payment Details" firstHeading="" secondHeading=""
                        :detailsArray="billSummaryArray"/>
        </div>
        <div class="col-lg-6">
          <summary-card class="h-[35rem]" mainTitle="Payment Method" firstHeading="Method"
                        secondHeading="Bank" :detailsArray="bpayArray"/>
        </div>
        <div class="col-lg-12">
          <common-footer @back="handleBack" :isContinueBtn="false" buttonTitle="Submit" />
        </div>


      </div>

    </div>

  </auth-layout>
</template>
<script>
import {
  TitleActionBar,
  AppTypography,
  SummaryCard, SummaryMainCard, CommonFooter
} from "@/components/index.js";
export default {
    components: {
      TitleActionBar,
      AppTypography,
      SummaryCard, SummaryMainCard, CommonFooter
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
            ],
        },


    },
  data() {
    return {
      id: null,
      contributorType:null,
      transNum:'1234',
      billsSummary: null,
      loading: false,
      billSummaryArray:[],
      bpayArray:[],
      header: {
        title: "Contribution Tree",
        primaryButtonText: "Invite",
        secondaryButtonText: "View Details",
        startIcon: 'plus-icon'
      },
    };
  },
  mounted() {
    const urlParams = this.$store.getters.getContributorDetail;
    if(urlParams){
      this.id = urlParams.id;
      this.contributorType = urlParams.type;
    }else {
      this.handleBack()
    }

    this.fetchBillSummary()
  },
  methods:{
    handleBack(){
      this.$inertia.visit('/contribution-detail')
    },
    fetchBillSummary() {
      this.loading = true;
        console.log('hit')
        this.$inertia.post('/contribution-summary', { id: this.id , contributer_type:this.contributorType }, {
          onSuccess: (page) => {

            this.billsSummary = page.props.commitmentDetails.data;
            const data = page.props.commitmentDetails.data;
            this.billSummaryArray = [
              { title: "Frequency", detail: data.payer_details.frequency },
              { title: "Name", detail: data.payer_details.reciever.name },
              { title: "Type", detail: data.payer_details.type },
            ];

            // Prepare bpayArray
            this.bpayArray = data.payment_method.attribute.map((attr) => ({
              title: attr.key.replace(/_/g, " ").replace(/^\w/, (c) => c.toUpperCase()), // Format key to title case
              detail: attr.value,
            }));
            this.loading = false;
          },
          onError: (errors) => {
            this.loading = false;
            console.error("An error occurred while submitting the bills:", errors);
            this.showErrors(errors);

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
    handleViewDetail(){
      this.$inertia.visit('/contribution-detail')
    }
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