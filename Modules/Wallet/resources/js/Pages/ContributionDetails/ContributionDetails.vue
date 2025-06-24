<template>
  <auth-layout>
    <div v-if="loading" class="loader-main">
      <div class="loader"></div>
    </div>
    <div class="container-fluid mb-4">
        <div class="row gy-4">
            <div class="col-lg-12">
                <title-action-bar
                    textWeight="600"
                    textVariant="text-38"
                    :title="header.title"
                    :isSubTitle="true"
                    :isActionButtons="true"
                    :isTertiaryBtn="true"
                    subTitle="(Ongoing Commitments)"
                    :isUserName="false"
                    :isDivider="true"
                    :isSecondaryBtn="true"
                    :primaryButtonText="header.primaryButtonText"
                    :secondaryButtonText="header.secondaryButtonText"
                    :isPrimaryBtn="true"
                    tertiaryButtonText="Friends & Family"
                    @secondary-click="handleViewTree"
                    @primary-click="handleInvite"
                    @tertiary-click="handleFriendsAndFamily"
                    btnRound="md" />
            </div>
            <contribution-details-card :commitments="commitments" />
            <div class="col-lg-12">
                <div class="card main-wrapper">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card border-0">
                                <div class="header">
                                    <app-typography variant="text-26" color="#14AE5C">
                                        Contribution In
                                    </app-typography>
                                </div>
                                <div class="card-body px-0">
                                    <common-table :data="inTableData" :header="inTableColumns" :isActionHeader="true"
                                        :filterIcon="true" headerBgColor="#ffff"
                                                  tableHeight="55vh"
                                                  :paginationStatus="false"
                                        headerTextColor="#36454F">
                                      <template v-slot:action="slotProps">
                                        <button class="detail-btn" @click="contributorInDetail(slotProps.row.id)">Detail</button>
                                      </template>
                                    </common-table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card border-0">
                                <div class="header">
                                    <app-typography variant="text-26" color="#F24822">
                                        Contribution Out
                                    </app-typography>
                                </div>
                                <div class="card-body px-0">
                                    <common-table :data="outTableData" :header="outTableColumns"
                                        :isActionHeader="false" tableHeight="55vh" :paginationStatus="false" :filterIcon="false" headerBgColor="#ffff"
                                        headerTextColor="#36454F">
                                      <template v-slot:action="slotProps">
                                        <button class="detail-btn" @click="contributorOutDetail(slotProps.row.id)">Detail</button>
                                      </template>

                                    </common-table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </auth-layout>

</template>
<script>
import { TitleActionBar, ContributionDetailsCard, AppTypography, CommonTable } from "@/components/index";
export default {
    components: {
        TitleActionBar, AppTypography,
        ContributionDetailsCard, CommonTable
    },
    data() {
        return {
          commitments:null,
          loading: false,
            header: {
                title: "Contribution Details",
                primaryButtonText: "Invite",
                secondaryButtonText: "View Tree",
                startIcon: 'plus-icon'
            },
            inTableData: [
            ],
            inTableColumns: [
                { label: 'Name', field: 'name' },
                { label: 'Frequency', field: 'frequency' },
                { label: 'Amount', field: 'amount' },
                { label: 'Action', field: 'action' },
            ],
            outTableData: [

            ],
            outTableColumns: [
                { label: 'Name', field: 'name' },
                { label: 'Frequency', field: 'frequency', filter: true },
                { label: 'Amount', field: 'amount' },
                { label: 'Action', field: 'action' },
            ],
        }
    },
  mounted() {
  this.getExpenseTracker()
      },
  methods: {
        handleInvite() {
          console.log('Zain')
          this.$inertia.visit('/contribution-invitation')
        },
        handleViewTree() {
          this.$inertia.visit('/contribution-tree')
        },
      handleFriendsAndFamily() {
          this.$inertia.visit('/friend-and-family')
        },
      getExpenseTracker() {
        this.loading = true;
        this.$inertia.get('/contribution-detail', {}, {
          preserveState: true,
          onSuccess: (page) => {
            this.loading = false
            this.commitments = page.props.commitments
            this.inTableData = page.props.commitments.incoming.commitments;
            this.outTableData = page.props.commitments.outgoing.commitments;

          }
        });

      },
    contributorInDetail(id) {
      const contributorType = 'incoming';
      const contributionDetails = {
        id: id,
        type : contributorType,

      }
      this.$store.commit('SET_CONTRIBUTOR_DETAIL', contributionDetails);
      this.$inertia.visit(`/contribution-summary`);
    },
    contributorOutDetail(id) {
      const contributorType = 'outgoing';
      const contributionDetails = {
        id: id,
        type : contributorType,

      }
      this.$store.commit('SET_CONTRIBUTOR_DETAIL', contributionDetails);
      this.$inertia.visit(`/contribution-summary`);

      },
    }
}
</script>
<style scoped>
.main-wrapper {
    padding: 20px 25px;
}
.detail-btn{
  border: 1px solid #B4B4B4;
  color: #76848D;
  border-radius: 8px;
  font-size:12px;
  padding: 5px 15px;
}
</style>