<template>
    <div>
        <div class="modal fade" id="add-edit-package" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title modal-title-font" id="exampleModalLabel">{{ title }}</div>
                    </div>

                    <ValidationObserver v-slot="{ handleSubmit }">
                        <form class="form-horizontal" id="form" @submit.prevent="handleSubmit(onSubmit)">
                            <div class="modal-body">
                                <div class="row">

                                    <!-- Package Name -->
                                    <div class="col-12 col-md-6">
                                        <ValidationProvider name="Package Name" mode="eager" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="packageName">Package Name <span class="error">*</span></label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    :class="{'error-border': errors[0]}"
                                                    id="packageName"
                                                    v-model="PackageName"
                                                    name="package-name"
                                                    placeholder="Package Name"
                                                    autocomplete="off"
                                                >
                                                <span class="error-message">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                    <!-- Partners Multiselect -->
                                    <div class="col-12 col-md-6">
                                        <ValidationProvider name="Partner" mode="eager"  rules="required|min:1" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label>Partner(s) <span class="error">*</span></label>
                                                <multiselect
                                                    v-model="selectedPartners"
                                                    :options="allPartners"
                                                    :multiple="true"
                                                    :close-on-select="true"
                                                    :clear-on-select="false"
                                                    :preserve-search="true"
                                                placeholder="Select Partner(s)"
                                                label="PartnerName"
                                                track-by="PartnerID"
                                                :class="{'error-border': errors[0]}"
                                                >
                                                </multiselect>
                                                <span class="error-message">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                    <!-- Service -->
                                    <div class="col-12 col-md-6">
                                        <ValidationProvider name="Service" mode="eager" rules="required|min:1" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label>Service <span class="error">*</span></label>
                                                <multiselect
                                                    v-model="selectedServices"
                                                    :options="allServices"
                                                    :multiple="true"
                                                    :close-on-select="true"
                                                    :clear-on-select="false"
                                                    :preserve-search="true"
                                                    placeholder="Select Service"
                                                    label="ServiceName"
                                                    track-by="ServiceID"
                                                    :class="{'error-border': errors[0]}"
                                                    @select="updateElementsMeasurement"
                                                @remove="updateElementsMeasurement"
                                                >
                                                </multiselect>
                                                <span class="error-message">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                    <!-- Duration -->
                                    <div class="col-12 col-md-6">
                                        <ValidationProvider name="Duration" mode="eager" rules="required|numeric|min_value:1" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="duration">Duration (in years) <span class="error">*</span></label>
                                                <input
                                                    type="number"
                                                    min="1"
                                                    class="form-control"
                                                    :class="{'error-border': errors[0]}"
                                                    id="duration"
                                                    v-model="Duration"
                                                    name="duration"
                                                    placeholder="Duration in years"
                                                    autocomplete="off"
                                                >
                                                <span class="error-message">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                    <!-- Elements Measurement -->
                                    <div class="col-12 col-md-6">
                                        <ValidationProvider name="Elements Measurement" mode="eager" rules="numeric|min_value:0" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="elementsMeasurement">Elements Measurement</label>
                                                <input
                                                    type="number"
                                                    step="any"
                                                    min="0"
                                                    class="form-control"
                                                    :class="{'error-border': errors[0]}"
                                                    id="elementsMeasurement"
                                                    v-model="ElementsMeasurement"
                                                    name="elements-measurement"
                                                    placeholder="0.00"
                                                    autocomplete="off"
                                                    readonly
                                                >
                                                <span class="error-message">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>


                                    <!-- Package Image -->
                                    <div class="col-12 col-md-6">
                                        <ValidationProvider name="Package Image" mode="eager" rules="" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="packageImage">Package Image</label>
                                                <input
                                                    type="file"
                                                    class="form-control-file"
                                                    :class="{'error-border': errors[0]}"
                                                    id="packageImage"
                                                    @change="handleImageUpload"
                                                    accept="image/*"
                                                    ref="imageInput"
                                                >
                                                <small class="form-text text-muted">Accepted formats: JPG, PNG, GIF. Max size: 2MB</small>
                                                <span class="error-message">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-12 col-md-12">
                                        <ValidationProvider name="Package Details" mode="eager" rules="" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="packageImage">Package Image</label>
                                              <textarea
                                                  class="form-control"
                                                  :class="{'error-border': errors[0]}" id="packageImage"
                                                  v-model="PackageDetails"  name="package-details" placeholder="Package Details" rows="4"></textarea>
                                                <span class="error-message">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                    <!-- Current Image Preview -->
                                    <div class="col-12" v-if="currentImagePreview">
                                        <div class="form-group">
                                            <label>Current Image:</label>
                                            <div class="mt-2">
                                                <img
                                                    :src="getImagePreviewUrl()"
                                                    alt="Package Image Preview"
                                                    class="image-preview" />
                                            </div>
                                        </div>
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
import { bus } from "../../app";
import { Common } from "../../mixins/common";

export default {
    mixins: [Common],
    data() {
        return {
            title: '',
            PackageID: '',
            PackageName: '',
            PackageDetails: '',
            selectedServices: [],  // Stores selected services
            ElementsMeasurement: 0,  // Initializes ElementsMeasurement
            PackageImage: null,
            currentImagePreview: null,
            selectedPartners: [],  // Stores selected partners
            allServices: [],
            allPartners: [],
            buttonText: '',
            actionType: '',
            buttonShow: false,
            selectedPackage: null,
            Duration: ''
        }
    },
    mounted() {
        $('#add-edit-package').on('hidden.bs.modal', () => {
            this.$emit('changeStatus')
        });
        this.getSupportingData();

        bus.$on('add-edit-package', (row) => {
            if (row) {
                let instance = this;
                this.PackageID=  row.PackageID
                this.axiosGet('package/get-package-info/' + row.PackageID, function(response) {
                    var packageData = response.data;

                    // Set the form title and button text for edit mode
                    instance.title = 'Update Package';
                    instance.buttonText = "Update";

                    // Pre-fill the form with the existing package data
                    instance.PackageName = packageData.PackageName;
                    instance.PackageDetails = packageData.PackageDetails || '';  // Default to empty string if null
                    instance.Duration = packageData.Duration || '';  // Pre-select duration
                    instance.ElementsMeasurement = parseInt(packageData.ElementsMeasurement) || 0;  // Ensure it's a number

                    // Map selectedServiceIDs to their full objects

                    instance.selectedServices = packageData.selectedServiceIDs.map(serviceID => {
                        // Ensure serviceID is a number (if needed, cast to number)
                        return instance.allServices.find(service => Number(service.ServiceID) === Number(serviceID));
                    }).filter(service => service !== undefined); // Filter out undefined values (in case of unmatched IDs)

                    instance.selectedPartners = packageData.selectedPartnerIDs.map(partnerID => {
                        // Ensure partnerID is a number (if needed, cast to number)
                        return instance.allPartners.find(partner => Number(partner.PartnerID) === Number(partnerID));
                    }).filter(partner => partner !== undefined); // Filter out undefined values (in case of unmatched IDs)

                    // Handle image preview if available
                    if (packageData.PackageImage) {
                        instance.currentImagePreview = packageData.PackageImage;
                    } else {
                        instance.currentImagePreview = null;  // Reset if no image
                    }

                    // Show the form and set action to edit mode
                    instance.buttonShow = true;
                    instance.actionType = 'edit';
                }, function(error) {
                    instance.errorNoti(error.message || 'Error loading package data');
                });
            } else {
                this.title = 'Add Package';
                this.buttonText = "Add";
                this.PackageName = '';
                this.PackageDetails = '';
                this.selectedServices = [];
                this.selectedPartners = [];
                this.ElementsMeasurement = 0;
                this.PackageImage = null;
                this.currentImagePreview = null;
                this.Duration = '';
                this.buttonShow = true;
                this.actionType = 'add';
            }

            $("#add-edit-package").modal("toggle");
        })
    },
    methods: {
        closeModal() {
            $("#add-edit-package").modal("toggle");
        },

        getSupportingData() {
            this.axiosGet('package/get-supporting-data', (response) => {
                try {
                    this.allServices = response.services || [];
                    this.allPartners = response.partners || [];
                } catch (e) {
                    console.log(e);
                }
            }, function (error) {
                console.error('Error loading supporting data:', error);
            });
        },

        updateElementsMeasurement() {
            // Recalculate ElementsMeasurement based on selected services
            this.ElementsMeasurement = this.selectedServices.reduce((total, service) => total + parseFloat(service.ServiceAmount || 0), 0);
        },

        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2048000) {
                    this.errorNoti('File size must be less than 2MB');
                    this.$refs.imageInput.value = '';
                    return;
                }

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    this.errorNoti('Only JPG, PNG, GIF files are allowed');
                    this.$refs.imageInput.value = '';
                    return;
                }

                this.PackageImage = file;

                // Create preview
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.currentImagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        getImagePreviewUrl() {
            if (this.currentImagePreview) {
                if (this.currentImagePreview.startsWith('data:')) {
                    return this.currentImagePreview;
                }
                return `${this.mainOrigin}public/storage/${this.currentImagePreview}`;
            }
            return '';
        },

        onSubmit() {
            this.$store.commit('submitButtonLoadingStatus', true);

            let url = '';
            if (this.actionType === 'add') {
                url = 'package/add';
            } else {
                url = 'package/update';
            }
            const serviceIDs = this.selectedServices.map(service => service.ServiceID);  // Extract only ServiceID
            const partnerIDs = this.selectedPartners.map(partner => partner.PartnerID);  // Extract only PartnerID
            console.log(serviceIDs)

            // Create FormData for file upload
            const formData = new FormData();
            formData.append('PackageID', this.PackageID);
            formData.append('PackageName', this.PackageName);
            formData.append('PackageDetails', this.PackageDetails || '');
            formData.append('ServiceID', JSON.stringify(serviceIDs));  // Send serviceIDs as JSON
            formData.append('PartnerID', JSON.stringify(partnerIDs))
            formData.append('ElementsMeasurement', this.ElementsMeasurement || '');
            formData.append('Duration', this.Duration || '');

            // Add PackageID for update
            if (this.actionType === 'edit' && this.selectedPackage) {
                formData.append('PackageID', this.selectedPackage.PackageID);
            }

            if (this.PackageImage) {
                formData.append('PackageImage', this.PackageImage);
            }

            this.axiosPost(url, formData, (response) => {
                this.successNoti(response.message);
                $("#add-edit-package").modal("toggle");
                bus.$emit('refresh-datatable');
                this.$store.commit('submitButtonLoadingStatus', false);
                this.resetForm();
            }, (error) => {
                this.errorNoti(error.message || 'Error saving package');
                this.$store.commit('submitButtonLoadingStatus', false);
            })
        },

        resetForm() {
            this.PackageName = '';
            this.PackageDetails = '';
            this.selectedServices = [];
            this.selectedPartners = [];
            this.ElementsMeasurement = 0;
            this.PackageImage = null;
            this.currentImagePreview = null;
            this.Duration = '';
        }
    }
}
</script>

<style scoped>
.image-preview {
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
