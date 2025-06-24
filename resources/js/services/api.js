// services/apis.js

import http from './http'; // Using Axios configuration
import { showToast } from './toastService';
import store from "@/store/index.js"; // Use custom toast service

const apis = {
    async getData(url, showError = true) {
        try {
            const response = await http.get(url);
            showToast('Data retrieved successfully', 'success');
            return response.data.data;
        } catch (error) {
            if (showError) showToast(error.response?.data.message ?? 'Failed to retrieve data', 'error');
            return null;
        }
    },

    async postData(url, createData, toastMessage = true) {
        try {
            const response = await http.post(url, createData);
            if (toastMessage) showToast('Data posted successfully', 'success');
            return response.data.data ?? true;
        } catch (error) {
            showToast(error.response.data.message ?? 'An error occurred while posting data', 'error');
            return false;
        }
    },

    async postDataCustom(url, createData) {
        try {
            const response = await http.post(url, createData);
            showToast('Custom data posted successfully', 'success');
            return response.data ?? true;
        } catch (error) {
            showToast(error.response.data.message ?? 'Failed in custom post operation', 'error');
            return null;
        }
    },

    async patchData(url, patchData) {
        try {
            const response = await http.patch(url, patchData);
            showToast('Data patched successfully', 'success');
            return response.data.data ?? true;
        } catch (error) {
            showToast(error.response.data.message ?? 'Failed to patch data', 'error');
            return null;
        }
    },

    async putData(url, updateData) {
        try {
            const response = await http.put(url, updateData);
            showToast('Data updated successfully', 'success');
            return response.data.data;
        } catch (error) {
            showToast(error.response.data.message ?? 'Failed to update data', 'error');
            return null;
        }
    },

    async deleteData(url, deleteFormData = null) {
        try {
            const response = await http.delete(url, { data: deleteFormData });
            showToast('Data deleted successfully', 'success');
            return response.data.data;
        } catch (error) {
            showToast(error.response.data.message ?? 'Failed to delete data', 'error');
            return null;
        }
    },

    async login(formData) {
        return await this.postData('/auth/login', formData, false) ?? false;
    },

    async register(formData) {
        return await this.postData('/auth/register', formData, true) ?? false;
    },

    async forgotPassword(formData) {
        return await this.postData('/auth/forgot-password', formData, true) ?? false;
    },
    async resetPassword(formData) {
        return await this.postData('/auth/reset-password', formData, true) ?? false;
    },
    async addProfile(formData) {
        return await this.postData('/userProfile/addProfile', formData, true) ?? false;
    },
    async addUserDocuments(formData) {
        return await this.postData('/userDocuments/storeDocuments', formData, true) ?? false;
    },
    async updateEmailAttribute(formData) {
        return await this.postData('/updateEmailAttribute', formData, true) ?? false;
    },
    async changePassword(formData) {
        return await this.postData('/changePassword', formData, true) ?? false;
    },
    async deletePaymentMethod(formData) {
        return await this.postData('/v1/paymentMethod/delete', formData, true) ?? false;
    },
    async sendOtp(formData) {
        return await this.postData('/send_otp', formData, true) ?? false;
    },

};

export default apis;
