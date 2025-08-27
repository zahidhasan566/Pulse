import Vue from 'vue'
import VueRouter from 'vue-router';
import Main from '../components/layouts/Main';
import Dashboard from '../views/dashboard/Index.vue';
import Login from '../views/auth/Login.vue';
import {baseurl} from '../base_url'
import NotFound from '../views/404/Index';

import Users from '../views/users/Index';
import ReportAllSalesList from "../views/reports/reportAllSalesList.vue";
import ApprovalIndex from "../views/approval/ApprovalIndex.vue";
import RoleList from '../views/roles/Index.vue';
import RolePermission from '../views/roles/Permission.vue';
import Partners from "../views/Partner/Partners.vue";
import insurance from "../views/settings/Insurance/Insurance.vue";
import Service from "../views/Services/Index.vue";
import Package from "../views/package/Index.vue";
import Agents from "../views/settings/Agents/Index.vue";
import Sales from "../views/sales/Index.vue";
import SalesInvoice from '../components/sales/AddEdit.vue';
import Payments from "../views/payments/Index.vue";
import SubscriberList from "../views/members/Index.vue";
import Banks from "../views/settings/Banks/Index.vue";

Vue.use(VueRouter);

const config = () => {
    let token = localStorage.getItem('token');
    return {
        headers: {Authorization: `Bearer ${token}`}
    };
}
const checkToken = (to, from, next) => {
    let token = localStorage.getItem('token');
    if (token === undefined || token === null || token === '') {
        next(baseurl + 'login');
    } else {
        next();
    }
};

const activeToken = (to, from, next) => {
    let token = localStorage.getItem('token');
    if (token === 'undefined' || token === null || token === '') {
        next();
    } else {
        next(baseurl);
    }
};

const routes = [
    {
        path: baseurl,
        component: Main,
        redirect: {name: 'Dashboard'},
        children: [
            //COMMON ROUTE | SHOW DASHBOARD DATA
            {
                path: baseurl + 'dashboard',
                name: 'Dashboard',
                component: Dashboard
            },
            {
                path: baseurl + 'roles/list',
                name: 'RoleList',
                component: RoleList,
            },
            {
                path: baseurl + 'roles/permission',
                name: 'RolePermission',
                component: RolePermission,
            },
            //ADMIN ROUTE | SHOW USER LIST
            {
                path: baseurl + 'users',
                name: 'Users',
                component: Users
            },
            //partner
            {
                path: baseurl + 'partner_name',
                name: 'Partners',
                component: Partners
            },
            //service
            {
                path: baseurl + 'service_name',
                name: 'Service',
                component: Service
            },
            //package
            {
                path: baseurl + 'package_name',
                name: 'Package',
                component: Package
            },
            //sales
            {
                path: baseurl + 'sales',
                name: 'Sales',
                component: Sales
            },
            {
                path: baseurl + 'sales-add/:id?',
                name: 'SalesInvoice',
                component: SalesInvoice
            },

            //Payment Transactions
            {
                path: baseurl + 'payments/transactions',
                name: 'Payments',
                component: Payments
            },

            //Members
            {
                path: baseurl + 'member/subscriber_list',
                name: 'SubscriberList',
                component: SubscriberList
            },



            //Settings-Insurance
            {
                path: baseurl + 'settings/insurance',
                name: 'insurance',
                component: insurance
            },
            //Settings-Agent
            {
                path: baseurl + 'settings/agents',
                name: 'Agents',
                component: Agents
            },
            //Settings-banks
            {
                path: baseurl + 'settings/banks',
                name: 'Banks',
                component: Banks
            },
            //Report
            {
                path: baseurl + 'report/sales_list',
                name: 'ReportAllSalesList',
                component: ReportAllSalesList
            },




        ],
        beforeEnter(to, from, next) {
            checkToken(to, from, next);
        }
    },
    {
        path: baseurl + 'login',
        name: 'Login',
        component: Login,
        beforeEnter(to, from, next) {
            activeToken(to, from, next);
        }
    },
    {
        path: baseurl + '*',
        name: 'NotFound',
        component: NotFound,
    },
]
const router = new VueRouter({
    mode: 'history',
    base: process.env.baseurl,
    routes
});

router.afterEach(() => {
    $('#preloader').hide();
});

export default router
