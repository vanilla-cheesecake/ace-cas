<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();
$accounts = ChartOfAccount::all();
$customers = Customer::all();
$products = Product::all();
$terms = Term::all();
$locations = Location::all();
$payment_methods = PaymentMethod::all();

$page = 'sales_invoice'; // Set the variable corresponding to the current page
?>

<?php require 'views/templates/header.php' ?>
<?php require 'views/templates/sidebar.php' ?>
<div class="main">
    <?php require 'views/templates/navbar.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Sales</strong> Invoice</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_payment_method') ?>
                    <?php displayFlashMessage('delete_payment_method') ?>
                    <?php displayFlashMessage('update_payment_method') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <form id="writeCheckForm" action="api/invoice_controller.php?action=add" method="POST">
                                <input type="hidden" name="action" id="modalAction" value="add" />
                                <input type="hidden" name="id" id="itemId" value="" />
                                <input type="hidden" name="item_data" id="item_data" />

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="invoice_number">Invoice #</label>
                                            <input type="text" class="form-control form-control-sm" id="invoice_number"
                                                name="invoice_number" placeholder="Enter invoice #">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="invoice_date">Invoice Date</label>
                                            <input type="date" class="form-control form-control-sm" id="invoice_date"
                                                name="invoice_date">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="invoice_date">Invoice Due Date</label>
                                            <input type="date" class="form-control form-control-sm" id="invoice_date"
                                                name="invoice_date">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="invoice_date">Account</label>
                                            <select class="form-control form-control-sm" id="customer_name"
                                                name="customer_name">
                                                <option value="">Select Account</option>
                                                <?php foreach ($accounts as $account): ?>
                                                    <option value="<?= $account->id ?>"><?= $account->account_code ?> -
                                                        <?= $account->description ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br><br><br>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="invoice_number">Customer</label>
                                            <select class="form-control form-control-sm" id="customer_name"
                                                name="customer_name">
                                                <option value="">Select Customer</option>
                                                <?php foreach ($customers as $customer): ?>
                                                    <option value="<?= $customer->id ?>"><?= $customer->customer_name ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="tin">TIN</label>
                                            <input type="text" class="form-control form-control-sm" id="tin" name="tin">
                                        </div>

                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="invoice_date">Billing Address</label>
                                            <input type="text" class="form-control form-control-sm" id="invoice_date"
                                                name="invoice_date">
                                        </div>
                                        <div class="form-group">
                                            <label for="tin">Email</label>
                                            <input type="text" class="form-control form-control-sm" id="tin" name="tin">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="invoice_date">Shipping Address</label>
                                            <input type="text" class="form-control form-control-sm" id="invoice_date"
                                                name="invoice_date">
                                        </div>
                                        <div class="form-group">
                                            <label for="tin">Business Style</label>
                                            <input type="text" class="form-control form-control-sm" id="tin" name="tin">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">

                                        </div>
                                    </div>
                                </div>

                                <br><br><br>

                                <br><br>
                                <div class="table-responsive-sm">
                                    <table class="table table-sm" id="itemTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 200px;">ITEM</th>
                                                <th>Description</th>
                                                <th>QTY</th>
                                                <th>UOM</th>
                                                <th>RATE</th>
                                                <th>Amount</th>
                                                <th>ACTION</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemTableBody" class="table-group-divider table-divider-color">
                                            <!-- Existing rows or dynamically added rows will be appended here -->
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-success" id="addItemBtn">Add Item</button>
                                </div>

                                <br><br><br>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="invoice_number">Payment Method</label>
                                            <input type="text" class="form-control form-control-sm" id="invoice_number"
                                                name="invoice_number" placeholder="Enter invoice #">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="invoice_date">Location</label>
                                            <input type="date" class="form-control form-control-sm" id="invoice_date"
                                                name="invoice_date">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="invoice_date">Invoice Due Date</label>
                                            <input type="date" class="form-control form-control-sm" id="invoice_date"
                                                name="invoice_date">
                                        </div>
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <div class="summary-details">
                                        <div class="container">

                                            <!-- GROSS AMOUNT -->
                                            <div class="row">
                                                <div class="col-md-4 d-inline-block text-right">
                                                    <label>Gross Amount:</label>
                                                </div>

                                                <div class="col-md-4 d-inline-block">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="gross_amount" id="gross_amount" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- DISCOUNT -->
                                            <div class="row">
                                                <div class="col-md-4 d-inline-block text-right">
                                                    <label for="discount_percentage">Discount</label>
                                                </div>

                                                <div class="col-md-4 d-inline-block">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="discount_amount" id="discount_amount" readonly>
                                                    </div>
                                                    <input type="text" class="form-control" name="discount_account_id"
                                                        id="discount_account_id" hidden>
                                                </div>
                                            </div>
                                            <!-- NET AMOUND DUE -->
                                            <div class="row">
                                                <div class="col-md-4 d-inline-block text-right">
                                                    <label>Net Amount:</label>
                                                </div>

                                                <div class="col-md-4 d-inline-block">
                                                    <div class="input-group input-group-sm ">
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="net_amount_due" id="net_amount_due" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- VAT PERCENTAGE -->
                                            <div class="row">
                                                <div class="col-md-4 d-inline-block text-right">
                                                    <label for="vat_percentage">Input Vat:</label>
                                                </div>

                                                <div class="col-md-4 d-inline-block">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="vat_percentage_amount" id="vat_percentage_amount"
                                                            readonly>
                                                    </div>
                                                    <input type="text" class="form-control" name="vat_account_id"
                                                        id="vat_account_id" hidden>
                                                </div>
                                            </div>
                                            <!-- NET OF VAT -->
                                            <div class="row">
                                                <div class="col-md-4 d-inline-block text-right">
                                                    <label>Taxable Amount</label>
                                                </div>

                                                <div class="col-md-4 d-inline-block">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="net_of_vat" id="net_of_vat" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- TAX WITHHELD -->
                                            <div class="row" id="taxWithheldRow">
                                                <div class="col-md-4 d-inline-block text-right">
                                                    <label>Tax Withheld (%):</label>
                                                </div>
                                                <div class="col-md-4 d-inline-block">
                                                    <select class="form-control form-control-sm"
                                                        id="tax_withheld_percentage" name="tax_withheld_percentage"
                                                        required>
                                                        <option value="">-- Select Tax Withheld --</option>
                                                        <?php foreach ($wtaxes as $wtax): ?>
                                                            <option value="<?= $wtax->wtax_rate ?>"
                                                                data-account-id="<?= $wtax->wtax_account_id ?>">
                                                                <?= $wtax->wtax_name ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 d-inline-block">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="tax_withheld_amount" id="tax_withheld_amount"
                                                            readonly>
                                                    </div>
                                                    <input type="hidden" class="form-control"
                                                        name="tax_withheld_account_id" id="tax_withheld_account_id">
                                                </div>
                                            </div>
                                            <br>
                                            <!-- GROSS AMOUNT -->
                                            <div class="row" style="font-size: 20px">
                                                <div class="col-md-6 d-inline-block text-right">
                                                    <label>Total Amount Due:</label>
                                                </div>
                                                <div class="col-md-6 d-inline-block">
                                                    <div class="input-group input-group-sm">

                                                        <input type="text" class="form-control form-control-sm"
                                                            name="total_amount_due" id="total_amount_due" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-md-10 d-inline-block">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </main>




    <?php require 'views/templates/footer.php' ?>