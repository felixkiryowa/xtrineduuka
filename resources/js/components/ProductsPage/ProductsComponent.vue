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
            <h5>Shop Products</h5>
          </div>

          <div class="card-body">
            <div>
              <button
                class="btn btn-success float-right"
                @click="creatingNewProduct"
              >
                Add Product <i class="fa fa-plus-circle" aria-hidden="true"></i>
              </button>
              <br />
              <br />
              <div class="row">
                <div class="col-md-9"></div>
                <div class="col-md-3">
                  <input
                    type="text"
                    v-model="search"
                    placeholder="Search Product"
                    @keyup="searchProduct"
                    class="form-control float-right"
                  />
                </div>
              </div>
              <br />
              <table
                class="table table-hover table-striped table-bordered table-condensed table-sm"
              >
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Quantity Brought</th>
                    <th>Buying Price</th>
                    <th>Selling Price</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(product, index) in products.data" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ product.item_name | upText }}</td>
                    <td>{{ product.quantity_brought | formatNumber }}</td>
                    <td>{{ product.buying_price | formatNumber }}</td>
                    <td>{{ product.selling_price | formatNumber }}</td>
                    <td>
                      <button
                        class="btn btn-primary btn-edit"
                        @click="editProductModal(product)"
                      >
                        Edit
                        <i class="fa fa-edit"></i>
                      </button>
                      |
                      <button
                        @click="addTransactionNodal(product)"
                        class="btn btn-success"
                      >
                        Add addTransaction
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div>
                <pagination
                  :data="products"
                  :limit="5"
                  class="float-right"
                  @pagination-change-page="getResults"
                >
                  <span slot="prev-nav">&lt; Previous</span>
                  <span slot="next-nav">Next &gt;</span>
                </pagination>
              </div>
            </div>
            <br />
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="addNewProductModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              {{ editMode ? "Update Product Item" : "Add New Product Item" }}
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
          <form @submit.prevent="editMode ? updateProduct() : createProduct()">
            <div class="modal-body">
              <div class="form-group">
                <input
                  v-model="form.item_name"
                  type="text"
                  name="item_name"
                  placeholder="Product Item Name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('item_name') }"
                />
                <has-error :form="form" field="item_name"></has-error>
              </div>

              <div class="form-group">
                <input
                  v-model="form.quantity_brought"
                  type="number"
                  min="1"
                  name="quantity_brought"
                  placeholder="Product Item Quantity"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('quantity_brought') }"
                />
                <has-error :form="form" field="quantity_brought"></has-error>
              </div>

              <div class="form-group">
                <input
                  v-model="form.buying_price"
                  type="number"
                  min="1"
                  name="quantity_bought"
                  placeholder="Buying Price"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('buying_price') }"
                />
                <has-error :form="form" field="buying_price"></has-error>
              </div>

              <div class="form-group">
                <input
                  v-model="form.selling_price"
                  type="number"
                  min="1"
                  name="selling_price"
                  placeholder="Selling Price"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('selling_price') }"
                />
                <has-error :form="form" field="selling_price"></has-error>
              </div>
            </div>
            <input type="hidden" v-model="form.id" name="id" />
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">
                Close
              </button>
              <button type="submit" class="btn btn-primary">
                {{ editMode ? "Update" : "Create" }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!--Add Transaction -->
    <div
      class="modal fade"
      id="addNewTransactionModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              Add Transaction For {{ transaction_title }}
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
          <form @submit.prevent="createTransaction">
            <div class="modal-body">
              <div class="form-group">
                <label for="amount">Quantity</label>
                <input
                  v-model="transaction.quantity"
                  type="number"
                  name="quantity"
                  min="1"
                  placeholder="Enter Quantity"
                  class="form-control"
                  :class="{ 'is-invalid': transaction.errors.has('quantity') }"
                />
                <has-error :form="transaction" field="quantity"></has-error>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">
                Close
              </button>
              <button type="submit" class="btn btn-primary">Add</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Add Transaction -->
  </div>
</template>
<script>
import Loading from "vue-loading-overlay";
export default {
  data() {
    return {
      editMode: false,
      fullPage: true,
      isLoading: true,
      products: {},
      search: "",
      transaction_title: "",
      form: new Form({
        item_name: "",
        quantity_brought: "",
        buying_price: "",
        selling_price: "",
        id: "",
      }),
      transaction: new Form({
        quantity: "",
        id: "",
      }),
    };
  },
  mounted() {},
  methods: {
    addTransactionNodal(product) {
      this.transaction.reset();
      this.transaction_title = product.item_name.toUpperCase();
      this.transaction.id = product.id;
      $("#addNewTransactionModal").modal("show");
    },
    createTransaction() {
      this.transaction
        .post("/create/transaction")
        .then((response) => {
          if (!response.data.success) {
            Fire.$emit("afterCreatingProduct");
            this.$Progress.finish();
            Swal.fire({
              text: response.data.message,
              icon: "error",
            });
            this.transaction.reset();
            $("#addNewTransactionModal").modal("hide");
          } else {
            Fire.$emit("afterCreatingProduct");
            this.$Progress.finish();
            Toast.fire({
              icon: "success",
              title: response.data.message,
            });
            this.transaction.reset();
            $("#addNewTransactionModal").modal("hide");
          }
        })
        .catch((error) => {
          this.$Progress.fail();
        });
    },
    getResults(page = 1) {
      axios.get("/get/products?page=" + page).then((response) => {
        this.products = response.data;
      });
    },
    searchProduct() {
      this.isLoading = true;
      axios
        .get("search/product?q=" + this.search)
        .then((result) => {
          this.products = result.data;
          this.isLoading = false;
        })
        .catch((error) => {
          this.isLoading = false;
        });
    },
    creatingNewProduct() {
      this.editMode = false;
      //RESET THE FORM FIELDS
      this.form.reset();
      $("#addNewProductModal").modal("show");
    },
    editProductModal(product) {
      this.editMode = true;
      //RESET THE FORM FIELDS
      this.form.reset();
      $("#addNewProductModal").modal("show");
      this.form.fill(product);
    },
    createProduct() {
      this.$Progress.start();
      this.form
        .post("/create/product")
        .then((response) => {
          Fire.$emit("afterCreatingProduct");
          this.$Progress.finish();
          Toast.fire({
            icon: "success",
            title: response.data.message,
          });
          $("#addNewProductModal").modal("hide");
        })
        .catch((error) => {
          this.$Progress.fail();
        });
    },

    updateProduct() {
      this.$Progress.start();

      this.form
        .post("/update/product")
        .then((response) => {
          Fire.$emit("afterCreatingProduct");
          this.$Progress.finish();
          Toast.fire({
            icon: "success",
            title: response.data.message,
          });
          $("#addNewProductModal").modal("hide");
        })
        .catch((error) => {
          this.$Progress.fail();
        });
    },

    loadProducts() {
      this.isLoading = true;
      axios
        .get("/get/products")
        .then((response) => {
          this.products = response.data;
          this.isLoading = false;
          this.$Progress.finish();
        })
        .catch((error) => {
          this.isLoading = false;
          this.$Progress.fail();
        });
    },
  },
  created() {
    this.$Progress.start();

    this.loadProducts();
    Fire.$on("afterCreatingProduct", () => {
      this.loadProducts();
    });
  },
  components: {
    Loading,
  },
};
</script>

