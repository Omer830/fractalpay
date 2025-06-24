<template>
    <div class="container-fluid mb-4">
        <div class="row gy-3">
            <div class="col-lg-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title"
                    :subTitle="header.subTitle" :isActionButtons="false" :isUserName="false" :isDivider="true" />
            </div>
            <div class="col-lg-12">
                <common-table :data="laserClinicDetails.laserClinicData"
                    :columns="laserClinicDetails.laserClinicColumns" :filterIcon="false" :isActionHeader="true"
                    :tableHeading="laserClinicDetails.heading" :headingColor="tableHeadDetails.textColor"
                    :headingVariant="tableHeadDetails.textVariant" :isTotalFooter="true"
                    :totalText='laserClinicDetails.totalText'>
                </common-table>
            </div>
            <div class="col-lg-12">
                <common-footer :isBackBtn="false" @continue="handleContinue" />
            </div>
        </div>
    </div>
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
                title: "Payment Bill Summary",
                subTitle: "Please verify following information is correct before proceeding"
            },
            tableHeadDetails: {
                textColor: '#3A84C6',
                textVariant: 'h2'
            },
            laserClinicDetails: {
                heading: "Royal",
                totalText: "Total for Layser Clinic",
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
            this.$router.push('/')
        },
    },
}
</script>

<style scoped></style>