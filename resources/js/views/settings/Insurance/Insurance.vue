<template>
  <div class="container-fluid">
    <breadcrumb :options="['Insurance']">
      <button class="btn btn-primary" @click="addPartnerModal()">Add Insurance Company</button>
    </breadcrumb>

    <div class="row" style="padding:8px 0px;">
      <div class="col-md-4">
        <button type="button" class="btn btn-success btn-sm" @click="exportData">Export to Excel</button>
      </div>
    </div>

    <advanced-datatable :options="tableOptions">
      <template slot="logo" slot-scope="row">
        <img
          v-if="row.item.InsuranceLogo"
          :src="`${mainOrigin}public/storage/${row.item.InsuranceLogo}`"
          alt="insurance Logo"
          class="insurance-logo-table"
        />
        <span v-else class="text-muted">No Logo</span>
      </template>

      <template slot="action" slot-scope="row">
          <button style="font-size: 12px;width:65px;padding: 2px 0px"  v-if="isAdmin" type="button"  @click="addPartnerModal(row.item)" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button>
          <button style="font-size: 12px;width:65px;padding: 2px 0px"  v-if="isAdmin" type="button" @click="deletePartner(row.item.InsuranceID)" class="btn btn-danger btn-sm"><i class="mdi mdi-trash-can"></i> Delete</button>

      </template>
    </advanced-datatable>

    <add-edit-insurance @changeStatus="changeStatus" v-if="loading"/>
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
        source: 'insurance/list',  // Updated to grouped route
        search: true,
        slots: [2,3], // Logo and Action columns
        hideColumn: ['InsuranceID','InsuranceLogo'],
        slotsName: ['logo', 'action'],
        sortable: [1,2], // PartnerName, PartnerDetails columns
        pages: [20, 50, 100],
        addHeader: ['Logo', 'Action']
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

    addPartnerModal(row = '') {
      this.loading = true;
      setTimeout(() => {
        bus.$emit('add-edit-insurance', row);
      })
    },

    deletePartner(id) {
      if (!this.isAdmin) {
        this.errorNoti('Only admin users can delete partners');
        return;
      }

      this.deleteAlert(() => {
        this.axiosDelete('insurance/delete', id, (response) => {  // Updated to grouped route
          this.successNoti(response.message);
          bus.$emit('refresh-datatable');
        }, (error) => {
          this.errorNoti(error);
        })
      });
    },

    exportData() {
      bus.$emit('export-data', 'insurance-list-' + moment().format('YYYY-MM-DD'))
    },

    getLogoUrl(logoPath) {
      return `/storage/${logoPath}`;
    }
  }
}
</script>

<style scoped>
.insurance-logo-table {
  width: 100px;
  height: 40px;
  object-fit: cover;
  border-radius: 4px;
}
</style>
