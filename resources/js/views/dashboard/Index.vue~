<template>
    <div>
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">Dashboard</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Welcome to E Verification Admin Dashboard</li>
                </ol>
            </div>
            <div class="col-sm-6">
                <div class="float-right d-none d-md-block">
                    <div class="dropdown">
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Action</a> <a class="dropdown-item" href="#">Another action</a> <a class="dropdown-item" href="#">Something else here</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Separated link</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>
<script>
import {Common} from "../../mixins/common";

export default {
  mixins: [Common],
  data() {
    return {
      isLoading: true
    }
  },
  computed: {
    me() {
      return this.$store.state.me
    }
  },
  created() {
    this.getData();
  },
  methods: {
    getData() {
      // this.axiosGet('dashboard-data', (response) => {
      //    this.total_pending = response.total_pending;
      //    this.todays_order = response.todays_order;
      //    this.todays_amount = response.todays_amount;
      //    this.total_order = response.total_order;
      this.isLoading = false;
      // }, (error) => {
      //   this.errorNoti(error);
      // });
    }
  }
}
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
