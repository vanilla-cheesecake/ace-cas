<?php
//Guard
include '_guards.php';

Guard::adminOnly();
$accounts = ChartOfAccount::all();
$vendors = Vendor::all();
$customers = Customer::all();
$other_names = OtherNameList::all();
$cost_centers = CostCenter::all();
$discounts = Discount::all();
$wtaxes = WithholdingTax::all();
$sales_taxes = SalesTax::all();
$input_vats = InputVat::all();
$checks = WriteCheck::all();

$page = 'write_check'; // Set the variable corresponding to the current page
?>




<?php include 'views/templates/header.php' ?>
<?php include 'views/templates/sidebar.php' ?>
<div class="main">
    <?php include 'views/templates/navbar.php' ?>
    <!-- Content Wrapper. Contains page content -->
    <main class="content">
        <div class="container-fluid p-0">
            <h1 class="h3 mb-3"><strong>Write Check</strong> Expense</h1>
            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_write_check') ?>
                    <?php displayFlashMessage('delete_check') ?>
                    <?php displayFlashMessage('view_check') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <style>
                                .loading-overlay {
                                    display: none;
                                    position: fixed;
                                    top: 0;
                                    left: 0;
                                    width: 100%;
                                    height: 100%;
                                    background: rgba(255, 255, 255, 0.8);
                                    z-index: 9999;
                                    justify-content: center;
                                    align-items: center;
                                }

                                .loading-overlay .spinner {
                                    border: 16px solid #54BD69;
                                    border-top: 16px solid #fff;
                                    border-radius: 50%;
                                    width: 120px;
                                    height: 120px;
                                    animation: spin 2s linear infinite;
                                }

                                @keyframes spin {
                                    0% {
                                        transform: rotate(0deg);
                                    }

                                    100% {
                                        transform: rotate(360deg);
                                    }
                                }
                            </style>
                            <?php if (isset($_GET['id'])) {
                                $id = $_GET['id'];
                                $check = WriteCheck::find($id);
                                if ($check) { ?>
                                    <form id="writeCheckForm" action="api/write_check_controller.php?action=add" method="POST">
                                        <input type="hidden" name="action" id="modalAction" value="add" />
                                        <input type="hidden" name="id" id="itemId" value="" />
                                        <input type="hidden" name="item_data" id="item_data" />
                                        <div class="row">
                                            <div class="col-md-3">
                                                <!-- SELECT BANK ACCOUNT -->
                                                <div class="form-group">
                                                    <label for="bank_account_id">BANK ACCOUNT</label>
                                                    <select class="form-control form-control-sm" id="bank_account_id"
                                                        name="bank_account_id" required>
                                                        <option value=""><?= $check->account_name ?></option>
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
                                                    <select class="form-control form-control-sm col-md-5" id="payee_type"
                                                        name="payee_type">
                                                        <option value="<?= $check->payee_type ?>"><?= $check->payee_type ?>
                                                        </option>
                                                        <option value="customers">Customer</option>
                                                        <option value="vendors">Vendor</option>
                                                        <option value="other_name">Other Names</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <!-- Set the retrieved COGS account types in your dropdown -->
                                                <div class="form-group col-md-6">
                                                    <label for="ref_no">REF NO</label>
                                                    <input type="text" class="form-control form-control-sm" id="ref_no"
                                                        name="ref_no" placeholder="Enter ref no" value="<?= $check->ref_no ?>">
                                                </div>
                                                <!-- SELECT PAYEE -->
                                                <div class="form-group col-md-12">
                                                    <label for="payee_id">PAYEE <span class="text-muted"
                                                            id="payee_type_display"></span></label>
                                                    <select class="form-control form-control-sm" id="payee_id" name="payee_id"
                                                        required>
                                                        <option value="<?= $check->payee_id ?>"><?= $check->payee_name ?>
                                                        </option>
                                                        <!-- Payee options will be populated dynamically based on the payee type selection -->
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <!-- SELECT DATE -->
                                                <div class="form-group">
                                                    <label for="write_check_date">CHECK DATE</label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        id="write_check_date" name="write_check_date"
                                                        value="<?= $check->write_check_date ?>">
                                                </div>
                                                <!-- PAYEE ADDRESS -->
                                                <div class="form-group">
                                                    <label for="payee_address">ADDRESS</label>
                                                    <input type="text" class="form-control form-control-sm" id="payee_address"
                                                        name="payee_address" value="<?= $check->payee_address ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group col-md-6">
                                                    <label for="check_no">CHECK NO</label>
                                                    <input type="text" class="form-control form-control-sm" id="check_no"
                                                        name="check_no" value="<?= $check->check_no ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <br><br><br>
                                        <div class="table-responsive-sm">
                                            <table class="table table-sm" id="itemTable">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 200px;">ACCOUNT</th>
                                                        <th>DESC</th>
                                                        <th>GROSS</th>
                                                        <th>DSC TYPE</th>
                                                        <th>DSC</th>
                                                        <th>NET</th>
                                                        <th>TAX AMT</th>
                                                        <th>TAX TYPE</th>
                                                        <th>VAT</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="itemTableBody" class="table-group-divider table-divider-color">
                                                    <!-- Existing rows or dynamically added rows will be appended here -->
                                                    <?php

                                                    if ($check) {
                                                        foreach ($check->details as $detail) {
                                                            $row = '
                                                                <tr>
                                                                    <td><select class="form-control form-control-sm account-dropdown" name="account_id[]"><option value="' . $detail['account_id'] . '">' . $detail['account_name_details'] . '</option></select></td>
                                                                    <td><input type="text" class="form-control form-control-sm memo" name="memo[]" placeholder="Enter memo" value="' . $detail['details_memo'] . '"></td>
                                                                    <td><input type="text" class="form-control form-control-sm amount" name="amount[]" placeholder="Enter amount" value="' . $detail['amount'] . '"></td>
                                                                    <td><select class="form-control form-control form-control-sm discount_percentage" name="discount_percentage[]"></select></td>
                                                                    <td><input type="text" class="form-control form-control-sm discount-amount" name="discount_amount[]" placeholder="" value="' . $detail['discount_amount'] . '"></td>
                                                                    <td><input type="text" class="form-control form-control-sm net-amount-before-vat" name="net_amount_before_vat[]" placeholder="" value="' . $detail['net_amount'] . '"></td>
                                                                    <td><input type="text" class="form-control form-control-sm net-amount" name="net_amount[]" placeholder="" value="' . $detail['taxable_amount'] . '"></td>
                                                                    <td><select class="form-control form-control-sm vat_percentage" name="vat_percentage[]"></select></td>
                                                                    <td><input type="text" class="form-control form-control-sm input-vat" name="vat_amount[]" placeholder="" value="' . $detail['input_vat'] . '"></td>
                                                                    <td><button type="button" class="btn btn-sm btn-danger removeRow"><i class="fas fa-trash"></i></button></td>
                                                                </tr>
                                                            ';
                                                            echo $row;
                                                        }
                                                    }
                                                    ?>
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
                                                        placeholder="Enter memo"
                                                        value="<?= $check->memo ?>"><?= $check->memo ?></textarea>
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
                                                                    name="gross_amount" id="gross_amount"
                                                                    value="<?= $check->gross_amount ?>">
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
                                                                    name="discount_amount" id="discount_amount"
                                                                    value="<?= $check->discount_amount ?>">
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
                                                                    name="net_amount_due" id="net_amount_due"
                                                                    value="<?= $check->net_amount_due ?>">
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
                                                                    value="<?= $check->vat_percentage_amount ?>">
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
                                                                    name="net_of_vat" id="net_of_vat"
                                                                    value="<?= $check->net_of_vat ?>">
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
                                                                <option value=""></option>
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
                                                                    value="<?= $check->tax_withheld_amount ?>">
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
                                                                    name="total_amount_due" id="total_amount_due"
                                                                    value="<?= $check->total_amount_due ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Submit Button -->
                                        <div class="row">
                                            <div class="col-md-10 d-inline-block">
                                                <button class="btn btn-success">Save</button>
                                                <button class="btn btn-secondary">Update</button>
                                                <button class="btn btn-danger">Delete</button>
                                                <a class="btn btn-primary" href="#"
                                                    onclick="printPurchaseOrder(<?= $check->id ?>); return false;">
                                                    <i class="fas fa-print"></i> Print
                                                </a>
                                                <iframe id="printFrame" style="display:none;"></iframe>
                                            </div>
                                        </div>
                                    </form>
                                    <div id="loadingOverlay" class="loading-overlay">
                                        <div class="spinner"></div>
                                    </div>
                                    <?php
                                    // Check found, you can now display the details
                                } else {
                                    // Handle the case where the check is not found
                                    echo "Check not found.";
                                    exit;
                                }
                            } else {
                                // Handle the case where the ID is not provided
                                echo "No ID provided.";
                                exit;
                            }
                            ?>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </main>


    <?php require 'views/templates/footer.php' ?>

    <script>
        function showLoadingOverlay() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        function hideLoadingOverlay() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        function printPurchaseOrder(poId) {
            showLoadingOverlay();

            const printFrame = document.getElementById('printFrame');
            const printContentUrl = `print_check?action=print&id=${poId}`;

            printFrame.src = printContentUrl;

            printFrame.onload = function () {
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
                hideLoadingOverlay();
            };
        }
    </script>


    <script>
        $(document).ready(function () {
            // Data from server (ideally, this should be fetched via an API)
            var customers = <?= json_encode($customers) ?>;
            var vendors = <?= json_encode($vendors) ?>;
            var otherNames = <?= json_encode($other_names) ?>;

            const payeeTypeSelect = $('#payee_type');
            const payeeDropdown = $('#payee_id');
            const payeeAddressInput = $('#payee_address');

            // Event handlers
            payeeTypeSelect.change(handlePayeeTypeChange);
            payeeDropdown.change(handlePayeeSelectionChange);

            function handlePayeeTypeChange() {
                const payeeType = payeeTypeSelect.val();
                resetPayeeDropdown();
                populatePayeeDropdown(payeeType);
            }

            function handlePayeeSelectionChange() {
                const payeeId = payeeDropdown.val();
                const payeeType = payeeTypeSelect.val();
                updatePayeeAddress(payeeType, payeeId);
            }

            function resetPayeeDropdown() {
                payeeDropdown.empty().append('<option value="">Select Payee</option>');
                payeeAddressInput.val('');
            }

            function populatePayeeDropdown(payeeType) {
                const data = getDataByPayeeType(payeeType);
                data.forEach(item => {
                    payeeDropdown.append(`<option value="${item.id}">${item.name}</option>`);
                });
            }

            function updatePayeeAddress(payeeType, payeeId) {
                const data = getDataByPayeeType(payeeType);
                const selectedPayee = data.find(item => item.id == payeeId);
                const address = selectedPayee ? selectedPayee.address : '';
                payeeAddressInput.val(address);
            }

            function getDataByPayeeType(payeeType) {
                switch (payeeType) {
                    case 'customers':
                        return mapData(customers, 'customer_name', 'customer_address');
                    case 'vendors':
                        return mapData(vendors, 'vendor_name', 'vendor_address');
                    case 'other_name':
                        return mapData(otherNames, 'other_name', 'other_name_address');
                    default:
                        return [];
                }
            }

            function mapData(data, nameKey, addressKey) {
                return data.map(item => ({
                    id: item.id,
                    name: item[nameKey],
                    address: item[addressKey]
                }));
            }

            // Function to open modal
            function openModal() {
                $('#myModal').modal('show');
            }

            // Expose openModal to the global scope
            window.openModal = openModal;
        });
    </script>

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


            // Populate dropdowns with accounts from PHP
            const discountOptions = <?php echo json_encode($discounts); ?>;
            let discountDropdownOptions = '';
            discountOptions.forEach(function (discount) {
                discountDropdownOptions += `<option value="${discount.discount_rate}" data-account-id="${discount.discount_account_id}">${discount.discount_name}</option>`;
            });

            const vatOptions = <?php echo json_encode($input_vats); ?>;
            let vatDropdownOptions = '';
            vatOptions.forEach(function (vat) {
                vatDropdownOptions += `<option value="${vat.input_vat_rate}" data-account-id="${vat.input_vat_account_id}">${vat.description} - ${vat.input_vat_name}</option>`;
            });

            // Add a new row to the table
            function addRow() {
                const newRow = `
            <tr>
                <td><select class="form-control form-control-sm account-dropdown" name="account_id[]">${accountDropdownOptions}</select></td>
                <td><input type="text" class="form-control form-control-sm memo" name="memo[]" placeholder="Enter memo"></td>
                <td><input type="text" class="form-control form-control-sm amount" name="amount[]" placeholder="Enter amount"></td>
                <td><select class="form-control form-control form-control-sm discount_percentage" name="discount_percentage[]">${discountDropdownOptions}</select></td>
                <td><input type="text" class="form-control form-control-sm discount-amount" name="discount_amount[]" placeholder="" readonly></td>
                <td><input type="text" class="form-control form-control-sm net-amount-before-vat" name="net_amount_before_vat[]" placeholder="" readonly></td>
                <td><input type="text" class="form-control form-control-sm net-amount" name="net_amount[]" placeholder="" readonly></td>
                <td><select class="form-control form-control-sm vat_percentage" name="vat_percentage[]">${vatDropdownOptions}</select></td>
                <td><input type="text" class="form-control form-control-sm input-vat" name="vat_amount[]" placeholder="" readonly></td>
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