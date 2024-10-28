import { createRouter, createWebHistory } from 'vue-router';
import UserRegister from '../components/UserRegister.vue';
import UserLogin from '../components/UserLogin.vue';
import UserDashboard from '../components/UserDashboard.vue';
import AddExpense from '../pages/AddExpense.vue'; // Importimi i komponentit AddExpense
import AddIncome from '../pages/AddIncome.vue'; // Importimi i komponentit AddIncome

const routes = [
  {
    path: '/register',
    name: 'UserRegister',
    component: UserRegister,
  },
  
  {
    path: '/login',
    name: 'UserLogin',
    component: UserLogin,
  },
  {
    path: '/dashboard',
    name: 'UserDashboard',
    component: UserDashboard,
  },
  {
    path: '/add-expense',
    name: 'AddExpense',
    component: AddExpense, 
  },
  {
    path: '/add-income',
    name: 'AddIncome',
    component: AddIncome, 
  },
  {
    path: '/',
    redirect: '/login', 
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/login',
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router; 
