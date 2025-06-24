<template>
    <div class="container-fluid mb-4">
        <div class="row gy-3">
            <div class="col-lg-12">
                <title-action-bar textWeight="600" textVariant="text-38" :title="header.title" :isSubTitle="false"
                    :isActionButtons="false" :isUserName="false" :isDivider="true" />
            </div>
            <div class="col-lg-12">
                <common-table :data="laserClinicDetails.laserClinicData"
                    :columns="laserClinicDetails.laserClinicColumns" :filterIcon="false" :isActionHeader="true"
                    :tableHeading="laserClinicDetails.heading" :headingVariant="tableHeadDetails.textVariant">
                </common-table>
            </div>
            <div class="col-lg-12">
                <common-footer @back="handleBack" @continue="handleContinue" />
            </div>
        </div>
    </div>
</template>

<script>
import { TitleActionBar, CommonTable, CommonFooter } from "@/components/index.js";
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
                textVariant: 'h4'
            },
            laserClinicDetails: {
                heading: "Visit Name > Paid Bills",
                laserClinicData: [
                    { hospital: 'Royal ', visit: { text: 'Chest Infect..', color: '#4682BE', type: "coloredText" }, billCategory: 'Checkup', invoiceNumber: '736-333', dueDate: '01/01/2020', totalAmount: "$1000", status: { type: 'button', label: 'Pay Now', onClick: this.handlePayBtn } },
                    { hospital: 'Royal ', visit: { text: 'Gallbladder P..', color: '#4682BE', type: "coloredText" }, billCategory: 'Checkup', invoiceNumber: '1234-322', dueDate: '01/01/2020', totalAmount: "$189.99", status: { type: 'button', label: 'Paid', onClick: this.handlePayBtn } },
                    { hospital: 'Royal ', visit: { text: 'Urine Infecti..', color: '#4682BE', type: "coloredText" }, billCategory: 'Checkup', invoiceNumber: '1234-322', dueDate: '01/01/2020', totalAmount: "$4999", status: { type: 'button', label: 'Pay Now', onClick: this.handlePayBtn } },
                    { hospital: 'Royal ', visit: { text: 'Bloodpressu..', color: '#4682BE', type: "coloredText" }, billCategory: 'Checkup', invoiceNumber: '1234-322', dueDate: '01/01/2020', totalAmount: "$340", status: { type: 'button', label: 'Pay Now', onClick: this.handlePayBtn } },
                    { hospital: 'Royal ', visit: { text: 'Sports Injury', color: '#4682BE', type: "coloredText" }, billCategory: 'Checkup', invoiceNumber: '0987-322', dueDate: '01/01/2020', totalAmount: "$1890", status: { type: 'button', label: 'Paid', onClick: this.handlePayBtn } },
                    { hospital: 'Royal ', visit: { text: 'Colonoscopy', color: '#4682BE', type: "coloredText" }, billCategory: 'Checkup', invoiceNumber: '736-333', dueDate: '01/01/2020', totalAmount: "$122", status: { type: 'button', label: 'Paid', onClick: this.handlePayBtn } },
                    { hospital: 'Royal ', visit: { text: 'Fracture', color: '#4682BE', type: "coloredText" }, billCategory: 'Checkup', invoiceNumber: '736-333', dueDate: '01/01/2020', totalAmount: "$350", status: { type: 'button', label: 'Pay Now', onClick: this.handlePayBtn } },
                ],
                laserClinicColumns: [
                    { label: 'Hospital', field: 'hospital' },
                    { label: 'Visit', field: 'visit' },
                    { label: 'Bill Category', field: 'billCategory' },
                    { label: 'Invoice Number', field: 'invoiceNumber' },
                    { label: 'Bill Due Date', field: 'dueDate' },
                    { label: 'Totla Amount', field: 'totalAmount' },
                    { label: 'Statue', field: 'status' },
                ],
            },
        }
    },
    methods: {
        handlePayBtn() {
            alert('pay now clicked')
        },
        handleContinue() {
            this.$router.push('/')
        },
        handleBack() {
            this.$router.push('/')
        },

    },
}
</script>

<style scoped></style>