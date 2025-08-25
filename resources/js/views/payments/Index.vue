<template>
    <div class="container-fluid">
        <breadcrumb :options="['Payments Transaction']">
        </breadcrumb>

        <div class="row" style="padding:8px 0px;">
            <div class="col-md-4">
                <button type="button" class="btn btn-success btn-sm" @click="exportData">Export to Excel</button>
            </div>
        </div>

        <advanced-datatable :options="tableOptions">
            <template slot="image" slot-scope="row">
                <img
                    v-if="row.item.ProfilePicture"
                    :src="`${mainOrigin}public/storage/${row.item.ProfilePicture}`"
                    alt="ProfilePicture"
                    class="picture-table"
                />
                <span v-else class="text-muted">No Picture</span>
            </template>
            <template slot="action" slot-scope="row">
                <button
                    :class="row.item.PaymentID ? 'btn btn-info' : 'btn btn-success'"
                    @click="addModal(row.item)">
                    {{ row.item.PaymentID ? 'Update' : 'Add' }} Payment
                </button>
            </template>
        </advanced-datatable>
        <add-edit-payments @changeStatus="changeStatus" v-if="loading"/>

    </div>
</template>

<script>
import { bus } from "../../app";
import { Common } from "../../mixins/common";
import moment from "moment";
import {baseurl} from "../../base_url";

export default {
    mixins: [Common],
    data() {
        return {
            tableOptions: {
                source: 'payments/list',  // Adjust to your API for sales
                search: true,
                slots: [8,9], // Columns for Action and other data
                hideColumn: ['PaymentID','ProfilePicture'],
                slotsName: ['image','action'],
                sortable: [1, 2], // Enable sorting for some columns like Date, Amount
                pages: [20, 50, 100],
                addHeader: ['image', 'Action'] // Customize as needed
            },
            loading: false,
            baseUrl: baseurl,
        }
    },
    computed: {
        isAdmin() {
            return this.$store.state.me && this.$store.state.me.RoleID === 'admin';
        }
    },
    mounted() {
        bus.$off('changeStatus', function() {
            this.changeStatus()
        })
    },
    methods: {
        changeStatus() {
            this.loading = false;
        },

        addModal(row = '') {
            this.loading = true;
            setTimeout(() => {
                bus.$emit('add-edit-payments', row);
            })
        },

        deleteData(id) {
            if (!this.isAdmin) {
                this.errorNoti('Only admin users can delete sales');
                return;
            }

            this.deleteAlert(() => {
                this.axiosDelete('sale/delete', id, (response) => {
                    this.successNoti(response.message);
                    bus.$emit('refresh-datatable');
                }, (error) => {
                    this.errorNoti(error);
                });
            });
        },

        exportData() {
            bus.$emit('export-data', 'payments-list-' + moment().format('YYYY-MM-DD'));
        }
    }
};
</script>

<style scoped>
.picture-table {
    width: 100px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
}
</style>
