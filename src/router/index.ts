import { createRouter, createWebHistory, type RouteRecordRaw } from 'vue-router';

import Home from './views/Home.vue'
import Work from './views/Works.vue';
import Vita from './views/Vita.vue';
import Contact from './views/Contact.vue';
import Impress from '@/components/Impress.vue';
import Dsgvo from '@/components/Dsgvo.vue';

// Define the routes with types
const routes: Array<RouteRecordRaw> = [
  { path: '/', name: 'Home', component: Home }, // Default to "Work"
  { path: '/work', name: 'Work', component: Work },
  { path: '/vita', name: 'Vita', component: Vita },
  { path: '/contact', name: 'Contact', component: Contact },
  { path: "/impress", name: 'Impress', component: Impress },
  { path: "/privacy-policy", name: 'Dsgvo', component: Dsgvo }

];

// Create the router
const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
