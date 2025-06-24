import { createApp, h } from 'vue'
import { createInertiaApp, usePage } from '@inertiajs/vue3'
import 'bootstrap/dist/css/bootstrap.min.css'
import './assets/css/global.css';
import 'element-plus/dist/index.css';
import store from './store';
import axios from 'axios'
import toastService from './services/toastService.js';
import { registerGlobalComponents } from './common-imports.js';
import './bootstrap';



const resolvePage = async (name) => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
    const modules = import.meta.glob('../../Modules/**/resources/js/Pages/**/*.vue', { eager: true });

    // Check in main Pages directory
    if (pages[`./Pages/${name}.vue`]) {
        return pages[`./Pages/${name}.vue`];
    }

    // Check in Modules directories
    for (const path in modules) {
        if (path.endsWith(`/${name}.vue`)) {
            return modules[path];
        }
    }

    throw new Error(`Page not found: ${name}`);
};



createInertiaApp({
    resolve: name => resolvePage(name),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin)
            .use(store)
            .use(toastService);

        registerGlobalComponents(app);

        app.mount(el);
    },
});

const page = usePage();
axios.interceptors.request.use(config => {
    let token = page.props.auth?.token;
    if (!token && localStorage.getItem('access_token')) {
        token = localStorage.getItem('access_token');
    }
    if(token) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
});


axios.interceptors.response.use(response => {
    const authToken = response.headers['authorization'];
    console.log(authToken);
    if (authToken) {
        localStorage.setItem('authToken', authToken);
    }
    console.log(authToken);
    return response;
}, error => {
    return Promise.reject(error);
});
