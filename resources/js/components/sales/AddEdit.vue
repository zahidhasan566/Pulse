<template>
    <div class="container-fluid">
        <breadcrumb :options="['Sales', 'Add/Edit Sale']">
            <router-link :to="{name: 'Sales'}" class="btn btn-primary">Back to Sales List</router-link>
        </breadcrumb>

        <div class="row">
            <div class="col-12 col-md-12">
                <ValidationObserver v-slot="{ handleSubmit }" ref="observer">
                    <form @submit.prevent="handleSubmit(submitForm)">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4 shadow-lg custom-card">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">Customer Information</h5>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Select Partner -->
                                            <div class="col-12 col-md-4">
                                                <ValidationProvider name="Partner" mode="eager" rules="required" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="partnerID">Select Partner <span class="error">*</span></label>
                                                        <multiselect
                                                            v-model="selectedPartner"
                                                            :options="partners"
                                                            :multiple="false"
                                                            :close-on-select="true"
                                                            :clear-on-select="false"
                                                            :preserve-search="true"
                                                            placeholder="Select Partner"
                                                            label="PartnerName"
                                                            track-by="PartnerID"
                                                            :class="{'error-border': errors[0]}"
                                                        >
                                                        </multiselect>
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <!-- Select PackageMaster -->
                                            <div class="col-12 col-md-4">
                                                <ValidationProvider name="Package" mode="eager" rules="required" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="packageID">Select Package <span class="error">*</span></label>
                                                        <multiselect
                                                            v-model="selectedPackage"
                                                            :options="packages"
                                                            :multiple="false"
                                                            :close-on-select="true"
                                                            :clear-on-select="false"
                                                            :preserve-search="true"
                                                            placeholder="Select Package"
                                                            label="PackageName"
                                                            track-by="PackageID"
                                                            :class="{'error-border': errors[0]}"
                                                        >
                                                        </multiselect>
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <!-- Select Agent -->
                                            <div class="col-12 col-md-4">
                                                <ValidationProvider name="Agent" mode="eager" rules="required" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="agentID">Select Agent <span class="error">*</span></label>
                                                        <multiselect
                                                            v-model="selectedAgent"
                                                            :options="agents"
                                                            :multiple="false"
                                                            :close-on-select="true"
                                                            :clear-on-select="false"
                                                            :preserve-search="true"
                                                            placeholder="Select Agent"
                                                            label="AgentName"
                                                            track-by="AgentID"
                                                            :class="{'error-border': errors[0]}"
                                                        >
                                                        </multiselect>
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <!-- Full Name -->
                                            <div class="col-12 col-md-4">
                                                <ValidationProvider name="Full Name" mode="eager" rules="required" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="fullName">Full Name <span class="error">*</span></label>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="FullName"
                                                            name="fullName"
                                                            placeholder="Full Name"
                                                        />

                                                    </div>
                                                    <span class="error-message">{{ errors[0] }}</span>
                                                </ValidationProvider>
                                            </div>

                                            <!-- Email -->
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Email" mode="eager" rules="email" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="email">Email</label>
                                                        <input
                                                            type="email"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="Email"
                                                            name="email"
                                                            placeholder="Email Address"
                                                        />
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <!-- Password -->
<!--                                            <div class="col-12 col-md-4">-->
<!--                                                <ValidationProvider name="Password" mode="eager" rules="min:6" v-slot="{ errors }">-->
<!--                                                    <div class="form-group">-->
<!--                                                        <label for="password">Password</label>-->
<!--                                                        <input-->
<!--                                                            type="password"-->
<!--                                                            class="form-control"-->
<!--                                                            :class="{'error-border': errors[0]}"-->
<!--                                                            v-model="Password"-->
<!--                                                            name="password"-->
<!--                                                            placeholder="Password (min 6 characters)"-->
<!--                                                        />-->
<!--                                                    </div>-->
<!--                                                    <span class="error-message">{{ errors[0] }}</span>-->
<!--                                                </ValidationProvider>-->
<!--                                            </div>-->



                                            <!-- Mobile Number -->
                                            <div class="col-12 col-md-2">
                                                <ValidationProvider name="Mobile Number" mode="eager" rules="required" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="mobileNumber">Mobile Number <span class="error">*</span></label>
                                                        <input type="tel" pattern='(01)?[0-9]{11}'
                                                               class="form-control"
                                                               :class="{'error-border': errors[0]}"
                                                               data-index="2"
                                                               id="mobileNo"
                                                               data-required="true"
                                                               v-model="MobileNumber" name="mobileNo"
                                                               placeholder="Mobile no">
                                                    </div>
                                                    <span class="error-message">{{ errors[0] }}</span>
                                                </ValidationProvider>
                                            </div>
                                            <!-- Profile Pic -->
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Profile Picture" mode="eager" rules="" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="profilePicture">Profile Picture</label>
                                                        <input
                                                            type="file"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            @change="handlePhotoUpload"
                                                            accept="image/*"
                                                            ref="photoInput"
                                                        />
                                                    </div>
                                                    <span class="error-message">{{ errors[0] }}</span>
                                                </ValidationProvider>
                                            </div>

                                            <!-- Visit Type -->
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Visit Type" mode="eager" rules="required"
                                                                    v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="Visit Type">Visit Type <span class="error">*</span></label>
                                                        <select name="gender" class="form-control"   v-model="visitType">
                                                            <option value="">Select</option>
                                                            <option value="NextVisit">Next Visit</option>
                                                            <option value="Booked">Booked</option>
                                                            <option value="SlightlyInterested">Slightly Interested</option>
                                                            <option value="NotBuy">Not Buy
                                                            </option>
                                                        </select>
                                                        <span class="error-message"> {{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <!-- Additional Number -->
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Additional Number" mode="eager" rules="" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="additionalNumber">Additional Number</label>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="AdditionalNumber"
                                                            name="additionalNumber"
                                                            placeholder="Additional Phone Number"
                                                        />
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>
                                            <!-- DOB -->
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Date of Birth" mode="eager" rules="" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="dob">Date of Birth</label>
                                                        <input
                                                            type="date"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="DOB"
                                                            name="dob"
                                                        />
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <!-- Gender -->
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Gender" mode="eager" rules="required" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="gender">Gender <span class="error">*</span></label>
                                                        <select
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="Gender"
                                                            name="gender"
                                                        >
                                                            <option value="">Select Gender</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <!-- Marital Status -->
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Marital Status" mode="eager" rules="" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="maritalStatus">Marital Status</label>
                                                        <select
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="MaritalStatus"
                                                            name="maritalStatus"
                                                        >
                                                            <option value="">Select Status</option>
                                                            <option value="Single">Single</option>
                                                            <option value="Married">Married</option>
                                                            <option value="Divorced">Divorced</option>
                                                            <option value="Widowed">Widowed</option>
                                                        </select>
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <!-- Height -->
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Height" mode="eager" rules="numeric" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="height">Height (cm)</label>
                                                        <input
                                                            type="number"
                                                            step="0.1"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="Height"
                                                            name="height"
                                                            placeholder="Height in cm"
                                                        />
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <!-- Weight -->
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Weight" mode="eager" rules="numeric" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="weight">Weight (kg)</label>
                                                        <input
                                                            type="number"
                                                            step="0.1"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="Weight"
                                                            name="weight"
                                                            placeholder="Weight in kg"
                                                        />
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <!-- NID/Passport Number -->
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="NID/Passport Number" mode="eager" rules="required" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="nidPassportNumber">NID/Passport Number <span class="error">*</span></label>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="NIDPassportNumber"
                                                            name="nidPassportNumber"
                                                            placeholder="NID or Passport Number"
                                                        />
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <!-- Geo Location -->
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Geo Location" mode="eager" rules="" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="geoLocation">Geo Location</label>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="GeoLocation"
                                                            name="geoLocation"
                                                            placeholder="Location coordinates"
                                                        />
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <!-- Visit Date -->
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="VisitDate" mode="eager" rules="required" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="visitDate">Visit Date <span class="error">*</span></label>
                                                        <input
                                                            type="date"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="VisitDate"
                                                            name="VisitDateDate"
                                                        />
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <!-- Registration Date -->
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Registration Date" mode="eager" rules="" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="registrationDate">Registration Date</label>
                                                        <input
                                                            type="date"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="RegistrationDate"
                                                            name="registrationDate"
                                                            @change="checkExpireDate"
                                                        />
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <!-- Expire Date -->
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Expire Date" mode="eager" rules="" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="expireDate">Expire Date</label>
                                                        <input
                                                            type="date"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="ExpireDate"
                                                            name="expireDate"
                                                        />
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <!-- Country Code -->
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Country Code" mode="eager" rules="" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="countryCode">Country Code</label>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="CountryCode"
                                                            name="countryCode"
                                                            placeholder="+880"
                                                        />
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nominee Information -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4 shadow-lg custom-card">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">Nominee Information</h5>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Nominee Name" mode="eager" rules="" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="nomineeName">Nominee Name</label>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="NomineeName"
                                                            name="nomineeName"
                                                            placeholder="Nominee's Full Name"
                                                        />
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Nominee Phone" mode="eager" rules="" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="nomineePhone">Nominee Phone</label>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="NomineePhone"
                                                            name="nomineePhone"
                                                            placeholder="Nominee's Phone Number"
                                                        />
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Nominee Gender" mode="eager" rules="" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="nomineeGender">Nominee Gender</label>
                                                        <select
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="NomineeGender"
                                                            name="nomineeGender"
                                                        >
                                                            <option value="">Select Gender</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Nominee Relationship" mode="eager" rules="" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="nomineeRelationship">Nominee Relationship</label>
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="NomineeRelationship"
                                                            name="nomineeRelationship"
                                                            placeholder="Nominee's Relationship"
                                                        />
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Nominee DOB" mode="eager" rules="" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="nomineeDOB">Nominee DOB</label>
                                                        <input
                                                            type="date"
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="NomineeDOB"
                                                            name="nomineeDOB"
                                                        />
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <div class="col-12 col-md-9">
                                                <ValidationProvider name="Nominee Bank Details" mode="eager" rules="" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="nomineeBankDetails">Nominee Bank Details</label>
                                                        <textarea
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="NomineeBankDetails"
                                                            name="nomineeBankDetails"
                                                            placeholder="Nominee's Bank Details"
                                                            rows="3"
                                                        ></textarea>
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Medical Information -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4 shadow-lg custom-card">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0">Medical Information</h5>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-md-3">
                                                <ValidationProvider name="Blood Group" mode="eager" rules="" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="bloodGroup">Blood Group</label>
                                                        <select
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="BloodGroup"
                                                            name="bloodGroup"
                                                        >
                                                            <option value="">Select Blood Group</option>
                                                            <option value="A+">A+</option>
                                                            <option value="A-">A-</option>
                                                            <option value="B+">B+</option>
                                                            <option value="B-">B-</option>
                                                            <option value="AB+">AB+</option>
                                                            <option value="AB-">AB-</option>
                                                            <option value="O+">O+</option>
                                                            <option value="O-">O-</option>
                                                        </select>
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>

                                            <div class="col-12 col-md-9">
                                                <ValidationProvider name="Disease Information" mode="eager" rules="" v-slot="{ errors }">
                                                    <div class="form-group">
                                                        <label for="diseaseInfo">Disease Information</label>
                                                        <textarea
                                                            class="form-control"
                                                            :class="{'error-border': errors[0]}"
                                                            v-model="DiseaseInfo"
                                                            name="diseaseInfo"
                                                            placeholder="Disease Information"
                                                            rows="4"
                                                        ></textarea>
                                                        <span class="error-message">{{ errors[0] }}</span>
                                                    </div>
                                                </ValidationProvider>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
<!--                        <div class="row">-->
<!--                            <div class="col-md-12">-->
<!--                                <div class="card mb-4 shadow-lg custom-card">-->
<!--                                    <div class="card-header bg-primary text-white">-->
<!--                                        <h5 class="mb-0">Payment Information</h5>-->
<!--                                    </div>-->

<!--                                    <div class="card-body">-->
<!--                                        <div class="row">-->
<!--                                            <div class="col-12 col-md-3">-->
<!--                                                <ValidationProvider name="Promo Code" mode="eager" rules="" v-slot="{ errors }">-->
<!--                                                    <div class="form-group">-->
<!--                                                        <label for="promoCode">Promo Code</label>-->
<!--                                                        <input-->
<!--                                                            type="text"-->
<!--                                                            class="form-control"-->
<!--                                                            :class="{'error-border': errors[0]}"-->
<!--                                                            v-model="PromoCode"-->
<!--                                                            name="promoCode"-->
<!--                                                            placeholder="Enter Promo Code"-->
<!--                                                        />-->
<!--                                                        <span class="error-message">{{ errors[0] }}</span>-->
<!--                                                    </div>-->
<!--                                                </ValidationProvider>-->
<!--                                            </div>-->

<!--                                            <div class="col-12 col-md-3">-->
<!--                                                <ValidationProvider name="Bank" mode="eager" rules="" v-slot="{ errors }">-->
<!--                                                    <div class="form-group">-->
<!--                                                        <label for="bank">Bank</label>-->
<!--                                                        <multiselect-->
<!--                                                            v-model="selectedBank"-->
<!--                                                            :options="bankOptions"-->
<!--                                                            :multiple="false"-->
<!--                                                            :close-on-select="true"-->
<!--                                                            :clear-on-select="false"-->
<!--                                                            :preserve-search="true"-->
<!--                                                            placeholder="Select Bank"-->
<!--                                                            label="BankName"-->
<!--                                                            track-by="BankID"-->
<!--                                                            :class="{'error-border': errors[0]}"-->
<!--                                                        >-->
<!--                                                        </multiselect>-->
<!--                                                        <span class="error-message">{{ errors[0] }}</span>-->
<!--                                                    </div>-->
<!--                                                </ValidationProvider>-->
<!--                                            </div>-->

<!--                                            <div class="col-12 col-md-3">-->
<!--                                                <ValidationProvider name="Wallet" mode="eager" rules="" v-slot="{ errors }">-->
<!--                                                    <div class="form-group">-->
<!--                                                        <label for="wallet">Wallet</label>-->
<!--                                                        <input-->
<!--                                                            type="text"-->
<!--                                                            class="form-control"-->
<!--                                                            :class="{'error-border': errors[0]}"-->
<!--                                                            v-model="Wallet"-->
<!--                                                            name="wallet"-->
<!--                                                            placeholder="Wallet Information"-->
<!--                                                        />-->
<!--                                                        <span class="error-message">{{ errors[0] }}</span>-->
<!--                                                    </div>-->
<!--                                                </ValidationProvider>-->
<!--                                            </div>-->

<!--                                            <div class="col-12 col-md-3">-->
<!--                                                <ValidationProvider name="Currency" mode="eager" rules="required" v-slot="{ errors }">-->
<!--                                                    <div class="form-group">-->
<!--                                                        <label for="currency">Currency <span class="error">*</span></label>-->
<!--                                                        <select-->
<!--                                                            class="form-control"-->
<!--                                                            :class="{'error-border': errors[0]}"-->
<!--                                                            v-model="Currency"-->
<!--                                                            name="currency"-->
<!--                                                        >-->
<!--                                                            <option value="">Select Currency</option>-->
<!--                                                            <option value="BDT">BDT - Bangladeshi Taka</option>-->
<!--                                                            <option value="USD">USD - US Dollar</option>-->
<!--                                                            <option value="EUR">EUR - Euro</option>-->
<!--                                                            <option value="GBP">GBP - British Pound</option>-->
<!--                                                            <option value="INR">INR - Indian Rupee</option>-->
<!--                                                            <option value="JPY">JPY - Japanese Yen</option>-->
<!--                                                        </select>-->
<!--                                                        <span class="error-message">{{ errors[0] }}</span>-->
<!--                                                    </div>-->
<!--                                                </ValidationProvider>-->
<!--                                            </div>-->

<!--                                            <div class="col-12 col-md-3">-->
<!--                                                <ValidationProvider name="Refund Option" mode="eager" rules="" v-slot="{ errors }">-->
<!--                                                    <div class="form-group">-->
<!--                                                        <label for="refundOption">Refund Option</label>-->
<!--                                                        <input-->
<!--                                                            type="text"-->
<!--                                                            class="form-control"-->
<!--                                                            :class="{'error-border': errors[0]}"-->
<!--                                                            v-model="RefundOption"-->
<!--                                                            name="refundOption"-->
<!--                                                            placeholder="Refund Option"-->
<!--                                                        />-->
<!--                                                        <span class="error-message">{{ errors[0] }}</span>-->
<!--                                                    </div>-->
<!--                                                </ValidationProvider>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-md-12" style="text-align: right">
                                <button type="submit" class="btn btn-primary">{{ buttonText }}</button>
                            </div>

                        </div>

                    </form>
                </ValidationObserver>
            </div>
        </div>
    </div>
</template>

<script>
import { bus } from "../../app";
import { Common } from "../../mixins/common";
import { ValidationObserver, ValidationProvider } from 'vee-validate';
import Multiselect from 'vue-multiselect';

export default {
    mixins: [Common],
    data() {
        return {
            title: 'Add New Sale',
            // Customer Information
            selectedPartner: null,
            selectedPackage: null,
            selectedAgent: null,
            FullName: '',
            Email: '',
            Password: '',
            CountryCode: '+880',
            MobileNumber: '',
            AdditionalNumber: '',
            MembershipID: '',
            visitType: '',
            DOB: '',
            Gender: '',
            MaritalStatus: '',
            ProfilePicture: '',
            GeoLocation: '',
            Height: '',
            Weight: '',
            NIDPassportNumber: '',
            BankDetails: '',
            RegistrationDate: '',
            VisitDate: '',
            ExpireDate: '',

            // Nominee Information
            NomineeName: '',
            NomineePhone: '',
            NomineeGender: '',
            NomineeRelationship: '',
            NomineeDOB: '',
            NomineeBankDetails: '',

            // Medical Information
            BloodGroup: '',
            DiseaseInfo: '',

            // Payment Information
            PromoCode: '',
            selectedBank: null,
            Wallet: '',
            Currency: '',
            RefundOption: '',

            buttonText: 'Save',
            actionType: 'add',
            selectedSale: null,

            // Options
            partners: [],
            packages: [],
            agents: [],
            bankOptions: [
                { BankID: 1, BankName: 'Dutch-Bangla Bank' },
                { BankID: 2, BankName: 'Islami Bank Bangladesh' },
                { BankID: 3, BankName: 'BRAC Bank' },
                { BankID: 4, BankName: 'City Bank' },
                { BankID: 5, BankName: 'Eastern Bank' },
                { BankID: 6, BankName: 'Premier Bank' },
                { BankID: 7, BankName: 'Standard Chartered Bank' },
                { BankID: 8, BankName: 'Sonali Bank' },
                { BankID: 9, BankName: 'Janata Bank' },
                { BankID: 10, BankName: 'Agrani Bank' }
            ]
        };
    },
    mounted() {
        this.fetchSupportingData();
        // console.log(this.$route.query.saleID)

        // Set default registration date to today
        this.VisitDate = new Date().toISOString().split('T')[0];
    },
    methods: {
        fetchSupportingData() {
            this.axiosGet('sale/get-supporting-data', (response) => {
                this.partners = response.data.partners || [];
                this.packages = response.data.packages || [];
                this.agents = response.data.agents || [];

                if (this.$route.query.saleID) {
                    this.loadSaleData(this.$route.query.saleID);
                }
            }, (error) => {
                this.errorNoti('Error loading supporting data');
            });
        },

        loadSaleData(saleId) {
            this.axiosGet(`sale/get-sale-info/${saleId}`, (response) => {
                const sale = response.data;
                this.title = 'Update Sale';
                this.buttonText = "Update";
                this.actionType = 'edit';
                console.log(this.partners)
                console.log(sale.PartnerID)

                // Set multiselect values
               // this.selectedPartner = this.partners.find(p => p.PartnerID === sale.PartnerID) || null;
                if (this.partners.length && this.packages.length && this.agents.length) {
                    this.selectedPartner = this.partners.find(p => Number(p.PartnerID) === Number(sale.PartnerID)) || null;
                    this.selectedPackage = this.packages.find(p => Number(p.PackageID) === Number(sale.PackageID)) || null;
                    this.selectedAgent   = this.agents.find(a => Number(a.AgentID) === Number(sale.AgentID)) || null;
                }

                // Customer Information
                this.FullName = sale.FullName || '';
                this.Email = sale.Email || '';
                this.Password = sale.Password || '';
                this.CountryCode = sale.CountryCode || '+880';
                this.MobileNumber = sale.MobileNumber || '';
                this.AdditionalNumber = sale.AdditionalNumber || '';
                this.MembershipID = sale.MembershipID || '';
                this.DOB = sale.DOB || '';
                this.Gender = sale.Gender || '';
                this.MaritalStatus = sale.MaritalStatus || '';
                this.ProfilePicture = sale.ProfilePicture || '';
                this.GeoLocation = sale.GeoLocation || '';
                this.Height = parseInt(sale.Height)   || '';
                this.Weight = parseInt(sale.Weight) || '';
                this.NIDPassportNumber = sale.NIDPassportNumber || '';
                this.BankDetails = sale.BankDetails || '';
                this.RegistrationDate = sale.RegistrationDate || '';
                this.ExpireDate = sale.ExpireDate || '';
                this.visitType = sale.VisitType || '';

                // Nominee Information
                this.NomineeName = sale.NomineeName || '';
                this.NomineePhone = sale.NomineePhone || '';
                this.NomineeGender = sale.NomineeGender || '';
                this.NomineeRelationship = sale.NomineeRelationship || '';
                this.NomineeDOB = sale.NomineeDOB || '';
                this.NomineeBankDetails = sale.NomineeBankDetails || '';

                // Medical Information
                this.BloodGroup = sale.BloodGroup || '';
                this.DiseaseInfo = sale.DiseaseInfo || '';

                // Payment Information
                this.PromoCode = sale.PromoCode || '';
                this.Wallet = sale.Wallet || '';
                this.Currency = sale.Currency || '';
                this.RefundOption = sale.RefundOption || '';

                this.selectedSale = sale;
            }, (error) => {
                this.errorNoti(error.message || 'Error loading sale data');
            });
        },
        handlePhotoUpload(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2048000) {
                    this.errorNoti('File size must be less than 2MB');
                    this.$refs.photoInput.value = '';
                    return;
                }

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    this.errorNoti('Only JPG, PNG, GIF files are allowed');
                    this.$refs.photoInput.value = '';
                    return;
                }

                this.ProfilePicture = file;

                // Create preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.currentPhotoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        checkExpireDate() {
            if (this.RegistrationDate && this.selectedPackage) {
                const pkg = this.packages.find(
                    p => p.PackageID === this.selectedPackage.PackageID
                );

                if (pkg && pkg.Duration) {
                    let regDate = new Date(this.RegistrationDate);

                    // Ensure Duration is treated as an integer number of years
                    let durationYears = parseInt(pkg.Duration, 10) || 0;

                    // Add years safely by creating new date components
                    let year = regDate.getFullYear() + durationYears;
                    let month = regDate.getMonth();
                    let day = regDate.getDate();

                    // Special handling for Feb 29 on non-leap years
                    if (month === 1 && day === 29 && !((year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0))) {
                        day = 28;
                    }

                    // Build safe date string manually (yyyy-MM-dd)
                    const expireDate = new Date(year, month, day);
                    const yyyy = expireDate.getFullYear();
                    const mm = String(expireDate.getMonth() + 1).padStart(2, '0');
                    const dd = String(expireDate.getDate()).padStart(2, '0');

                    this.ExpireDate = `${yyyy}-${mm}-${dd}`;
                }
            }
        },
        submitForm() {
            this.$store.commit('submitButtonLoadingStatus', true);

            let url = this.actionType === 'add' ? 'sale/add' : 'sale/update';

            const saleData = {
                // IDs from multiselects
                PartnerID: this.selectedPartner ? this.selectedPartner.PartnerID : null,
                PackageID: this.selectedPackage ? this.selectedPackage.PackageID : null,
                AgentID: this.selectedAgent ? this.selectedAgent.AgentID : null,

                // Customer Information
                FullName: this.FullName,
                Email: this.Email,
                Password: this.Password,
                CountryCode: this.CountryCode,
                MobileNumber: this.MobileNumber,
                AdditionalNumber: this.AdditionalNumber,
                MembershipID: this.MembershipID,
                visitType: this.visitType,
                DOB: this.DOB,
                Gender: this.Gender,
                MaritalStatus: this.MaritalStatus,
                GeoLocation: this.GeoLocation,
                Height: this.Height,
                Weight: this.Weight,
                NIDPassportNumber: this.NIDPassportNumber,
                BankDetails: this.BankDetails,
                VisitDate: this.VisitDate,
                RegistrationDate: this.RegistrationDate,
                ExpireDate: this.ExpireDate,

                // Nominee Information
                NomineeName: this.NomineeName,
                NomineePhone: this.NomineePhone,
                NomineeGender: this.NomineeGender,
                NomineeRelationship: this.NomineeRelationship,
                NomineeDOB: this.NomineeDOB,
                NomineeBankDetails: this.NomineeBankDetails,

                // Medical Information
                BloodGroup: this.BloodGroup,
                DiseaseInfo: this.DiseaseInfo,

                // Payment Information
                PromoCode: this.PromoCode,
                Bank: this.selectedBank ? this.selectedBank.BankName : '',
                Wallet: this.Wallet,
                Currency: this.Currency,
                RefundOption: this.RefundOption
            };

            // Add SaleID for update
            if (this.actionType === 'edit' && this.selectedSale) {
                saleData.SaleID = this.selectedSale.SaleID;
            }

            // ✅ Convert to FormData
            let formData = new FormData();

            Object.keys(saleData).forEach(key => {
                if (saleData[key] !== undefined && saleData[key] !== null) {
                    formData.append(key, saleData[key]);
                }
            });

// ✅ Attach file separately
            if (this.ProfilePicture) {
                formData.append("ProfilePicture", this.ProfilePicture); // this.ProfilePicture must be a File/Blob
            }
            this.axiosPost(url, formData, (response) => {
                this.successNoti(response.message);
                this.$router.push({ name: 'Sales' });
                this.$store.commit('submitButtonLoadingStatus', false);
                this.resetForm();
            }, (error) => {
                this.errorNoti(error.message || 'Error saving sale');
                this.$store.commit('submitButtonLoadingStatus', false);
            });
        },

        resetForm() {
            // Reset multiselects
            this.selectedPartner = null;
            this.selectedPackage = null;
            this.selectedAgent = null;
           // this.selectedBank = null;

            // Reset customer information
            this.FullName = '';
            this.Email = '';
            this.Password = '';
            this.CountryCode = '+880';
            this.MobileNumber = '';
            this.AdditionalNumber = '';
            this.MembershipID = '';
            this.visitType = '';
            this.DOB = '';
            this.Gender = '';
            this.MaritalStatus = '';
            this.ProfilePicture = '';
            this.GeoLocation = '';
            this.Height = '';
            this.Weight = '';
            this.NIDPassportNumber = '';
            this.BankDetails = '';
            this.RegistrationDate = '';
            this.VisitDate = new Date().toISOString().split('T')[0];
            this.ExpireDate = '';

            // Reset nominee information
            this.NomineeName = '';
            this.NomineePhone = '';
            this.NomineeGender = '';
            this.NomineeRelationship = '';
            this.NomineeDOB = '';
            this.NomineeBankDetails = '';

            // Reset medical information
            this.BloodGroup = '';
            this.DiseaseInfo = '';

            // Reset payment information
            // this.PromoCode = '';
            // this.Wallet = '';
            // this.Currency = '';
            // this.RefundOption = '';

            this.selectedSale = null;

            // Reset validation
            this.$nextTick(() => {
                this.$refs.observer.reset();
            });
        },

        goBack() {
            this.$router.push({ name: 'SalesListPage' });
        }
    }
};
</script>

<style scoped>
/* Import vue-multiselect CSS */
@import "~vue-multiselect/dist/vue-multiselect.min.css";

/* Card container with blur background */
.custom-card {
    background-color: #ffffff;
    border-radius: 10px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.custom-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #ffffff;
    backdrop-filter: blur(8px);
    z-index: -1;
}

/* Validation error styles */
.error {
    color: #dc3545;
}

.error-message {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}

.error-border {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

/* Multiselect error styling */
.multiselect.error-border .multiselect__tags {
    border-color: #dc3545 !important;
}

.multiselect.error-border:focus-within .multiselect__tags {
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

/* Card Headers Color Styles */
.bg-primary {
    background-color: #007bff !important;
}

.bg-success {
    background-color: #28a745 !important;
}

.bg-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

.bg-info {
    background-color: #17a2b8 !important;
}

/* Form group spacing */
.form-group {
    margin-bottom: 1.5rem;
}

/* Responsive for smaller screens */
@media (max-width: 767px) {
    .container-fluid {
        padding: 15px;
    }

    .col-md-3, .col-md-4, .col-md-6, .col-md-9 {
        margin-bottom: 15px;
    }
}

/* Multiselect custom styling */
.multiselect__tags {
    min-height: 38px;
    padding: 6px 12px;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.multiselect__placeholder {
    color: #6c757d;
    padding-top: 0;
    padding-left: 0;
}

.multiselect__single {
    padding-left: 0;
    color: #495057;
}
</style>
