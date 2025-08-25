<template>
    <div>
        <div class="modal fade" id="add-edit-service" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title modal-title-font" id="exampleModalLabel">{{ title }}</div>
                    </div>

                    <ValidationObserver v-slot="{ handleSubmit }">
                        <form class="form-horizontal" id="form" @submit.prevent="handleSubmit(onSubmit)">
                            <div class="modal-body">
                                <div class="row">

                                    <!-- Service Name -->
                                    <div class="col-12 col-md-6">
                                        <ValidationProvider name="Service Name" mode="eager" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="serviceName">Service Name <span class="error">*</span></label>
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    :class="{'error-border': errors[0]}"
                                                    id="serviceName"
                                                    v-model="ServiceName"
                                                    name="service-name"
                                                    placeholder="Service Name"
                                                    autocomplete="off"
                                                >
                                                <span class="error-message">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                    <!-- Service Amount -->
                                    <div class="col-12 col-md-6">
                                        <ValidationProvider name="Service Amount" mode="eager" rules="required|numeric|min_value:0" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="serviceAmount">Service Amount <span class="error">*</span></label>
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    class="form-control"
                                                    :class="{'error-border': errors[0]}"
                                                    id="serviceAmount"
                                                    v-model="ServiceAmount"
                                                    name="service-amount"
                                                    placeholder="0.00"
                                                    autocomplete="off"
                                                >
                                                <span class="error-message">{{ errors[0] }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>

                                    <!-- Service Details -->
                                    <div class="col-12">
                                        <ValidationProvider name="Service Details" mode="eager" rules="" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="serviceDetails">Service Details</label>
                                                <textarea
                                                    class="form-control"
                                                    :class="{'error-border': errors[0]}"
                                                    id="serviceDetails"
                                                    v-model="ServiceDetails"
                                                    name="service-details"
                                                    placeholder="Service Details"
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
            ServiceName: '',
            ServiceAmount: '',
            ServiceDetails: '',
            buttonText: '',
            actionType: '',
            buttonShow: false,
            selectedService: null
        }
    },
    computed: {},
    mounted() {
        $('#add-edit-service').on('hidden.bs.modal', () => {
            this.$emit('changeStatus')
        });

        bus.$on('add-edit-service', (row) => {
            if (row) {
                // Edit mode
                let instance = this;
                this.axiosGet('service/get-service-info/' + row.ServiceID, function(response) {
                    var service = response.data;
                    instance.title = 'Update Service';
                    instance.buttonText = "Update";
                    instance.ServiceName = service.ServiceName;
                    instance.ServiceAmount = service.ServiceAmount;
                    instance.ServiceDetails = service.ServiceDetails || '';
                    instance.selectedService = service;

                    instance.buttonShow = true;
                    instance.actionType = 'edit';
                }, function(error) {
                    instance.errorNoti(error.message || 'Error loading service data');
                });
            } else {
                // Add mode
                this.title = 'Add Service';
                this.buttonText = "Add";
                this.ServiceName = '';
                this.ServiceAmount = '';
                this.ServiceDetails = '';
                this.selectedService = null;
                this.buttonShow = true;
                this.actionType = 'add';
            }

            $("#add-edit-service").modal("toggle");
        })
    },
    destroyed() {
        bus.$off('add-edit-service')
    },
    methods: {
        closeModal() {
            $("#add-edit-service").modal("toggle");
        },

        onSubmit() {
            this.$store.commit('submitButtonLoadingStatus', true);

            let url = '';
            if (this.actionType === 'add') {
                url = 'service/add';
            } else {
                url = 'service/update';
            }

            // Prepare data object
            const data = {
                ServiceName: this.ServiceName,
                ServiceAmount: parseFloat(this.ServiceAmount) || 0,
                ServiceDetails: this.ServiceDetails || ''
            };

            // Add ServiceID for update
            if (this.actionType === 'edit' && this.selectedService) {
                data.ServiceID = this.selectedService.ServiceID;
            }

            this.axiosPost(url, data, (response) => {
                this.successNoti(response.message);
                $("#add-edit-service").modal("toggle");
                bus.$emit('refresh-datatable');
                this.$store.commit('submitButtonLoadingStatus', false);
                this.resetForm();
            }, (error) => {
                this.errorNoti(error.message || 'Error saving service');
                this.$store.commit('submitButtonLoadingStatus', false);
            })
        },

        resetForm() {
            this.ServiceName = '';
            this.ServiceAmount = '';
            this.ServiceDetails = '';
            this.selectedService = null;
        }
    }
}
</script>

<style scoped>
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
