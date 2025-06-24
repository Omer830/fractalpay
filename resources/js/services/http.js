import axios from 'axios';
import store from '@/store'; // Import your Vuex store

const http = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
    },
});

// Add a request interceptor
http.interceptors.request.use(function (config) {
    const token = store.getters.getToken;
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, function (error) {
    return Promise.reject(error);
});

// Response interceptor (optional improvements possible here)
http.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    return Promise.reject(error);
});

export default http;
