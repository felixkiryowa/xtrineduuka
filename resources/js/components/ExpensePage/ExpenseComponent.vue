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
            <h5>Expenses</h5>
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
                  worksheet="EXPENSES"
                  title="EXPENSES REPORT"
                  name="expenses_report.xls"
                  :fetch="generateExpensesReport"
                >
                  Generate Report
                  <i class="fa fa-download" aria-hidden="true"></i>
                </export-excel>
              </div>
            </div>
            <br />
            <div>
              <button
                class="btn btn-success float-right"
                @click="addingExpenseModal"
              >
                Add Expense <i class="fa fa-plus-circle" aria-hidden="true"></i>
              </button>
            </div>
            <br />
            <br />
            <table
              class="table table-hover table-striped table-bordered table-condensed table-sm"
            >
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Amount</th>
                  <th>Description</th>
                  <th>Created On</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(expense, index) in expenses.data" :key="index">
                  <td>{{ index + 1 }}</td>
                  <td>{{ expense.amount | formatNumber }}</td>
                  <td>{{ expense.description }}</td>
                  <td>{{ expense.created_at | customDate }}</td>
                  <td>
                    <button
                      class="btn btn-primary btn-edit"
                      @click="editExpense(expense)"
                    >
                      Edit Expense
                      <i class="fa fa-edit"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
            <br />
            <br />
            <div>
              <pagination
                :data="expenses"
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

    <!--Add Expense -->
    <div
      class="modal fade"
      id="addNewExpenseModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              {{ editMode ? "Update Expense" : "Add Expense" }}
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="editMode ? updateExpense() : createExpense()">
            <div class="modal-body">
              <div class="form-group">
                <label for="amount">Amount</label>
                <input
                  v-model="expense.amount"
                  type="text"
                  name="amount"
                  placeholder="Enter Amount"
                  class="form-control"
                  :class="{ 'is-invalid': expense.errors.has('amount') }"
                />
                <has-error :form="expense" field="amount"></has-error>
              </div>
              <div class="form-group">
                <textarea
                  v-model="expense.description"
                  name="bio"
                  rows="3"
                  placeholder="Enter Expense Description"
                  class="form-control"
                  :class="{ 'is-invalid': expense.errors.has('description') }"
                ></textarea>
                <has-error :form="expense" field="description"></has-error>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">
                Close
              </button>
              <button type="submit" class="btn btn-primary">
                {{ editMode ? "Update" : "Add" }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!--Add Expense -->
  </div>
</template>

<script>
import Loading from "vue-loading-overlay";
import moment from "moment";
export default {
  data() {
    return {
      json_fields: {
        AMOUNT: "amount",
        DESCRIPTION: "description",
        "ADDED ON": {
          field: "created_at",
          callback: (value) => {
            return moment(value).format("MMMM Do YYYY");
          },
        },
      },
      json_meta: [
        [
          {
            key: "charset",
            value: "utf-8",
          },
        ],
      ],
      expenses: {},
      fullPage: true,
      isLoading: false,
      editMode: false,
      expense: new Form({
        amount: "",
        description: "",
        id: "",
      }),
      form: new Form({
        start_date: "",
        end_date: "",
      }),
    };
  },
  mounted() {},
  methods: {
    async generateExpensesReport() {
      this.$Progress.start();
      this.isLoading = true;
      const response = await this.form.post("/generate/expenses/report");
      let responseData = response.data;
      if (responseData.length === 0) {
        this.isLoading = false;
        Swal.fire({
          text: "No Expenses Found Between Different Dates",
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
      axios.get("/all/expenses?page=" + page).then((response) => {
        this.isLoading = false;
        this.expenses = response.data;
      });
    },
    loadExpenses() {
      this.isLoading = true;
      axios
        .get("/all/expenses")
        .then((response) => {
          this.$Progress.finish();
          this.expenses = response.data;
          this.isLoading = false;
        })
        .catch((error) => {
          this.$Progress.fail();
          this.isLoading = false;
        });
    },
    editExpense(expense) {
      this.editMode = true;
      this.expense.fill(expense);
      $("#addNewExpenseModal").modal("show");
    },
    addingExpenseModal() {
      this.editMode = false;
      this.expense.reset();
      $("#addNewExpenseModal").modal("show");
    },
    createExpense() {
      this.$Progress.start();
      this.expense
        .post("/create/expense")
        .then((response) => {
          Fire.$emit("afterCreatingExpense");
          this.$Progress.finish();
          Toast.fire({
            icon: "success",
            title: response.data.message,
          });
          $("#addNewExpenseModal").modal("hide");
        })
        .catch((error) => {
          this.$Progress.fail();
        });
    },
    updateExpense() {
      this.$Progress.start();
      this.expense
        .post("/update/expense")
        .then((response) => {
          Fire.$emit("afterCreatingExpense");
          this.$Progress.finish();
          Toast.fire({
            icon: "success",
            title: response.data.message,
          });
          $("#addNewExpenseModal").modal("hide");
        })
        .catch((error) => {
          this.$Progress.fail();
        });
    },
  },

  created() {
    this.$Progress.start();
    this.loadExpenses();
    Fire.$on("afterCreatingExpense", () => {
      this.loadExpenses();
    });
  },
  components: {
    Loading,
  },
};
</script>
