<template>
  <div class="container-fluid">
    <div class="row mt-5">
      <div class="col-md-12">
        <div class="vld-parent">
          <loading
            :active.sync="isLoading"
            :is-full-page="fullPage"
            color="#669900"
            loader="spinner"
          ></loading>
        </div>
        <div class="card">
          <div class="card-header">
            <h5>Transactions</h5>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="">Start Date</label>
                  <input
                    type="date"
                    placeholder="Start Date"
                    :class="{ 'is-invalid': form.errors.has('start_date') }"
                    class="form-control"
                    v-model="form.start_date"
                  />
                  <has-error :form="form" field="start_date"></has-error>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="">End Date</label>
                  <input
                    type="date"
                    placeholder="End Date"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('end_date') }"
                    v-model="form.end_date"
                  />
                  <has-error :form="form" field="end_date"></has-error>
                </div>
              </div>
              <div class="col-md-3">
                <export-excel
                  class="btn btn-success generate-btn"
                  :fields="json_fields"
                  worksheet="TRANSACTIONS"
                  title="TRANSACTIONS REPORT"
                  name="transactions_report.xls"
                  :fetch="generateTransactionsReport"
                >
                  Generate Report
                  <i class="fa fa-download" aria-hidden="true"></i>
                </export-excel>
              </div>
            </div>

            <table
              class="table table-hover table-striped table-bordered table-condensed table-sm"
            >
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Item Name</th>
                  <th>Quantity Sold</th>
                  <th>Amount</th>
                  <th>Profit</th>
                  <th>Created At</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(transaction, index) in transactions.data"
                  :key="index"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ transaction.product.item_name | upText }}</td>
                  <td>{{ transaction.quantity_sold | formatNumber }}</td>
                  <td>{{ transaction.amount | formatNumber }}</td>
                  <td>{{ transaction.profit | formatNumber }}</td>
                  <td>{{ transaction.createdat | customDate }}</td>
                </tr>
              </tbody>
            </table>
            <br />
            <br />
            <div>
              <pagination
                :data="transactions"
                :limit="5"
                class="float-right"
                @pagination-change-page="getResults"
              >
                <span slot="prev-nav">&lt; Previous</span>
                <span slot="next-nav">Next &gt;</span>
              </pagination>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Loading from "vue-loading-overlay";
export default {
  data() {
    return {
      json_fields: {
        "ITEM NAME": "item_name",
        "BUYING PRICE":"buying_price",
        "SELLING PRICE":"selling_price",
        "TOTAL QUANTITY SOLD" : "total_quantity_sold",
        "TOTAL BUYING PRICE": "total_buying_price",
        "TOTAL SELLING PRICE": "total_amount_sold",
        "TOTAL PROFITS": "total_profits",
      },
      json_meta: [
        [
          {
            key: "charset",
            value: "utf-8",
          },
        ],
      ],
      transactions: {},
      isLoading: false,
      fullPage: true,
      form: new Form({
        start_date: "",
        end_date: "",
      }),
    };
  },
  mounted() {},
  methods: {
    async generateTransactionsReport() {
      this.$Progress.start();
      this.isLoading = true;
      const response = await this.form.post("/generate/transactions/report");
      let responseData = response.data;
      if (responseData.length === 0) {
        this.isLoading = false;
        Swal.fire({
          text: "No Transaction Found Between Different Dates",
          icon: "error",
        });
      } else {
        this.$Progress.finish();
        this.isLoading = false;
        console.log("MY DATA DATA", responseData);
        return responseData;
      }
    },
    getResults(page = 1) {
      this.isLoading = true;
      axios.get("/all/transactions?page=" + page).then((response) => {
        this.isLoading = false;
        this.transactions = response.data;
        console.log(response.data);
      });
    },
    loadTransactions() {
      this.isLoading = true;
      this.$Progress.start();
      axios
        .get("/all/transactions")
        .then((response) => {
          this.$Progress.finish();
          this.transactions = response.data;
          this.isLoading = false;
        })
        .catch((error) => {
          this.$Progress.fail();
          this.isLoading = false;
        });
    },
  },
  created() {
    this.$Progress.start();
    this.loadTransactions();
  },
  components: {
    Loading,
  },
};
</script>
