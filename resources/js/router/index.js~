import Vue from 'vue'
import VueRouter from 'vue-router';
import Main from '../components/layouts/Main';
import Dashboard from '../views/dashboard/Index.vue';
import Login from '../views/auth/Login.vue';
import {baseurl} from '../base_url'
import NotFound from '../views/404/Index';

import Users from '../views/users/Index';
import ReportAllShopInformation from "../views/reports/reportAllShopInformation.vue";
import AssignVroIndex from "../views/vro/AssignVroIndex.vue";
import ApprovalIndex from "../views/approval/ApprovalIndex.vue";
import shopInformationPrint from "../views/reports/shopInformationPrint.vue";


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
            //VRO
            {
                path: baseurl + 'vro/assign-vro',
                name: 'AssignVroIndex',
                component: AssignVroIndex
            },
            //ADMIN ROUTE | SHOW USER LIST
            {
                path: baseurl + 'users',
                name: 'Users',
                component: Users
            },
            //Approval
            {
                path: baseurl + 'approval/approve-shop-requisition',
                name: 'ApprovalIndex',
                component: ApprovalIndex
            },
            //Report
            {
                path: baseurl + 'report/shop-information-report',
                name: 'ReportAllShopInformation',
                component: ReportAllShopInformation
            },
            {
                path: baseurl + 'report/shop-information-report/print/:shopId',
                name: 'shopInformationPrint',
                component: shopInformationPrint
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
