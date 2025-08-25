<template>
    <div class="background">
        <div class="wrapper-page">
            <div class="card overflow-hidden account-card mx-3">
                <div class="p-4 text-white text-center position-relative">
                    <img :src="`${mainOrigin}assets/images/pulse_logo_1.png`" alt="logo">
                </div>
                <div class="account-card-content">
                    <ValidationObserver v-slot="{ handleSubmit }">
                        <form class="form-horizontal m-t-30" @submit.prevent="handleSubmit(onSubmit)">

                            <!-- User ID or Mobile Number -->
                            <ValidationProvider name="User ID" mode="eager" rules="required" v-slot="{ errors }">
                                <div class="form-group">
                                    <label for="usermailorphone">Mobile No / User Id</label>
                                    <input type="text" class="form-control" :class="{'error-border': errors[0]}" id="usermailorphone"
                                           v-model="usermailorphone" name="usermailorphone" placeholder="Mobile No / User Id" autocomplete="off">
                                    <span class="error-message"> {{ errors[0] }}</span>
                                </div>
                            </ValidationProvider>

                            <!-- OTP Field, initially hidden -->
                            <div v-if="otpSent">
                                <ValidationProvider name="OTP" mode="eager" rules="required|numeric" v-slot="{ errors }">
                                    <div class="form-group">
                                        <label for="otp">OTP</label>
                                        <input type="text" v-model="otp" class="form-control" :class="{'error-border': errors[0]}" id="otp" placeholder="Enter OTP" autocomplete="off">
                                        <span class="error-message">{{ errors[0] }}</span>
                                    </div>
                                </ValidationProvider>
                            </div>

                            <!-- Password Field, hidden initially -->
                            <div v-if="!otpSent && !isMobileNumber">
                                <ValidationProvider name="Password" mode="eager" rules="required|min:6" v-slot="{ errors }">
                                    <div class="form-group">
                                        <label for="user-password">Password</label>
                                        <input type="password" v-model="password" class="form-control" :class="{'error-border': errors[0]}"
                                               id="user-password" placeholder="Password" autocomplete>
                                        <span class="error-message">{{ errors[0] }}</span>
                                    </div>
                                </ValidationProvider>
                            </div>

                            <!-- Submit Button (Send OTP, Verify OTP or Login) -->
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary" :disabled="isSubmitting">
                                    <!-- Display text based on conditions -->
                                    <span v-if="isMobileNumber && !otpSent">Send OTP</span>
                                    <span v-if="isMobileNumber && otpSent">Verify OTP</span>
                                    <span v-if="!isMobileNumber">Login</span>
                                </button>
                            </div>

                            <!-- OTP Sent Confirmation Message -->
                            <div v-if="otpSent" class="text-center">
                                <p class="otp-sent-message">OTP has been sent to your mobile number: {{ usermailorphone }}</p>
                            </div>
                        </form>
                    </ValidationObserver>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Common } from '../../mixins/common'
import moment from "moment";

export default {
    mixins: [Common],
    data() {
        return {
            usermailorphone: '', // Mobile or user ID
            password: '',         // Password field
            otp: '',              // OTP field
            otpSent: false,       // Flag to check if OTP is sent
            isMobileNumber: false, // To check if input is a mobile number
            isSubmitting: false,   // Flag to disable submit button during submission
        }
    },
    computed: {
        now() {
            return moment()
        }
    },
    methods: {
        onSubmit() {
            this.isSubmitting = true; // Disable submit button

            if (this.isMobileNumber && !this.otpSent) {
                // If it's a mobile number and OTP is not sent, send OTP
                this.axiosPostWithoutToken('sendOtp', { mobileNumber: this.usermailorphone }, (response) => {
                    if (response.mobile) {
                        this.otpSent = true; // OTP sent successfully
                        this.successNoti('OTP sent to your mobile number.');
                    } else {
                        this.errorNoti('Mobile number not found or invalid.');
                        this.otpSent = false;
                    }
                    this.isSubmitting = false; // Re-enable submit button
                }, (error) => {
                    this.errorNoti('Error sending OTP.');
                    this.isSubmitting = false;
                });
            } else if (this.otpSent) {
                // If OTP is sent, verify OTP
                this.axiosPostWithoutToken('login', {
                    mobileNumber: this.usermailorphone,
                    otp: this.otp,
                    otpSent: this.otpSent
                }, (response) => {
                    localStorage.setItem("token", response.access_token);
                    this.successNoti('Successfully logged in.');
                    this.isSubmitting = false;
                    this.redirect(this.mainOrigin + 'dashboard');
                }, (error) => {
                    this.errorNoti(error);
                    this.isSubmitting = false;
                });
            } else {
                // If it's a staff ID, use password for login
                this.axiosPostWithoutToken('login', {
                    userID: this.usermailorphone,
                    password: this.password,
                    otpSent: this.otpSent
                }, (response) => {
                    localStorage.setItem("token", response.access_token);
                    this.successNoti('Successfully logged in.');
                    this.isSubmitting = false;
                    this.redirect(this.mainOrigin + 'dashboard');
                }, (error) => {
                    this.errorNoti(error);
                    this.isSubmitting = false;
                });
            }
        },

        // Detect whether the input is a mobile number (Bangladesh-specific regex)
        detectMobileNumber() {
            const mobileRegex = /^(01)?[0-9]{11}$/; // Bangladesh mobile number pattern
            const isMobile = mobileRegex.test(this.usermailorphone);
            this.isMobileNumber = isMobile; // Update the flag
        }
    },

    watch: {
        usermailorphone(newVal) {
            // Whenever the user starts typing, detect if it's a mobile number
            this.detectMobileNumber();
        }
    }
}
</script>

<style scoped>
.background {
    padding: 1px;
    background-size: cover !important;
    background: url("https://wa.acibd.com/e-verification/assets/images/loginBg.png");
    height: 100vh;
}
.account-card {
    background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent background */
    backdrop-filter: blur(10px); /* Blur effect */
    border-radius: 8px; /* Optional: rounded corners for the card */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional: shadow for better visibility */
}

.account-card-content {
    padding: 20px; /* Adjust padding as necessary */
}

</style>
