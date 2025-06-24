import { createRouter, createWebHashHistory } from 'vue-router'; // Import createRouter and createWebHashHistory from vue-router
import routes from './routes';

// Create the router instance
const router = createRouter({
  history: createWebHashHistory(), // Use createWebHashHistory for hash mode
  routes,
  linkActiveClass: 'active', // Set the active class for router links
});

export default router;
