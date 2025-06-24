
// Import libraries
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';
import * as ElementPlusIconsVue from '@element-plus/icons-vue';
import VueTheMask from 'vue-the-mask';
import { Link } from '@inertiajs/vue3';

// Import local components
import AuthLayout from "@/layout/authLayout.vue";
import AppTypography from "@/components/Typography/appTypography.vue";
import ImageSvg from "@/components/ImageSvg/imageSvg.vue";
import TableComponent from "@/components/TableComponent/TableComponent.vue";

// Export a function to register these in a Vue app
export function registerGlobalComponents(app) {
    app.use(ElementPlus)
        .component('Link', Link)
        .component('AuthLayout', AuthLayout)
        .component('app-typography', AppTypography)
        .component('image-svg', ImageSvg)
        .component('TableComponent', TableComponent)
        .use(VueTheMask);
    for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
        app.component(key, component);
    }
}
