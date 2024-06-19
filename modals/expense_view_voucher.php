<style>
    /* Customize modal width */
    .modal-custom {
        max-width: 90%;
        /* Adjust this value as needed */
    }
</style>

<!-- modal_template.html -->
<div class="modal" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-custom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <img src="./photos/logo.jpg" alt="AdminLTE Logo" class="brand-image image-fluid"
                    style="opacity: .8 width: 50px; height: 50px">
                <h5 class="modal-title" id="exampleModalLabel">Check Voucher - Expense</h5>
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
                            <!-- SELECT BANK ACCOUNT -->
                            <div class="form-group">
                                <label for="bank_account_id">BANK ACCOUNT</label>
                                <select class="form-control" id="bank_account_id" name="bank_account_id">
                                    <option value="">-- Select Account --</option>
                                    <?php foreach ($accounts as $account): ?>
                                        <?php if ($account->account_type == 'Bank'): ?>
                                            <option value="<?= $account->id ?>"><?= $account->account_code ?> -
                                                <?= $account->description ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- SELECT PAYEE TYPE -->
                            <div class="form-group">
                                <label for="payee_type">PAYEE TYPE</label>
                                <select class="form-control" id="payee_type" name="payee_type">
                                    <option value="select payee type">-- SELECT PAYEE TYPE --</option>
                                    <option value="customers">Customer</option>
                                    <option value="vendors">Vendor</option>
                                    <option value="other_name">Other Names</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <!-- Set the retrieved COGS account types in your dropdown -->
                            <div class="form-group">
                                <label for="ref_no">REF NO</label>
                                <input type="text" class="form-control" id="ref_no" name="ref_no"
                                    placeholder="Enter ref no">
                            </div>
                            <!-- SELECT PAYEE -->
                            <div class="form-group">
                                <label for="payee_id">PAYEE <span class="text-muted"
                                        id="payee_type_display"></span></label>
                                <select class="form-control" id="payee_id" name="payee_id">
                                    <option value="">-- Select Payee --</option>
                                    <!-- Payee options will be populated dynamically based on the payee type selection -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <!-- SELECT DATE -->
                            <div class="form-group">
                                <label for="write_check_date">Date</label>
                                <input type="date" class="form-control" id="write_check_date" name="write_check_date"
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <!-- PAYEE ADDRESS -->
                            <div class="form-group">
                                <label for="payee_address">ADDRESS</label>
                                <input type="text" class="form-control" id="payee_address" name="payee_address">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="itemTable">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th>Memo</th>
                                    <th>Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="itemTableBody">
                                <!-- Existing rows or dynamically added rows will be appended here -->
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success" id="addItemBtn">Add Account</button>
                    </div>
                    <br><br><br>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- ENTER MEMO -->
                            <div class="form-group">
                                <label for="memo">Enter Memo:</label>
                                <textarea class="form-control" id="memo" name="memo" rows="2"
                                    placeholder="Enter memo"></textarea>
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
                                    </div>
                                    <div class="col-md-4 d-inline-block">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="gross_amount"
                                                id="gross_amount" readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- DISCOUNT -->
                                <div class="row">
                                    <div class="col-md-4 d-inline-block text-right">
                                        <label for="discount_percentage">DISCOUNT (%):</label>
                                    </div>
                                    <div class="col-md-4 d-inline-block">
                                        <select class="form-control" id="discount_percentage" name="discount_percentage"
                                            required>
                                            <?php foreach ($discounts as $discount): ?>
                                                <option value="<?= $discount->discount_rate ?>"
                                                    data-account-id="<?= $discount->discount_account_id ?>">
                                                    <?= $discount->discount_name ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 d-inline-block">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="discount_amount"
                                                id="discount_amount" readonly>
                                        </div>
                                        <input type="text" class="form-control" name="discount_account_id"
                                            id="discount_account_id" readonly>
                                    </div>
                                </div>
                                <!-- NET AMOUND DUE -->
                                <div class="row">
                                    <div class="col-md-4 d-inline-block text-right">
                                        <label>Net Amount Due:</label>
                                    </div>
                                    <div class="col-md-4 d-inline-block">

                                    </div>
                                    <div class="col-md-4 d-inline-block">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="net_amount_due"
                                                id="net_amount_due" readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- VAT PERCENTAGE -->
                                <div class="row">
                                    <div class="col-md-4 d-inline-block text-right">
                                        <label for="vat_percentage">INPUT VAT (%):</label>
                                    </div>
                                    <div class="col-md-4 d-inline-block">
                                        <select class="form-control" id="vat_percentage" name="vat_percentage" required>
                                            <?php foreach ($input_vats as $input_vat): ?>
                                                <option value="<?= $input_vat->input_vat_rate ?>"
                                                    data-account-id="<?= $input_vat->input_vat_account_id ?>">
                                                    <?= $input_vat->input_vat_name ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 d-inline-block">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="vat_percentage_amount"
                                                id="vat_percentage_amount" readonly>
                                        </div>
                                        <input type="text" class="form-control" name="vat_account_id"
                                            id="vat_account_id" readonly>
                                    </div>
                                </div>
                                <!-- NET OF VAT -->
                                <div class="row">
                                    <div class="col-md-4 d-inline-block text-right">
                                        <label>Net of VAT:</label>
                                    </div>
                                    <div class="col-md-4 d-inline-block">

                                    </div>
                                    <div class="col-md-4 d-inline-block">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="net_of_vat" id="net_of_vat"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- TAX WITHHELD -->
                                <div class="row" id="taxWithheldRow">
                                    <div class="col-md-4 d-inline-block text-right">
                                        <label>Tax Withheld (%):</label>
                                    </div>
                                    <div class="col-md-4 d-inline-block">
                                        <select class="form-control" id="tax_withheld_percentage"
                                            name="tax_withheld_percentage" required>
                                            <?php foreach ($wtaxes as $wtax): ?>
                                                <option value="<?= $wtax->wtax_rate ?>"
                                                    data-account-id="<?= $wtax->wtax_account_id ?>">
                                                    <?= $wtax->wtax_name ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 d-inline-block">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="tax_withheld_amount"
                                                id="tax_withheld_amount" readonly>
                                        </div>
                                        <input type="text" class="form-control" name="tax_withheld_account_id"
                                            id="tax_withheld_account_id" readonly>
                                    </div>
                                </div>
                                <br>
                                <!-- GROSS AMOUNT -->
                                <div class="row" style="font-size: 20px">
                                    <div class="col-md-6 d-inline-block text-right">
                                        <label>Total Amount Due:</label>
                                    </div>
                                    <div class="col-md-6 d-inline-block">
                                        <div class="input-group">

                                            <input type="text" class="form-control" name="total_amount_due"
                                                id="total_amount_due" readonly>
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

    </div>
</div>