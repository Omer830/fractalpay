import { createApp } from 'vue'; // Import createApp function from Vue 3
import App from './App.vue'; // Assuming your root component file is named App.vue
import router from './router/index';
import './assets/css/global.css'; // Import the global CSS file
const app = createApp(App); // Create the Vue app instance
import VueSidebarMenu from 'vue-sidebar-menu'
import 'vue-sidebar-menu/dist/vue-sidebar-menu.css'

app.use(router); // Use Vue Router
app.use(VueSidebarMenu)
app.mount('#app'); // Mount the app to the root element with id="app"
