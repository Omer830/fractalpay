<template>
  <auth-layout>
  <div class="container-fluid mb-4">
        <div class="row gy-3">
            <div class="col-lg-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title" :isSubTitle="false"
                    :isActionButtons="false" :isUserName="false" :isDivider="true" />
            </div>
            <div class="col-lg-12">
                <common-table :data="laserClinicDetails.laserClinicData"
                    :header="laserClinicDetails.laserClinicColumns" :filterIcon="false" :isActionHeader="true"
                    :tableHeading="laserClinicDetails.heading" :headingColor="tableHeadDetails.textColor"
                    :headingVariant="tableHeadDetails.textVariant" :isTotalFooter="true"
                    :totalText='laserClinicDetails.totalText' :paginationStatus="false">
                </common-table>
            </div>
            <div class="col-lg-12">
                <common-footer @back="handleBack" @continue="handleContinue" />
            </div>
        </div>
    </div>
  </auth-layout>
</template>

<script>
import { TitleActionBar, CommonTable, CommonFooter } from "@/components/index";
export default {
    components: {
        TitleActionBar,
        CommonTable,
        CommonFooter
    },
    data() {
        return {
            header: {
                title: "Expense Tracker",
            },
            tableHeadDetails: {
                textColor: '#3A84C6',
                textVariant: 'h2'
            },
            laserClinicDetails: {
                heading: "Total Payable",
                totalText: "Total Payable",
                laserClinicData: [
                    { billCategory: 'Pain ', billDescription: "Check Up", paymentMethod: { text: 'Bpay', color: '#4682BE', type: "coloredText" }, invoiceNumber: '736-333', dueDate: 'Dec 21, 2024', billAmount: "$250.0" },
                    { billCategory: 'Physio', billDescription: "Check Up", paymentMethod: { text: 'Bpay', color: '#4682BE', type: "coloredText" }, invoiceNumber: '736-333', dueDate: 'Dec 21, 2024', billAmount: "$250.0" },
                ],
                laserClinicColumns: [
                    { label: 'Bill Category', field: 'billCategory' },
                    { label: 'Bill Description', field: 'billDescription' },
                    { label: 'Payment Method', field: 'paymentMethod' },
                    { label: 'Invoice Number', field: 'invoiceNumber' },
                    { label: 'Due Date', field: 'dueDate' },
                    { label: 'Bill Amount', field: 'billAmount' },
                ],
            },
        }
    },
    methods: {
        handleContinue() {
            this.$inertia.visit('/add-bill')
        },
        handleBack() {
            window.history.back()
        }
    },
}
</script>

<style scoped></style>