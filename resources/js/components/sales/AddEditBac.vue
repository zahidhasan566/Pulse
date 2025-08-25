<template>
    <div class="container-fluid">
        <breadcrumb :options="['Sales', 'Add/Edit Sale']">
            <button class="btn btn-secondary" @click="goBack">Back to Sales List</button>
        </breadcrumb>

        <div class="row">
            <div class="col-12 col-md-12">
                <h3>{{ title }}</h3>
                <form @submit.prevent="handleSubmit">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4 shadow-lg custom-card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Customer Information</h5>
                                </div>

                                <div class="card-body">

                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label for="partnerID">Select Partner <span class="text-danger">*</span></label>
                                            <select
                                                class="form-control"
                                                v-model="PartnerID"
                                                name="partnerID"
                                                required
                                            >
                                                <option v-for="partner in partners" :key="partner.PartnerID" :value="partner.PartnerID">{{ partner.PartnerName }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Select Partner -->
                                    <div class="col-12 col-md-3">
                                        <!-- Select PackageMaster -->
                                        <div class="form-group">
                                            <label for="packageID">Select Package <span class="text-danger">*</span></label>
                                            <select
                                                class="form-control"
                                                v-model="PackageID"
                                                name="packageID"
                                                required
                                            >
                                                <option v-for="packaged in packages" :key="packaged.PackageID" :value="packaged.PackageID">{{ packaged.PackageName }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <!-- Select Agent -->
                                        <div class="form-group">
                                            <label for="agentID">Select Agent <span class="text-danger">*</span></label>
                                            <select
                                                class="form-control"
                                                v-model="AgentID"
                                                name="agentID"
                                                required
                                            >
                                                <option v-for="agent in agents" :key="agent.AgentID" :value="agent.AgentID">{{ agent.AgentName }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <!-- Full Name -->
                                        <div class="form-group">
                                            <label for="fullName">Full Name <span class="text-danger">*</span></label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                v-model="FullName"
                                                name="fullName"
                                                placeholder="Full Name"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <!-- Mobile Number -->
                                        <div class="form-group">
                                            <label for="mobileNumber">Mobile Number <span class="text-danger">*</span></label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                v-model="MobileNumber"
                                                name="mobileNumber"
                                                placeholder="Mobile Number"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <!-- Sale Date -->
                                        <div class="form-group">
                                            <label for="saleDate">Sale Date <span class="text-danger">*</span></label>
                                            <input
                                                type="date"
                                                class="form-control"
                                                v-model="SaleDate"
                                                name="saleDate"
                                                required
                                            />
                                        </div>
                                    </div>
                                </div>



                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4 shadow-lg custom-card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Nominee Information</h5>
                                </div>

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label for="nomineeName">Nominee Name</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="NomineeName"
                                                    name="nomineeName"
                                                    placeholder="Nominee's Full Name"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label for="nomineePhone">Nominee Phone</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="NomineePhone"
                                                    name="nomineePhone"
                                                    placeholder="Nominee's Phone Number"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label for="nomineeGender">Nominee Gender</label>
                                                <select
                                                    class="form-control"
                                                    v-model="NomineeGender"
                                                    name="nomineeGender"
                                                >
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label for="nomineeRelationship">Nominee Relationship</label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="NomineeRelationship"
                                                    name="nomineeRelationship"
                                                    placeholder="Nominee's Relationship"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label for="nomineeDOB">Nominee DOB</label>
                                                <input
                                                    type="date"
                                                    class="form-control"
                                                    v-model="NomineeDOB"
                                                    name="nomineeDOB"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label for="nomineeBankDetails">Nominee Bank Details</label>
                                                <textarea
                                                    class="form-control"
                                                    v-model="NomineeBankDetails"
                                                    name="nomineeBankDetails"
                                                    placeholder="Nominee's Bank Details"
                                                ></textarea>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4 shadow-lg custom-card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Medical Information</h5>
                                </div>

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-12 col-md-3">
                                            <div class="form-group">
                                                <label for="diseaseInfo">Disease Information</label>
                                                <textarea
                                                    class="form-control"
                                                    v-model="DiseaseInfo"
                                                    name="diseaseInfo"
                                                    placeholder="Disease Information"
                                                    rows="4"
                                                ></textarea>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4 shadow-lg custom-card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Payment Information</h5>
                            </div>

                            <div class="card-body">


                                <div class="row">

                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label for="promoCode">Promo Code</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                v-model="PromoCode"
                                                name="promoCode"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label for="bank">Bank</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                v-model="Bank"
                                                name="bank"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label for="wallet">Wallet</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                v-model="Wallet"
                                                name="wallet"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label for="currency">Currency</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                v-model="Currency"
                                                name="currency"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="form-group">
                                            <label for="refundOption">Refund Option</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                v-model="RefundOption"
                                                name="refundOption"
                                            />
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">{{ buttonText }}</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { bus } from "../../app";
import { Common } from "../../mixins/common";

export default {
    mixins: [Common],
    data() {
        return {
            title: '',
            PartnerID: '',
            PackageID: '',
            AgentID: '',
            FullName: '',
            MobileNumber: '',
            SaleDate: '',
            PromoCode: '',
            Bank: '',
            Wallet: '',
            Currency: '',
            RefundOption: '',
            NomineeName: '',
            NomineePhone: '',
            NomineeGender: '',
            NomineeRelationship: '',
            NomineeDOB: '',
            NomineeBankDetails: '',
            BloodGroup: '',
            DiseaseInfo: '',
            buttonText: '',
            actionType: '',
            buttonShow: false,
            selectedSale: null,
            partners: [],
            packages: [],
            agents: []
        };
    },
    mounted() {
        this.buttonText = "Save";
        this.fetchSupportingData();  // Fetch partners, packages, and agents data
        if (this.$route.params.id) {
            this.loadSaleData(this.$route.params.id);
        }
    },
    methods: {
        fetchSupportingData() {
            this.axiosGet('sale/get-supporting-data', (response) => {
                this.partners = response.data.partners;
                this.packages = response.data.packages;
                this.agents = response.data.agents;
            });
        },
        loadSaleData(saleId) {
            this.axiosGet(`sale/get-sale-info/${saleId}`, (response) => {
                const sale = response.data;
                this.title = 'Update Sale';
                this.buttonText = "Update";
                this.PartnerID = sale.PartnerID;
                this.PackageID = sale.PackageID;
                this.AgentID = sale.AgentID;
                this.FullName = sale.FullName;
                this.MobileNumber = sale.MobileNumber;
                this.SaleDate = sale.SaleDate;
                this.PromoCode = sale.PromoCode || '';
                this.Bank = sale.Bank || '';
                this.Wallet = sale.Wallet || '';
                this.Currency = sale.Currency || '';
                this.RefundOption = sale.RefundOption || '';
                this.NomineeName = sale.NomineeName || '';
                this.NomineePhone = sale.NomineePhone || '';
                this.NomineeGender = sale.NomineeGender || '';
                this.NomineeRelationship = sale.NomineeRelationship || '';
                this.NomineeDOB = sale.NomineeDOB || '';
                this.NomineeBankDetails = sale.NomineeBankDetails || '';
                this.BloodGroup = sale.BloodGroup || '';
                this.DiseaseInfo = sale.DiseaseInfo || '';
                this.selectedSale = sale;

                this.actionType = 'edit';
            }, (error) => {
                this.errorNoti(error.message || 'Error loading sale data');
            });
        },
        handleSubmit() {
            this.$store.commit('submitButtonLoadingStatus', true);

            let url = '';
            if (this.actionType === 'add') {
                url = 'sale/add';
            } else {
                url = 'sale/update';
            }

            const saleData = {
                PartnerID: this.PartnerID,
                PackageID: this.PackageID,
                AgentID: this.AgentID,
                FullName: this.FullName,
                MobileNumber: this.MobileNumber,
                SaleDate: this.SaleDate,
                PromoCode: this.PromoCode,
                Bank: this.Bank,
                Wallet: this.Wallet,
                Currency: this.Currency,
                RefundOption: this.RefundOption,
                NomineeName: this.NomineeName,
                NomineePhone: this.NomineePhone,
                NomineeGender: this.NomineeGender,
                NomineeRelationship: this.NomineeRelationship,
                NomineeDOB: this.NomineeDOB,
                NomineeBankDetails: this.NomineeBankDetails,
                BloodGroup: this.BloodGroup,
                DiseaseInfo: this.DiseaseInfo
            };

            // Add SaleID for update
            if (this.actionType === 'edit' && this.selectedSale) {
                saleData.SaleID = this.selectedSale.SaleID;
            }

            this.axiosPost(url, saleData, (response) => {
                this.successNoti(response.message);
                this.$router.push({ name: 'SalesListPage' });  // Redirect after submission
                this.$store.commit('submitButtonLoadingStatus', false);
                this.resetForm();
            }, (error) => {
                this.errorNoti(error.message || 'Error saving sale');
                this.$store.commit('submitButtonLoadingStatus', false);
            });
        },
        resetForm() {
            this.PartnerID = '';
            this.PackageID = '';
            this.AgentID = '';
            this.FullName = '';
            this.MobileNumber = '';
            this.SaleDate = '';
            this.PromoCode = '';
            this.Bank = '';
            this.Wallet = '';
            this.Currency = '';
            this.RefundOption = '';
            this.NomineeName = '';
            this.NomineePhone = '';
            this.NomineeGender = '';
            this.NomineeRelationship = '';
            this.NomineeDOB = '';
            this.NomineeBankDetails = '';
            this.BloodGroup = '';
            this.DiseaseInfo = '';
            this.selectedSale = null;
        },
        goBack() {
            this.$router.push({ name: 'SalesListPage' });
        }
    }
};
</script>


<style scoped>
/* Card container with blur background */
.custom-card {
    background-color: #ffffff; /* Light background for cards */
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
    background:  #ffffff;
    backdrop-filter: blur(8px); /* Blurring the background */
    z-index: -1;
}

/*.card-header {*/
/*    border-radius: 10px 10px 0 0;*/
/*    padding: 15px;*/
/*    font-size: 1.25rem;*/
/*    font-weight: bold;*/
/*    text-transform: uppercase;*/
/*}*/

/*.card-body {*/
/*    padding: 20px;*/
/*}*/


/* Improved Input and Textarea Styling */

/* Card Headers Color Styles */
.bg-primary {
    background-color: #007bff !important;
}

.bg-success {
    background-color: #28a745 !important;
}

.bg-warning {
    background-color: #ffc107 !important;

}

.bg-info {
    background-color: #17a2b8 !important;
}

/* Hover Effect for Cards */
/*.card:hover {*/
/*    transform: translateY(-5px);*/
/*    transition: transform 0.3s ease;*/
/*}*/

.card:active {
    transform: translateY(0px);
}

/* Responsive for smaller screens */
@media (max-width: 767px) {
    .container {
        padding: 20px;
    }

    .col-md-8 {
        width: 100%;
    }
}
</style>
