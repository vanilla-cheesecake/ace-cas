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
                <h5 class="modal-title" id="exampleModalLabel">ENTER BILLS</h5>
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
                                <label for="bank_account_id">A/P ACCOUNT:</label>
                                <select class="form-control form-control-sm" id="bank_account_id"
                                    name="bank_account_id">
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

                            <!-- SELECT PAYEE TYPE -->
                            <div class="form-group">
                                <label for="payee_type">VENDOR:</label>
                                <select class="form-control form-control-sm col-md-5" id="payee_type" name="payee_type">
                                    <option value="select payee type">SELECT PAYEE TYPE</option>
                                    <option value="customers">Customer</option>
                                    <option value="vendors">Vendor</option>
                                    <option value="other_name">Other Names</option>
                                </select>
                            </div>

                            <!-- ADDRESS -->
                            <div class="form-group">
                                <label for="memo">ADDRESS:</label>
                                <textarea class="form-control" id="memo" name="memo" rows="2"
                                    placeholder="Address"></textarea>
                            </div>

                            <!-- SELECT TERMS TYPE -->

                            <div class="form-group">
                                <label for="payee_type">TERMS:</label>
                                <select class="form-control form-control-sm col-md-5" id="payee_type" name="payee_type">
                                    <option value="select payee type">SELECT PAYEE TYPE</option>
                                    <option value="customers">Customer</option>
                                    <option value="vendors">Vendor</option>
                                    <option value="other_name">Other Names</option>
                                </select>
                            </div>

                            <!-- MEMO -->

                            <div class="form-group">
                                <label for="memo">MEMO:</label>
                                <textarea class="form-control" id="memo" name="memo" rows="2"
                                    placeholder="Memo"></textarea>
                            </div>

                        </div>

                        <div class="col-md-3">
                            <!-- Set the retrieved COGS account types in your dropdown -->
                            <div class="form-group col-md-6">
                                <label for="ref_no">REF NO</label>
                                <input type="text" class="form-control form-control-sm" id="ref_no" name="ref_no"
                                    placeholder="Enter ref no">
                            </div>

                        </div>
                        <div class="col-md-3">
                            <!-- SELECT DATE -->
                            <div class="form-group">
                                <label for="write_check_date">BILL DUE</label>
                                <input type="date" class="form-control form-control-sm" id="write_check_date"
                                    name="write_check_date" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <!-- PAYEE ADDRESS -->
                            <div class="form-group">
                                <label for="write_check_date">DISCOUNT DATE</label>
                                <input type="date" class="form-control form-control-sm" id="write_check_date"
                                    name="write_check_date" value="<?php echo date('Y-m-d'); ?>">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="ref_no">AMOUNT</label>
                                <input type="text" class="form-control form-control-sm" style="font-size: 30px"
                                    id="ref_no" name="ref_no" readonly>
                            </div>

                        </div>

                    </div>
                    <div class="table-responsive-sm">
                        <table class="table table-sm" id="itemTable">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th>Memo</th>
                                    <th>Amount</th>
                                    <th style="width: 200px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemTableBody" class="table-group-divider table-divider-color">
                                <!-- Existing rows or dynamically added rows will be appended here -->
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success" id="addItemBtn">Add Account</button>
                    </div>
                    <br><br><br>
                    <div class="row">

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

    </div>
</div>
</div>

<script>
    $(document).ready(function () {

        document.getElementById('tax_withheld_percentage').addEventListener('change', function () {
            var selectedOption = this.options[this.selectedIndex];
            var accountId = selectedOption.getAttribute('data-account-id');
            document.getElementById('tax_withheld_account_id').value = accountId;
        });

        // Populate dropdowns with accounts from PHP
        const accounts = <?php echo json_encode($accounts); ?>;
        let accountDropdownOptions = '';
        $.each(accounts, function (index, account) {
            accountDropdownOptions += `<option value="${account.id}">${account.account_code}-${account.description}</option>`;
        });


        // Add a new row to the table
        function addRow() {
            const newRow = `
            <tr>
                <td><select class="form-control form-control-sm account-dropdown" name="account_id[]">${accountDropdownOptions}</select></td>
                <td><input type="text" class="form-control form-control-sm memo" name="memo[]" placeholder="Enter memo"></td>
                <td><input type="text" class="form-control form-control-sm amount" name="amount[]" placeholder="Enter amount"></td>
                <td><button type="button" class="btn btn-sm btn-danger removeRow"><i class="fas fa-trash"></i></button></td>
            </tr>`;
            $('#itemTableBody').append(newRow);
        }

        // Function to calculate net amount
        // Function to calculate net amount and total discount amount
        function calculateNetAmount() {
            let totalAmount = 0;
            let totalDiscountAmount = 0;
            let totalNetAmount = 0;
            let totalVat = 0;
            let totalTaxableAmount = 0;
            let totalAmountDue = 0;

            $('.amount').each(function () {
                const amount = parseFloat($(this).val()) || 0;
                totalAmount += amount;
                const discountPercentage = parseFloat($(this).closest('tr').find('.discount_percentage').val()) || 0;
                const vatPercentage = parseFloat($(this).closest('tr').find('.vat_percentage').val()) || 0;

                const discountAmount = (discountPercentage / 100) * amount;
                $(this).closest('tr').find('.discount-amount').val(discountAmount.toFixed(4)); // Update discount amount field
                totalDiscountAmount += discountAmount;

                const netAmountBeforeVAT = amount - discountAmount;
                $(this).closest('tr').find('.net-amount-before-vat').val(netAmountBeforeVAT.toFixed(4)); // Update net amount before VAT field
                totalNetAmount += netAmountBeforeVAT;

                const vatPercentageAmount = vatPercentage / 100;
                const netAmount = netAmountBeforeVAT / (1 + vatPercentageAmount);
                $(this).closest('tr').find('.net-amount').val(netAmount.toFixed(4)); // Update net amount field

                const vatAmount = netAmountBeforeVAT - netAmount;
                $(this).closest('tr').find('.input-vat').val(vatAmount.toFixed(4)); // Update VAT amount field
                totalVat += vatAmount;

                totalTaxableAmount += netAmount;
            });

            // Update total fields
            $("#gross_amount").val(totalAmount.toFixed(4));
            $("#discount_amount").val(totalDiscountAmount.toFixed(4));
            $("#net_amount_due").val(totalNetAmount.toFixed(4));
            $("#vat_percentage_amount").val(totalVat.toFixed(4));
            $("#net_of_vat").val(totalTaxableAmount.toFixed(4));

            const taxWithheldPercentage = parseFloat($("#tax_withheld_percentage").val()) || 0;
            const taxWithheldAmount = (taxWithheldPercentage / 100) * totalTaxableAmount;
            $("#tax_withheld_amount").val(taxWithheldAmount.toFixed(4));

            totalAmountDue = totalNetAmount - taxWithheldAmount;
            $("#total_amount_due").val(totalAmountDue.toFixed(4));
        }

        // Event listener for tax withheld percentage change
        $('#tax_withheld_percentage').on('change', function () {
            calculateNetAmount();
        });

        // Event listener for amount input
        $('#itemTableBody').on('input', '.amount', function () {
            calculateNetAmount();
        });

        // Event listener for discount or VAT change
        $('#itemTableBody').on('change', '.discount_percentage, .vat_percentage', function () {
            calculateNetAmount();
        });

        // Event listeners
        $('#addItemBtn').click(addRow);

        $(document).on('click', '.removeRow', function () {
            $(this).closest('tr').remove();
            calculateNetAmount();
        });

        // Gather table items and submit form
        function gatherTableItems() {
            const items = [];
            $('#itemTableBody tr').each(function (index) {
                const item = {
                    account_id: $(this).find('.account-dropdown').val(),
                    memo: $(this).find('.memo').val(),
                    amount: $(this).find('.amount').val(),
                    discount_percentage: $(this).find('.discount_percentage').val(),
                    discount_amount: $(this).find('.discount-amount').val(),
                    net_amount_before_vat: $(this).find('.net-amount-before-vat').val(),
                    net_amount: $(this).find('.net-amount').val(),
                    vat_percentage: $(this).find('.vat_percentage').val(),
                    input_vat: $(this).find('.input-vat').val(),
                    discount_account_id: $(this).find('.discount_percentage option:selected').data('account-id'),
                    input_vat_account_id: $(this).find('.vat_percentage option:selected').data('account-id')
                };

                // For the first row only, set input_vat_account_id as input_vat_account_id of the first row
                if (index === 0) {
                    item.input_vat_account_id_first_row = item.input_vat_account_id;
                    item.discount_account_id_first_row = item.discount_account_id;
                }

                items.push(item);
            });
            return items;
        }
        $('#writeCheckForm').submit(function (event) {
            event.preventDefault();
            const items = gatherTableItems();
            $('#item_data').val(JSON.stringify(items));
            this.submit();
        });
    });
</script>