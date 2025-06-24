<template>
  <auth-layout>
    <div v-if="loading" class="loader-main">
      <div class="loader"></div>
    </div>
    <div class="container-fluid d-flex flex-column gap-5 mb-4">
        <div class="row gy-4">
            <div class="col-md-12">
                <title-action-bar :userName="userData.name" :isActionButtons="true" :isPrimaryBtn="true"
                    :subTitle="header.subTitle" :isSecondaryBtn="true" @secondary-click="handleDepositeFunds"
                    @primary-click="handleInvite" />
            </div>
            <div class="col-md-12">
                <div class="row gy-4">
                    <div class="col-sm-12 col-md-6 col-lg-4 ">
                        <details-card headerTitle="Wallet" @footer-action="hanldeFooterAction">
                          <app-typography variant="h5" color="#57E794" fontWeight="700">
                            {{ displayAmount }} <!-- Use computed property here -->
                          </app-typography>
                          <div class="content d-flex justify-content-between">
                            <app-typography class="flex gap-2 items-center" variant="body2" fontWeight="500">
                              <span>{{ displayBalanceType === 'spendable' ? 'Available' : 'Spendable' }}</span>
                              <span class="cursor-pointer" @click="toggleBalanceDisplay">
                              <image-svg  width="20px" height="15px" name="refresh-icon"/>
                                </span>
                            </app-typography>

<!--                                <div class="d-flex gap-2">-->
<!--                                    <app-typography variant="body3" fontWeight="500">Wallet ID:-->
<!--                                        24579485</app-typography>-->
<!--                                    <image-svg width="16px" height="16px" name="copy-address-icon" />-->
<!--                                </div>-->
                            </div>
                        </details-card>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4 ">
                        <details-card v-if="!insurance" headerTitle="Insurance Premiums" headerIcon="insurance-icon"
                            @footer-action="hanldeFooterAction" :isAdd="false">
                            <div class="content d-flex flex-column justify-content-center align-items-center">
                                <button @click="handleAddInsurance"
                                    class="add-btn my-4 d-flex align-items-center justify-content-center gap-2">
                                    <image-svg width="16px" height="16px" name="plus-icon" />
                                    <app-typography variant="body1" color="#3A84C6">Add insurance</app-typography>
                                </button>
                            </div>
                        </details-card>
                      <details-card v-else headerTitle="Insurance Premiums" headerIcon="insurance-icon" @footer-action="handleAddInsurance">


                        <div class="content d-flex justify-content-between">
                          <app-typography  class="flex gap-2 items-center" variant="body2" fontWeight="500">
                            <span>Total Paid</span>

                          </app-typography>
                          <div class="d-flex gap-2">
                            <app-typography variant="body2"  fontWeight="500">
                              {{ insurance.terms }}
                            </app-typography>

                          </div>
                        </div>
                        <app-typography variant="h5" color="#57E794" fontWeight="700">
                          {{ insurance.amount }}
                        </app-typography>
                      </details-card>

                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4 ">
                        <details-card v-if="!latestCommitment" headerTitle="Commitements" headerIcon="commitments-icon"
                            @footer-action="hanldeFooterAction" :isAdd="false">
                            <div class="content d-flex flex-column justify-content-center align-items-center">
                                <button @click="handleAddContributors"
                                    class="add-btn my-4 d-flex align-items-center justify-content-center gap-2">
                                    <image-svg width="16px" height="16px" name="plus-icon" />
                                    <app-typography variant="body1" color="#3A84C6">Add contributors</app-typography>
                                </button>
                            </div>
                        </details-card>
                      <details-card v-else headerTitle="Commitements" headerIcon="last-commitment" @footer-action="handleLastCommitment">


                        <div class="content d-flex justify-content-between">
                          <app-typography  class="flex gap-2 items-center" variant="body2" fontWeight="500">
                            <span>{{ latestCommitment.frequency }}</span>

                          </app-typography>
                          <div class="d-flex gap-2">
                            <app-typography variant="body2"  fontWeight="500">
<!--                              {{ latestCommitment.amount }}-->
                            </app-typography>

                          </div>
                        </div>
                        <app-typography variant="h5" color="#57E794" fontWeight="700">
                          {{ latestCommitment.amount }}
                        </app-typography>
                      </details-card>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-8 col-lg-9">
                <wallet-history mainTitle="Wallet History"
                                :walletHistory="walletHistory"
                                :noDataFound="noDataFound"
                                class="h-100"
                                :latestBills="latestBills"
                                :pendingBills="pendingBills"
                />
            </div>
            <div class="col-sm-12 col-md-4 col-lg-3">
                <contribution-list-card :tableData="contributorsList" :noDataFound="noDataFoundContributorsList" class="h-100" />
            </div>
            <div class="col-sm-12 col-md-8 col-lg-9 mt-0">
              <transaction-history
                  :mainTitle="'Transaction History'"
                  :header="tableColumns"
                  :tableData="transactionsList"
                  :noDataFound="!transactionsList.length"

                  @view-all="handleViewAll" />
            </div>
            <div class="col-sm-12 col-md-4 col-lg-3 mt-[56px]">
                <payment-analytics-card class="h-100" />
            </div>
        </div>
    </div>
  </auth-layout>
</template>
<script>
import {
    TitleActionBar, DetailsCard, AppTypography,
    ImageSvg, WalletHistory, ContributionListCard, PaymentAnalyticsCard ,TransactionHistory
} from "@/components/index";
import {usePage} from "@inertiajs/vue3";
const userData = usePage();
export default {
    components: {
        PaymentAnalyticsCard,
        ContributionListCard,
      TransactionHistory,
        AppTypography,
        TitleActionBar,
        WalletHistory,
        DetailsCard,
        ImageSvg,
    },
    data() {
        return {
          userData: userData.props.auth.user,
          profilePic: userData.props.auth.profilePic,
          token: userData.props.auth.token,
            header: {
                user: "Harold Johnson",
                subTitle: "Your Journey to Healthcare Finance starts here"
            },
            noDataFound: false,
            noDataFoundContributorsList: false,
            wallet: null,
          displayBalanceType: 'spendable',
          displayAmount: '0.00',
          insurance: null,
          latestCommitment: null,
          contributorsList:[],
          loading:false,
          isSender: true,
          transactionsList: [],
          walletHistory:null,
          latestBills:null,
          pendingBills:null,
          tableColumns: [

            { field: 'id', label: 'ID' },
            { field: 'type', label: 'Type' },
            { field: 'status', label: 'Status' },
            { field: 'currency', label: 'Currency' , filter: true },
            { field: 'amount', label: 'Amount' },

          ],
        }
    },
    methods: {
        hanldeFooterAction() {
            this.$inertia.visit('/transactions-history')
        },
      toggleBalanceDisplay() {
        this.displayBalanceType = this.displayBalanceType === 'spendable' ? 'available' : 'spendable';
        if (this.displayBalanceType === 'spendable') {
          this.displayAmount = this.wallet.spendable;
        }else {
          this.displayAmount = this.wallet.available_balance;
        }

      },
        handleViewAll() {
            this.$inertia.visit('/transactions-history')
        },
        handleAddInsurance() {
            this.$store.commit('SET_PAGE_COME_FROM', 'dashboard');
            this.$inertia.visit('/insurance')
        },
        handleAddContributors() {
            this.$inertia.visit('/contribution-invitation')
        },
      handleLastCommitment() {
            this.$inertia.visit('/contribution-tree')
        },
        handleDepositeFunds() {
          this.$store.commit('SET_PAGE_COME_FROM', 'dashboard');
          this.$store.commit('SET_FUND_TYPE', 'deposit');
            this.$inertia.visit('/deposit-funds')
        },
        handleInvite() {
            this.$inertia.visit('/contribution-invitation')
        },
      getWalletAmount() {
        this.loading = true;
        this.$inertia.get('/dashboard', {}, {
          preserveState: true,
          onSuccess: (page) => {
            this.loading = false
            this.wallet = page.props.wallet.data;
            this.insurance = page.props.insurance;
            this.latestCommitment = page.props.latestCommitment?.data;
            this.contributorsList = page.props.contributorsList?.data;
            this.transactionsList = page.props.transactionsList;
            this.walletHistory = page.props.walletHistory;
            this.latestBills = page.props.latestBills;
            this.pendingBills = page.props.pendingBills;
            if(this.contributorsList.length === 0){
              this.noDataFoundContributorsList = true
            }
            if (this.displayBalanceType === 'spendable') {
              this.displayAmount = this.wallet.spendable;
            }else {
              this.displayAmount = this.wallet.available_balance;
            }

          }
        });

        },

    },
  computed: {

  },
    mounted() {
        setTimeout(() => {
            this.noDataFound = false;
        }, 3000);

        this.getWalletAmount()
      this.$store.commit('SET_PAGE_COME_FROM', '');
      this.$store.commit('SET_PROFILE_IMG' ,this.profilePic )
      this.$store.commit('SET_TOKEN' ,this.token )

    },

}
</script>

<style scoped>
.add-btn {
    border: 1px dashed #3A84C6;
    background-color: transparent;
    border-radius: 10px;
    height: 40px;
    width: 200px;
}
.add-btn {
  border: 1px dashed #3A84C6;
  background-color: transparent;
  border-radius: 10px;
  height: 40px;
  width: 200px;
}

.balance {
  transition: opacity 0.3s ease-in-out;
}

.balance.fade-out {
  opacity: 0;
}

.balance.fade-in {
  opacity: 1;
}
.hover-effect:hover app-typography {
  color: white !important; /* Override the existing color on hover */
}
</style>