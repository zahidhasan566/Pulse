<template>
    <div>
        <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4 class="page-title">Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">Welcome to Pulse Dashboard</li>
                    </ol>
                </div>
                <div class="col-sm-6">
                    <div class="float-right d-none d-md-block">
                        <div class="dropdown">
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Separated link</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-md-4">
                <div class="card mini-stat bg-primary text-white">
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="float-left mini-stat-img mr-4"><img src="assets/images/services-icon/01.png" alt="" /></div>
                            <h5 class="font-16 text-uppercase mt-0 text-white-50">Sales </h5>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Total Customer</th>
                                    <th>Total Sales (Amount)</th>
                                    <th>Agent Commission (Amount)</th>
                                </tr>
                                </thead>
                                <tbody id="tagModal-body">
                                <tr>
                                    <td>{{ total_sales }}</td>
                                    <td>{{ total_amount_paid }}</td>
                                    <td>{{total_agent_commission }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Graph -->


                        <div class="pt-2">
                            <div class="float-right">
                                <router-link :to="{ path: 'purchase/purchaseList' }">
                                    <i style="color: white" class="mdi mdi-arrow-right h5"></i>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div v-if="!isLoading">
                    <canvas id="salesChart"></canvas>
                </div>

                <!-- Loading Spinner -->
                <div v-else>
                    <p>Loading...</p> <!-- Display loading text while fetching data -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Common } from "../../mixins/common";
import { Chart } from 'chart.js';


export default {
    mixins: [Common],
    data() {
        return {
            isLoading: true,
            sales: [],
            total_agent_commission: 0,
            total_amount_paid: 0,
            total_sales: 0,
        };
    },
    computed: {
        me() {
            return this.$store.state.me;
        },
    },
    created() {
        this.getData();
    },
    methods: {
        getData() {
            this.axiosGet("dashboard-data", (response) => {
                console.log(response)
                this.total_agent_commission = response.total_agent_commission;
                this.total_amount_paid = response.total_amount_paid;
                this.total_sales = response.total_sales;
                this.isLoading = false;
                this.renderChart();
            }, (error) => {
                this.isLoading = false;
                this.errorNoti(error);
            });
        },

        renderChart() {
            const ctx = document.getElementById("salesChart").getContext("2d");

            // These are the three values you want to chart
            const totalSales = parseFloat(this.total_sales).toFixed(4);
            const totalAmountPaid = parseFloat(this.total_amount_paid).toFixed(4);
            const totalAgentCommission = parseFloat(this.total_agent_commission).toFixed(4);

            console.log(totalSales)

            // Create the chart
            new Chart(ctx, {
                type: "bar", // Bar chart type
                data: {
                    labels: ['Sales Overview'],  // Label for the chart (can be any label you want)
                    datasets: [
                        {
                            label: "Total Sales",
                            data: [totalSales],
                            backgroundColor: "rgba(54, 162, 235, 0.5)",
                            borderColor: "rgba(54, 162, 235, 1)",
                            borderWidth: 1,
                        },
                        {
                            label: "Total Amount Paid",
                            data: [totalAmountPaid],
                            backgroundColor: "rgba(75, 192, 192, 0.5)",
                            borderColor: "rgba(75, 192, 192, 1)",
                            borderWidth: 1,
                        },
                        {
                            label: "Total Agent Commission",
                            data: [totalAgentCommission],
                            backgroundColor: "rgba(255, 99, 132, 0.5)",
                            borderColor: "rgba(255, 99, 132, 1)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 10000, // Adjust this step size as per your data
                            }
                        },
                    },
                },
            });
        }
    },
};
</script>

<style scoped>
.bg-blue {
    background: #626ed4!important;
    text-align: center;
    text-transform: uppercase;
}
.card-body {
    padding: 0.60rem !important;
}
.bg-blue span {
    font-size: 18px;
}
.task {
    background: #00a55d2b;
    padding: 5px 8px;
    font-size: 14px;
    margin-bottom: 10px;
    border-radius: 5px;
    font-weight: bold;
}
.links {
    /*height: 148px;*/
}
.helpline {
    height: 120px;
    text-align: center;
    position: relative;
    background-image: linear-gradient(146deg,#626ed4,#626ed4);
}
.helpline span {
    position: absolute;
    top: 40%;
    left: 0;
    right: 0;
    font-weight: bold;
    font-size: 20px;
    text-transform: uppercase;
    color: #FFFFFF;
}
.contact {
    height: 120px;
    text-align: center;
    padding-top: 20px;
    background-image: linear-gradient(146deg,#626ed4,#626ed4);
}
.contact p {
    margin: 0;
    padding: 2px;
    font-weight: bold;
    color: #ffffff;
}
.header-bg {
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    text-transform: uppercase;
}
</style>
