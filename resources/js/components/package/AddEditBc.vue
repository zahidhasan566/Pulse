\<template>
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

                                    <!-- PackageMaster Name -->
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

                                    <!-- Service -->
                                    <div class="col-12 col-md-6">
                                        <ValidationProvider name="Service" mode="eager" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label>Service <span class="error">*</span></label>
                                                <select name="serviceID" class="form-control" v-model="ServiceID"
                                                        :class="{'error-border': errors[0]}"
                                                        style="margin: 0">
                                                    <option value="">Select Service</option>
                                                    <option :value="singleData.ServiceID"
                                                            v-for="(singleData, index) in allServices"
                                                            :key="index">{{ singleData.ServiceName }}
                                                    </option>
                                                </select>
                                                <span class="error-message">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                    <!-- Partner -->
                                    <div class="col-12 col-md-6">
                                        <ValidationProvider name="Partner" mode="eager" rules="" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label>Partner</label>
                                                <select name="partnerID" class="form-control" v-model="PartnerID"
                                                        :class="{'error-border': errors[0]}"
                                                        style="margin: 0">
                                                    <option value="">Select Partner</option>
                                                    <option :value="singleData.PartnerID"
                                                            v-for="(singleData, index) in allPartners"
                                                            :key="index">{{ singleData.PartnerName }}
                                                    </option>
                                                </select>
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
                                                >
                                                <span class="error-message">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                    <!-- PackageMaster Image -->
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

                                    <!-- PackageMaster Details -->
                                    <div class="col-12">
                                        <ValidationProvider name="Package Details" mode="eager" rules="" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="packageDetails">Package Details</label>
                                                <textarea
                                                    class="form-control"
                                                    :class="{'error-border': errors[0]}"
                                                    id="packageDetails"
                                                    v-model="PackageDetails"
                                                    name="package-details"
                                                    placeholder="Package Details"
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
import { bus } from "../../app";
import { Common } from "../../mixins/common";

export default {
    mixins: [Common],
    data() {
        return {
            title: '',
            PackageName: '',
            PackageDetails: '',
            ServiceID: '',
            ElementsMeasurement: '',
            PackageImage: null,
            currentImagePreview: null,
            PartnerID: '',
            buttonText: '',
            actionType: '',
            buttonShow: false,
            selectedPackage: null,
            allServices: [],
            allPartners: []
        }
    },
    computed: {},
    mounted() {
        $('#add-edit-package').on('hidden.bs.modal', () => {
            this.$emit('changeStatus')
        });
        this.getSupportingData();

        bus.$on('add-edit-package', (row) => {
            if (row) {
                // Edit mode
                let instance = this;
                this.axiosGet('package/get-package-info/' + row.PackageID, function(response) {
                    var packageData = response.data;
                    instance.title = 'Update PackageMaster';
                    instance.buttonText = "Update";
                    instance.PackageName = packageData.PackageName;
                    instance.PackageDetails = packageData.PackageDetails || '';
                    instance.ServiceID = packageData.ServiceID;
                    instance.ElementsMeasurement = parseInt(packageData.ElementsMeasurement);
                    instance.PartnerID = packageData.PartnerID;
                    instance.selectedPackage = packageData;

                    // Set current image preview if exists
                    if (packageData.PackageImage) {
                        instance.currentImagePreview = packageData.PackageImage;
                    } else {
                        instance.currentImagePreview = null;
                    }

                    instance.buttonShow = true;
                    instance.actionType = 'edit';
                }, function(error) {
                    instance.errorNoti(error.message || 'Error loading package data');
                });
            } else {
                // Add mode
                this.title = 'Add PackageMaster';
                this.buttonText = "Add";
                this.PackageName = '';
                this.PackageDetails = '';
                this.ServiceID = '';
                this.ElementsMeasurement = '';
                this.PackageImage = null;
                this.currentImagePreview = null;
                this.PartnerID = '';
                this.selectedPackage = null;
                this.buttonShow = true;
                this.actionType = 'add';
            }

            $("#add-edit-package").modal("toggle");
        })
    },
    destroyed() {
        bus.$off('add-edit-package')
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
                    console.log('Services:', this.allServices);
                    console.log('Partners:', this.allPartners);
                } catch (e) {
                    console.log(e);
                }
            }, function (error) {
                console.error('Error loading supporting data:', error);
            });
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
                // If it starts with data:, it's a base64 preview from file upload
                if (this.currentImagePreview.startsWith('data:')) {
                    return this.currentImagePreview;
                }
                // Otherwise it's an existing image path
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

            // Extract only the IDs from the selectedServices and selectedPartners
            const serviceIDs = this.selectedServices.map(service => service.ServiceID);
            const partnerIDs = this.selectedPartners.map(partner => partner.PartnerID);

            // Create FormData for file upload
            const formData = new FormData();
            formData.append('PackageName', this.PackageName);
            formData.append('PackageDetails', this.PackageDetails || '');
            formData.append('ServiceID', JSON.stringify(serviceIDs));  // Send only the Service IDs
            formData.append('PartnerID', JSON.stringify(partnerIDs));  // Send only the Partner IDs
            formData.append('ElementsMeasurement', this.ElementsMeasurement || '');
            formData.append('Duration', this.Duration || '');

            // Add PackageID for update
            if (this.actionType === 'edit' && this.selectedPackage) {
                formData.append('PackageID', this.selectedPackage.PackageID);
            }

            if (this.PackageImage) {
                formData.append('PackageImage', this.PackageImage);
            }

            // Make the API request
            this.axiosPost(url, formData, (response) => {
                this.successNoti(response.message);
                $("#add-edit-package").modal("toggle");
                bus.$emit('refresh-datatable');
                this.$store.commit('submitButtonLoadingStatus', false);
                this.resetForm();
            }, (error) => {
                this.errorNoti(error.message || 'Error saving package');
                this.$store.commit('submitButtonLoadingStatus', false);
            });
        },


        resetForm() {
            this.PackageName = '';
            this.PackageDetails = '';
            this.ServiceID = '';
            this.ElementsMeasurement = '';
            this.PackageImage = null;
            this.currentImagePreview = null;
            this.PartnerID = '';
            this.selectedPackage = null;

            // Reset file input
            if (this.$refs.imageInput) {
                this.$refs.imageInput.value = '';
            }
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
