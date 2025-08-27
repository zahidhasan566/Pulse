<template>
    <div class="container-fluid">
        <breadcrumb :options="['Sales']">
            <button class="btn btn-primary" @click="navigateToAddPage">Add Sale</button>
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
                <router-link class="btn btn-success" style="font-size: 12px;width:65px;padding: 2px 0px" target='_blank' :to="{path: `${baseUrl}`+'sales-add?saleID='+(row.item.SaleID)}"><i class="fa fa-edit">Edit</i></router-link>
<!--                <button style="font-size: 12px;width:65px;padding: 2px 0px" v-if="isAdmin" type="button" @click="navigateToEditPage(row.item.SaleID)" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>-->
                <button style="font-size: 12px;width:65px;padding: 2px 0px" v-if="isAdmin" type="button" @click="deleteData(row.item.SaleID)" class="btn btn-danger btn-sm"><i class="mdi mdi-trash-can"></i> Delete</button>
            </template>
        </advanced-datatable>

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
                source: 'sale/list',  // Adjust to your API for sales
                search: true,
                slots: [7,8], // Columns for Action and other data
                hideColumn: ['SaleID','ProfilePicture'],
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
        },
        me() {
            return this.$store.state.me
        }
    },
    methods: {
        navigateToAddPage() {
            let row = '';
            bus.$emit('add-edit-sale',row='');
            this.$router.push({ name: 'SalesInvoice' });
        },

        navigateToEditPage(saleID) {
            this.$router.push({ name: "SalesInvoice", params: { saleID: saleID } });
        },
        // navigateToEditPage(saleID) {
        //     console.log(saleID,'saleID')
        //     this.$router.push({ name: 'SalesInvoice', params: { saleId: saleID } });
        // },

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
            bus.$emit('export-data', 'sales-list-' + moment().format('YYYY-MM-DD'));
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
