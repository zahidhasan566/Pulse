<template>
  <div>
    <div class="wrapper-page">
      <div class="card overflow-hidden account-card mx-3">
        <div class=" p-4 text-white text-center position-relative">
            <img  :src="`${mainOrigin}assets/images/pulse_logo_1.png`"   alt="logo">
        </div>
        <div class="account-card-content">
          <ValidationObserver v-slot="{ handleSubmit }">
            <form class="form-horizontal m-t-30" @submit.prevent="handleSubmit(onSubmit)">
              <ValidationProvider name="User ID" mode="eager" rules="required" v-slot="{ errors }">
                <div class="form-group">
                  <label for="username">User Id</label>
                  <input type="text" class="form-control" :class="{'error-border': errors[0]}" id="usermailorphone"
                         v-model="usermailorphone" name="usermailorphone" placeholder="User Id" autocomplete="off">
                  <span class="error-message"> {{ errors[0] }}</span>
                </div>
              </ValidationProvider>
              <ValidationProvider name="Password" mode="eager" rules="required|min:6" v-slot="{ errors }">
                <div class="form-group">
                  <label for="user-password">Password</label>
                  <input type="password" v-model="password" class="form-control" :class="{'error-border': errors[0]}"
                         id="user-password" placeholder="Password" autocomplete>
                  <span class="error-message">{{ errors[0] }}</span>
                </div>
              </ValidationProvider>
              <submit-form name="Log In"/>
            </form>
          </ValidationObserver>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import {Common} from '../../mixins/common'
import moment from "moment";

export default {
  mixins: [Common],
  data() {
    return {
      usermailorphone: '',
      userID: '',
      password: '',
    }
  },
  computed: {
    now() {
      return moment()
    }
  },
  mounted() {
    // this.getData();
  },
  methods: {
    onSubmit() {
      this.$store.commit('submitButtonLoadingStatus', true);
      this.axiosPostWithoutToken('login', {
        userID: this.usermailorphone,
        password: this.password
      }, (response) => {
          console.log(response)
        localStorage.setItem("token", response.access_token);
        this.successNoti('Successfully logged in.');
        this.$store.commit('submitButtonLoadingStatus', false);
        this.redirect(this.mainOrigin + 'dashboard')
      }, (error) => {
        this.errorNoti(error);
        this.$store.commit('submitButtonLoadingStatus', false);
      })
    }
  }
}
</script>
