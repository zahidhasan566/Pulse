<template>
    <div class="container-fluid">
        <breadcrumb :options="['Services']">
            <button class="btn btn-primary" @click="addModal()">Add Banks</button>
        </breadcrumb>

        <div class="row" style="padding:8px 0px;">
            <div class="col-md-4">
                <button type="button" class="btn btn-success btn-sm" @click="exportData">Export to Excel</button>
            </div>
        </div>

        <advanced-datatable :options="tableOptions">

            <template slot="action" slot-scope="row">
                <button style="font-size: 12px;width:65px;padding: 2px 0px"  v-if="isAdmin" type="button"  @click="addModal(row.item)" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
                <button style="font-size: 12px;width:65px;padding: 2px 0px"  v-if="isAdmin" type="button" @click="deletePartner(row.item.BankID)" class="btn btn-danger btn-sm"><i class="mdi mdi-trash-can"></i> Delete</button>

            </template>
        </advanced-datatable>

        <add-edit-banks @changeStatus="changeStatus" v-if="loading"/>
    </div>
</template>

<script>
import { bus } from "../../../app";
import { Common } from "../../../mixins/common";
import moment from "moment";

export default {
    mixins: [Common],
    data() {
        return {
            tableOptions: {
                source: 'banks/list',  // Updated to grouped route
                search: true,
                slots: [4], // Logo and Action columns
                hideColumn: ['ServiceID'],
                slotsName: ['action'],
                sortable: [], // PartnerName, PartnerDetails columns
                pages: [20, 50, 100],
                addHeader: ['Action']
            },
            loading: false
        }
    },
    computed: {
        isAdmin() {
            // Adjust this based on how you check user role in your app
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
                bus.$emit('add-edit-banks', row);
            })
        },

        deletePartner(id) {
            if (!this.isAdmin) {
                this.errorNoti('Only admin users can delete partners');
                return;
            }

            this.deleteAlert(() => {
                this.axiosDelete('banks/delete', id, (response) => {  // Updated to grouped route
                    this.successNoti(response.message);
                    bus.$emit('refresh-datatable');
                }, (error) => {
                    this.errorNoti(error);
                })
            });
        },

        exportData() {
            bus.$emit('export-data', 'banks-list-' + moment().format('YYYY-MM-DD'))
        },
    }
}
</script>

<style scoped>
.partner-logo-table {
    width: 100px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
}
</style>
