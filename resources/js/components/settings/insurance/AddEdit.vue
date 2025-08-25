<template>
    <div>
        <div class="modal fade" id="add-edit-partner" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title modal-title-font" id="exampleModalLabel">{{ title }}</div>
                    </div>

                    <ValidationObserver v-slot="{ handleSubmit }">
                        <form class="form-horizontal" id="form" @submit.prevent="handleSubmit(onSubmit)">
                            <div class="modal-body">
                                <div class="row">

                                    <!-- Partner Name -->
                                    <div class="col-12 col-md-6">
                                        <ValidationProvider name="Partner Name" mode="eager" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="partnerName">Insurance Company Name <span class="error">*</span></label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    :class="{'error-border': errors[0]}"
                                                    id="partnerName"
                                                    v-model="InsuranceName"
                                                    name="Insurance-name"
                                                    placeholder="Insurance Name"
                                                    autocomplete="off"
                                                >
                                                <span class="error-message">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                    <!-- Partner Logo -->
                                    <div class="col-12 col-md-6">
                                        <ValidationProvider name="Partner Logo" mode="eager" rules="" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="partnerLogo"> Insurance Logo</label>
                                                <input
                                                    type="file"
                                                    class="form-control-file"
                                                    :class="{'error-border': errors[0]}"
                                                    id="partnerLogo"
                                                    @change="handleLogoUpload"
                                                    accept="image/*"
                                                    ref="logoInput"
                                                >
                                                <small class="form-text text-muted">Accepted formats: JPG, PNG, GIF. Max size: 2MB</small>
                                                <span class="error-message">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                    <!-- Current Logo Preview -->
                                    <div class="col-12" v-if="currentLogoPreview">
                                        <div class="form-group">
                                            <label>Current Logo:</label>
                                            <div class="mt-2">
                                                <img
                                                    :src="`${mainOrigin}public/storage/${currentLogoPreview}`"
                                                    alt="Logo Preview" class="logo-preview" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Partner Details -->
                                    <div class="col-12">
                                        <ValidationProvider name="Partner Details" mode="eager" rules="" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="partnerDetails">Insurance Details</label>
                                                <textarea
                                                    class="form-control"
                                                    :class="{'error-border': errors[0]}"
                                                    id="partnerDetails"
                                                    v-model="InsuranceDetails"
                                                    name="insurance-details"
                                                    placeholder="Insurance Details"
                                                    rows="4"
                                                ></textarea>
                                                <span class="error-message">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                </div>
                            </div>

                            <div class="modal-footer">
                                <submit-form v-if="buttonShow" :name="buttonText"/>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="closeModal">Close</button>
                            </div>
                        </form>
                    </ValidationObserver>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { bus } from "../../../app";
import { Common } from "../../../mixins/common";

export default {
    mixins: [Common],
    data() {
        return {
            title: '',
            InsuranceName: '',
            InsuranceDetails: '',
            InsuranceLogo: null,
            currentLogoPreview: null,
            buttonText: '',
            actionType: '',
            buttonShow: false,
            selectedPartner: null
        }
    },
    computed: {},
    mounted() {
        $('#add-edit-partner').on('hidden.bs.modal', () => {
            this.$emit('changeStatus')
        });

        bus.$on('add-edit-insurance', (row) => {
            if (row) {
                // Edit mode
                let instance = this;
                this.axiosGet('insurance/get-insurance-info/' + row.InsuranceID, function(response) {  // Updated to grouped route
                    var insurance = response.data;
                    instance.title = 'Update Partner';
                    instance.buttonText = "Update";
                    instance.InsuranceName = insurance.InsuranceName;
                    instance.InsuranceDetails = insurance.InsuranceDetails || '';
                    instance.selectedPartner = insurance;

                    // Set current logo preview if exists
                    if (insurance.InsuranceLogo) {
                        instance.currentLogoPreview = insurance.InsuranceLogo;
                    } else {
                        instance.currentLogoPreview = null;
                    }

                    instance.buttonShow = true;
                    instance.actionType = 'edit';
                }, function(error) {
                    instance.errorNoti(error.message || 'Error loading partner data');
                });
            } else {
                // Add mode
                this.title = 'Add Partner';
                this.buttonText = "Add";
                this.InsuranceName = '';
                this.InsuranceName = '';
                this.InsuranceLogo = null;
                this.currentLogoPreview = null;
                this.selectedPartner = null;
                this.buttonShow = true;
                this.actionType = 'add';
            }

            $("#add-edit-partner").modal("toggle");
        })
    },
    destroyed() {
        bus.$off('add-edit-insurance')
    },
    methods: {
        closeModal() {
            $("#add-edit-partner").modal("toggle");
        },

        handleLogoUpload(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2048000) {
                    this.errorNoti('File size must be less than 2MB');
                    this.$refs.logoInput.value = '';
                    return;
                }

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    this.errorNoti('Only JPG, PNG, GIF files are allowed');
                    this.$refs.logoInput.value = '';
                    return;
                }

                this.InsuranceLogo = file;

                // Create preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.currentLogoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        onSubmit() {
            this.$store.commit('submitButtonLoadingStatus', true);

            let url = '';
            if (this.actionType === 'add') {
                url = 'insurance/add';  // Updated to grouped route
            } else {
                url = 'insurance/update';  // Updated to grouped route
            }

            // Create FormData for file upload
            const formData = new FormData();
            formData.append('InsuranceName', this.InsuranceName);
            formData.append('InsuranceDetails', this.InsuranceDetails || '');

            // Add PartnerID for update
            if (this.actionType === 'edit' && this.selectedPartner) {
                formData.append('InsuranceID', this.selectedPartner.InsuranceID);
            }

            if (this.InsuranceLogo) {
                formData.append('InsuranceLogo', this.InsuranceLogo);
            }

            this.axiosPost(url, formData, (response) => {
                this.successNoti(response.message);
                $("#add-edit-partner").modal("toggle");
                bus.$emit('refresh-datatable');
                this.$store.commit('submitButtonLoadingStatus', false);
                this.resetForm();
            }, (error) => {
                this.errorNoti(error.message || 'Error saving partner');
                this.$store.commit('submitButtonLoadingStatus', false);
            })
        },

        resetForm() {
            this.InsuranceName = '';
            this.InsuranceDetails = '';
            this.InsuranceLogo = null;
            this.currentLogoPreview = null;
            this.selectedPartner = null;

            // Reset file input
            if (this.$refs.logoInput) {
                this.$refs.logoInput.value = '';
            }
        },

        getLogoUrl(logoPath) {
            return `/storage/${logoPath}`;
        }
    }
}
</script>

<style scoped>
.logo-preview {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #ddd;
    padding: 4px;
}

.error-border {
    border-color: #dc3545 !important;
}

.error {
    color: #dc3545;
}

.error-message {
    color: #dc3545;
    font-size: 0.875em;
}
</style>
