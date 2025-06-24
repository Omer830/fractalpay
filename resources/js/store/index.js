

import { createStore } from 'vuex';

export default createStore({
    state: {
        email: localStorage.getItem('email'),

        phoneNumber: '',

        otp:localStorage.getItem('otp'),

        documentName: localStorage.getItem('documentName') || '',

        fundDetail: localStorage.getItem('fundDetail') ? JSON.parse(localStorage.getItem('fundDetail')) : '',

        pageComeName: localStorage.getItem('pageComeName') || '',

        fundType: localStorage.getItem('fundType') || '',

        selectedUserId: localStorage.getItem('selectedUserId') || '',

        paymentMethodDetail: localStorage.getItem('paymentMethodDetail') ? JSON.parse(localStorage.getItem('paymentMethodDetail')) : '',

        billDoc: localStorage.getItem('billDoc') || '',

        visit_ids: localStorage.getItem('visit_ids') || [],

        bill_ids: JSON.parse(localStorage.getItem('bill_ids')) || [],


        bill_amount: localStorage.getItem('bill_amount') || null,

        contributor_detail: JSON.parse(localStorage.getItem('contributor_detail') )|| null,

        set_doc_type: JSON.parse(localStorage.getItem('set_doc_type') )|| null,

        set_expense_type: JSON.parse(localStorage.getItem('set_expense_type') )|| null,

        profile_img: JSON.parse(localStorage.getItem('profile_img') )|| null,

        token: JSON.parse(localStorage.getItem('token') )|| null,
    },
    getters: {
        getEmail: state => state.email,

        getPhoneNumber: state => state.phoneNumber,

        getOtp: state => state.otp,

        getDocumentName: state => state.documentName,

        getFundDetail: state => state.fundDetail,

        getPageComeFromName: state => state.pageComeName,

        getBillDoc: state => state.billDoc,

        getPaymentMethodDetail: state => state.paymentMethodDetail,

        getFundType: state => state.fundType,

        getSelectedUserId: state => state.selectedUserId,

        getVisitIds: state => state.visit_ids,

        getBillsIds: state => state.bill_ids,

        getBillPayableAmount: state => state.bill_amount,

        getContributorDetail: state => state.contributor_detail,

        getDocType: state => state.set_doc_type,

        getExpenseType: state => state.set_expense_type,

        getProfileIMG: state => state.profile_img,

        getToken: state => state.token,


    },
    mutations: {
        SET_EMAIL(state, email) {
            state.email = email;
            localStorage.setItem('email', email);
        },
        SET_OTP(state, otp) {
            state.otp = otp;
            localStorage.setItem('otp', otp);
        },
        SET_PHONE_NUMBER(state, phoneNumber) {
            state.phoneNumber = phoneNumber;
        },
        SET_DOCUMENT_NAME(state, documentName) {
            state.documentName = documentName;
            localStorage.setItem('documentName', JSON.stringify(documentName));
        },
        SET_ADD_FUND_DETAIL(state, fundDetail) {
            state.fundDetail = fundDetail;
            localStorage.setItem('fundDetail', JSON.stringify(fundDetail));
        },
        SET_PAYMENT_METHOD_DETAIL(state, fundDetail) {
            state.paymentMethodDetail = fundDetail;
            localStorage.setItem('paymentMethodDetail', JSON.stringify(fundDetail));
        },
        SET_PAGE_COME_FROM(state, pageComeName) {
            state.pageComeName = pageComeName;
            localStorage.setItem('pageComeName', pageComeName);
        },
        SET_FUND_TYPE(state, fundType) {
            state.fundType = fundType;
            localStorage.setItem('fundType', fundType);
        },
        SET_SELECTED_USER_ID(state, selectedUserId) {
            state.selectedUserId = selectedUserId;
            localStorage.setItem('selectedUserId', selectedUserId);
        },
        SET_BILL_DOC(state, value) {
            state.billDoc = value;
            localStorage.setItem('billDoc', JSON.stringify(value));
        },
        SET_VISIT_IDS(state, value) {
            state.visit_ids = value;
            localStorage.setItem('visit_ids', value);
        },
        SET_Bill_IDS(state, value) {
            state.bill_ids = value;
            localStorage.setItem('bill_ids', JSON.stringify(value));
        },
        SET_BILL_PAYABLE_AMOUNT(state, value) {
            state.bill_amount = value;
            localStorage.setItem('bill_amount', value);
        },
        SET_CONTRIBUTOR_DETAIL(state, value) {
            state.contributor_detail = value;
            localStorage.setItem('contributor_detail', JSON.stringify(value));
        },
        SET_DOC_TYPE(state, value) {
            state.set_doc_type = value;
            localStorage.setItem('set_doc_type', JSON.stringify(value));
        },
        SET_EXPENSE_TAB(state, value) {
            state.set_expense_type = value;
            localStorage.setItem('set_expense_type', JSON.stringify(value));
        },
        SET_PROFILE_IMG(state, value) {
            state.prfile_img= value;
            localStorage.setItem('profile_img', JSON.stringify(value));
        },
        SET_TOKEN(state, value) {
            state.token= value;
            localStorage.setItem('token', JSON.stringify(value));
        },
    },

    actions: {
        changeRoute(context, payload) {
            if ('scrollRestoration' in history) {
                history.scrollRestoration = 'manual';
            }
            // logic to change route
        }
    },

    modules: {

    }
});
