import { createRouter, createWebHashHistory } from 'vue-router';
import Home from './components/Home.vue';
import About from './components/About.vue';
import Announcements from './components/Announcements.vue';
import Signup from './components/Signup.vue';

const routes = [
  { path: '/', redirect: '/home' }, 
  { path: '/home', name: 'Home', component: Home },
  { path: '/about', name: 'About', component: About },
  { path: '/announcements', name: 'Announcements', component: Announcements },
  { path: '/login', name: 'Login', component: () => import('./components/Login.vue')},
  { path: '/signup', name: 'Signup', component: Signup },
  {path: '/social', name: 'Social', component: () => import('./components/Social.vue') }
];


const router = createRouter({
  history: createWebHashHistory(), // ✅ this works on Mercury
  routes
});

router.beforeEach((to, from, next) => {
  const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';

  if (to.meta.requiresAuth && !isLoggedIn) {
    next({ path: '/login' });
  } else {
    next();
  }
});

export default router;