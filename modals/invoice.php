<style>
    /* Customize modal width */
    .modal-custom {
        max-width: 100%;
        width: 100%;
        /* Adjust this value as needed */
    }

    label {
        font-size: 11px;
    }
</style>

<!-- modal_template.html -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-custom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <img src="./photos/logo.jpg" alt="AdminLTE Logo" class="brand-image image-fluid"
                    style="opacity: .8 width: 50px; height: 50px">
                <h5 class="modal-title" id="exampleModalLabel">SALES INVOICE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="writeCheckForm" action="api/write_check_controller.php?action=add" method="POST">
                    <input type="hidden" name="action" id="modalAction" value="add" />
                    <input type="hidden" name="id" id="itemId" value="" />
                    <input type="hidden" name="item_data" id="item_data" />
                    <div class="row">
                        <div class="col-md-3">
                            <!-- SELECT CUSTOMER NAME -->
                            <div class="form-group">
                                <label for="customer_name">CUSTOMER NAME</label>
                                <select class="form-control form-control-sm" id="customer_name"
                                    name="customer_name">
                                    <option value="">Select Account</option>
                                    <?php foreach ($accounts as $account): ?>
                                        <?php if ($account->account_type == 'Bank'): ?>
                                            <option value="<?= $account->id ?>"><?= $account->account_code ?> -
                                                <?= $account->description ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- SELECT  BILLING ADDRESS-->
                            <div class="form-group col-md-12">
                                <label for="billing_address">BILLING ADDRESS</label>
                                <input type="text" class="form-control form-control-sm" id="billing_address" name="billing_address" placeholder="Enter billing address">
                            </div>
                            <!-- SELECT ACCOUNT -->
                            <div class="form-group col-md-12">
                                <label for="account">ACCOUNT <span class="text-muted"
                                        id="payee_type_display"></span></label>
                                <select class="form-control form-control-sm" id="account" name="account">
                                    <option value="">SELECT ACCOUNT</option>
                                    <!-- Payee options will be populated dynamically based on the payee type selection -->
                                </select>
                            </div>
                            <!-- ENTER  TIN:-->
                            <div class="form-group col-md-12">
                                <label for="tin">TIN:</label>
                                <input type="text" class="form-control form-control-sm" id="tin" name="tin" placeholder="Enter TIN #">
                            </div>
                            <!-- ENTER EMAIL-->
                            <div class="form-group col-md-12">
                                <label for="email">EMAIL</label>
                                <input type="text" class="form-control form-control-sm" id="email" name="email" placeholder="Enter email">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <!-- Enter Invoice # -->
                            <div class="form-group col-md-12">
                                <label for="invoice_number">INVOICE #</label>
                                <input type="text" class="form-control form-control-sm" id="invoice_number" name="invoice_number" placeholder="Enter invoice #">
                            </div>
                            <!-- SELECT INVOICE DATE -->
                            <div class="form-group col-md-12">
                                <label for="invoice_date">INVOICE DATE</label>
                                <input type="date" class="form-control form-control-sm" id="invoice_date"
                                    name="invoice_date" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <!-- SELECT INVOICE DUE DATE -->
                            <div class="form-group col-md-12">
                                <label for="invoice_due_date">INVOICE DUE DATE</label>
                                <input type="date" class="form-control form-control-sm" id="invoice_due_date"
                                    name="invoice_due_date" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <!-- Enter BUSINESS STYLE -->
                            <div class="form-group col-md-12">
                                <label for="business_style">BUSINESS STYLE</label>
                                <input type="text" class="form-control form-control-sm" id="business_style" name="business_style" placeholder="Enter business style">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <!-- SELECT ACCOUNT -->
                            <div class="form-group">
                                <label for="account">SELECT ACCOUNT</label>
                                <select class="form-control form-control-sm" id="account"
                                    name="account">
                                    <option value="">Select Account</option>
                                    <?php foreach ($accounts as $account): ?>
                                        <?php if ($account->account_type == 'Bank'): ?>
                                            <option value="<?= $account->id ?>"><?= $account->account_code ?> -
                                                <?= $account->description ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- PAYMENT METHOD -->
                            <div class="form-group">
                                <label for="payment_method">PAYMENT METHOD</label>
                                <select class="form-control form-control-sm" id="payment_method"
                                    name="payment_method">
                                    <option value="">Payment Method</option>
                                    <?php foreach ($accounts as $account): ?>
                                        <?php if ($account->account_type == 'Bank'): ?>
                                            <option value="<?= $account->id ?>"><?= $account->account_code ?> -
                                                <?= $account->description ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- PAYEE ADDRESS -->
                            <div class="form-group">
                                <label for="payee_address">ADDRESS</label>
                                <input type="text" class="form-control form-control-sm" id="payee_address"
                                    name="payee_address">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <!-- ENTER TERMS -->
                            <div class="form-group col-md-12">
                                <label for="terms">ENTER TERMS</label>
                                <input type="text" class="form-control form-control-sm" id="terms" name="terms" placeholder="Enter terms">
                            </div>
                            <!-- ENTER LOCATION -->
                            <div class="form-group col-md-12">
                                <label for="location">LOCATION</label>
                                <input type="text" class="form-control form-control-sm" id="location" name="location" placeholder="Enter Location">
                            </div>
                            <!-- ENTER MEMO -->
                            <div class="form-group">
                                <label for="memo">Enter Memo:</label>
                                <textarea class="form-control" id="memo" name="memo" rows="2"
                                    placeholder="Enter memo"></textarea>
                            </div>
                        </div>
                    </div>
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
                                            <input type="text" class="form-control form-control-sm" name="gross_amount"
                                                id="gross_amount" readonly>
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
                                                name="vat_percentage_amount" id="vat_percentage_amount" readonly>
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
                                            <input type="text" class="form-control form-control-sm" name="net_of_vat"
                                                id="net_of_vat" readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- TAX WITHHELD -->
                                <div class="row" id="taxWithheldRow">
                                    <div class="col-md-4 d-inline-block text-right">
                                        <label>Tax Withheld (%):</label>
                                    </div>
                                    <div class="col-md-4 d-inline-block">
                                        <select class="form-control form-control-sm" id="tax_withheld_percentage"
                                            name="tax_withheld_percentage" required>
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
                                                name="tax_withheld_amount" id="tax_withheld_amount" readonly>
                                        </div>
                                        <input type="hidden" class="form-control" name="tax_withheld_account_id"
                                            id="tax_withheld_account_id">
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

        </div>
        <script>
        document.getElementById('addItemBtn').addEventListener('click', function() {
            // Create a new row
            var newRow = document.createElement('tr');

            // Define the HTML content of the new row
            newRow.innerHTML = `
                <td><input type="text" class="form-control" placeholder="ITEM"></td>
                <td><input type="text" class="form-control" placeholder="Description"></td>
                <td><input type="number" class="form-control" placeholder="QTY"></td>
                <td><input type="text" class="form-control" placeholder="UOM"></td>
                <td><input type="number" class="form-control" placeholder="RATE"></td>
                <td><input type="number" class="form-control" placeholder="Amount" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-btn">Remove</button></td>
            `;

            // Append the new row to the table body
            document.getElementById('itemTableBody').appendChild(newRow);

            // Add event listener to the remove button
            newRow.querySelector('.remove-btn').addEventListener('click', function() {
                newRow.remove();
            });
        });
    </script>

    </div>
</div>
</div>