<?php
//Guard
require_once '_guards.php';
Guard::adminOnly();
$accounts = ChartOfAccount::all();
$vendors = Vendor::all();
$products = Product::all();
$discounts = Discount::all();
$input_vats = InputVat::all();
$purchase_orders = PurchaseOrder::all();
?>


<?php require 'views/templates/header.php' ?>
<?php require 'views/templates/sidebar.php' ?>
<div class="main">
    <?php require 'views/templates/navbar.php' ?>


    <!-- Content Wrapper. Contains page content -->
    <main class="content">
        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>View</strong> Purchase Order</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_purchase_order') ?>
                    <?php displayFlashMessage('delete_payment_method') ?>
                    <?php displayFlashMessage('update_payment_method') ?>
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
                            <?php
                            if (isset($_GET['id'])) {
                                $id = $_GET['id'];
                                $purchase_order = PurchaseOrder::find($id);
                                if ($purchase_order) { ?>
                                    <form id="writeCheckForm" action="api/purchase_order_controller.php?action=add"
                                        method="POST">
                                        <input type="hidden" name="action" id="modalAction" value="add" />
                                        <input type="hidden" name="id" id="itemId" value="" />
                                        <input type="hidden" name="item_data" id="item_data" />
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <!-- PURCHASE ORDER NO -->
                                                    <label for="po_no">PO NO:</label>
                                                    <input type="text" class="form-control form-control-sm" id="po_no"
                                                        name="po_no" placeholder="Enter po no"
                                                        value="<?= $purchase_order->po_no ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <!-- DATE -->
                                                    <label for="po_date">DATE</label>
                                                    <input type="date" class="form-control form-control-sm" id="po_date"
                                                        name="po_date"
                                                        value="<?= date('Y-m-d', strtotime($purchase_order->po_date)) ?>">
                                                    <!-- DELIVERY DATE -->
                                                    <label for="">DELIVERY DATE</label>
                                                    <input type="date" class="form-control form-control-sm" id="delivery_date"
                                                        name="delivery_date"
                                                        value="<?= date('Y-m-d', strtotime($purchase_order->delivery_date)) ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <!-- SELECT VENDOR -->
                                                <div class="form-group">
                                                    <label for="vendor_id">VENDOR</label>
                                                    <select class="form-control form-control-sm" id="vendor_id"
                                                        name="vendor_id">
                                                        <option value="<?= $purchase_order->vendor_name ?>">
                                                            <?= $purchase_order->vendor_name ?>
                                                        </option>
                                                        <?php foreach ($vendors as $vendor): ?>
                                                            <option value="<?= $vendor->id ?>"
                                                                data-address="<?= $vendor->vendor_address ?>"
                                                                data-terms="<?= $vendor->terms ?>"><?= $vendor->vendor_name ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="col-md-3">
                                                <!-- VENDOR ADDRESS -->
                                                <div class="form-group">
                                                    <label for="">ADDRESS</label>
                                                    <input type="text" class="form-control form-control-sm" id="vendor_address"
                                                        name="vendor_address" value="<?= $purchase_order->vendor_address ?>">
                                                </div>

                                            </div>
                                            <div class="col-md-3">
                                                <!-- TIN -->
                                                <div class="form-group">
                                                    <label for="">TIN</label>
                                                    <input type="text" class="form-control form-control-sm" id="tin" name="tin"
                                                        value="<?= $purchase_order->tin ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <!-- <label for="ref_no">TERMS</label>
                                <input type="text" class="form-control form-control-sm" id="ref_no" name="ref_no"
                                    placeholder="Enter ref no"> -->
                                                    <label for="">TERMS</label>
                                                    <select class="form-control form-control-sm" id="terms" name="terms">
                                                        <option value="<?= $purchase_order->terms ?>">
                                                            <?= $purchase_order->terms ?>
                                                        </option>
                                                        <option value="NET 7">NET 7</option>
                                                        <option value="NET 15">NET 15</option>
                                                        <option value="NET 30">NET 30</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="table-responsive-sm">
                                                <table class="table table-sm" id="itemTable">
                                                    <thead>
                                                        <tr>
                                                            <th>ITM</th>
                                                            <th>DESC</th>
                                                            <th>U/M</th>
                                                            <th>QTY</th>
                                                            <th>COST</th>
                                                            <th>AMT</th>
                                                            <th>DSC TYPE</th>
                                                            <th>DSC</th>
                                                            <th>NET</th>
                                                            <th>TAX AMT</th>
                                                            <th>TAX TYPE</th>
                                                            <th>VAT</th>
                                                            <th>ACT</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="itemTableBody" class="table-group-divider table-divider-color">
                                                        <!-- Existing rows or dynamically added rows will be appended here -->
                                                        <?php

                                                        // Assuming $purchaseOrder contains the retrieved purchase order details
                                                        // Assuming $purchaseOrder contains the retrieved purchase order details
                                                        if ($purchase_order) {
                                                            foreach ($purchase_order->details as $detail) {
                                                                $row = '
                                                                    <tr>
                                                                        <td><select class="form-control form-control-sm account-dropdown" name="item_id[]" onchange="populateFields(this)"><option value="' . $detail['item_name'] . '" data-account-id="${discount.discount_account_id}">' . $detail['item_name'] . '</option></select></td>
                                                                        <td><input type="text" class="form-control form-control-sm description-field" name="description[]" readonly value="' . $detail['purchase_description'] . '"></td>
                                                                        <td><input type="text" class="form-control form-control-sm uom" name="uom[]" readonly value="' . $detail['uom_name'] . '"></td>
                                                                        <td><input type="text" class="form-control form-control-sm quantity" name="quantity[]" placeholder="Qty" value="' . $detail['qty'] . '"></td>
                                                                        <td><input type="text" class="form-control form-control-sm cost" name="cost[]" placeholder="Enter Cost" value="' . $detail['cost'] . '"></td>
                                                                        <td><input type="text" class="form-control form-control-sm amount" name="amount[]" placeholder="Amount" readonly value="' . $detail['amount'] . '"></td>
                                                                        <td><select class="form-control form-control form-control-sm discount_percentage" name="discount_percentage[]"><option value="' . $detail['discount_rate'] . '" data-account-id="${discount.discount_account_id}">' . $detail['discount_name'] . '</option></select></td>
                                                                        <td><input type="text" class="form-control form-control-sm discount_amount" name="discount_amount[]" placeholder="" readonly value="' . $detail['discount'] . '"></td>
                                                                        <td><input type="text" class="form-control form-control-sm net_amount_before_input_vat" name="net_amount_before_input_vat[]" placeholder="" readonly value="' . $detail['net_amount'] . '"></td>
                                                                        <td><input type="text" class="form-control form-control-sm net_amount" name="net_amount[]" placeholder="" value="' . $detail['net_amount'] . '"></td>
                                                                        <td><select class="form-control form-control-sm input_vat_percentage" name="input_vat_percentage[]" ><option value="' . $detail['input_vat_rate'] . '" data-account-id="${discount.discount_account_id}">' . $detail['input_vat_name'] . '</option></select></td>
                                                                        <td><input type="text" class="form-control form-control-sm input_vat_amount" name="input_vat_amount[]" placeholder="" readonly value="' . $detail['input_vat'] . '"></td>
                                                                        <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="fas fa-trash"></i></button></td>
                                                                    </tr>';
                                                                echo $row;
                                                            }
                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>
                                                <button type="button" class="btn btn-success" id="addItemBtn">Add Item</button>
                                            </div>
                                            <br><br><br><br><br>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <!-- ENTER MEMO -->
                                                    <div class="form-group">
                                                        <label for="">Enter Memo:</label>
                                                        <textarea class="form-control" id="memo" name="memo" rows="2"
                                                            placeholder="Enter memo"><?= $purchase_order->memo ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <br><br>
                                            <div class="modal-footer">
                                                <div class="summary-details">
                                                    <div class="container">
                                                        <!-- GROSS AMOUNT -->
                                                        <div class="row">
                                                            <div class="col-md-4 d-inline-block text-right">
                                                                <label>Gross Amount:</label>
                                                            </div>

                                                            <div class="col-md-6 d-inline-block">
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control form-control-sm"
                                                                        name="gross_amount" id="gross_amount"
                                                                        value="<?= $purchase_order->gross_amount ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- DISCOUNT -->
                                                        <div class="row">
                                                            <div class="col-md-4 d-inline-block text-right">
                                                                <label for="discount_percentage">Discount:</label>
                                                            </div>

                                                            <div class="col-md-6 d-inline-block">
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control form-control-sm"
                                                                        name="discount_amount" id="discount_amount"
                                                                        value="<?= $purchase_order->discount_amount ?>"
                                                                        readonly>
                                                                </div>
                                                                <input type="text" class="form-control"
                                                                    name="discount_account_id" id="discount_account_id" hidden>
                                                            </div>
                                                        </div>
                                                        <!-- NET AMOUND DUE -->
                                                        <div class="row">
                                                            <div class="col-md-4 d-inline-block text-right">
                                                                <label>Net Amount:</label>
                                                            </div>

                                                            <div class="col-md-6 d-inline-block">
                                                                <div class="input-group input-group-sm ">
                                                                    <input type="text" class="form-control form-control-sm"
                                                                        name="net_amount_due" id="net_amount_due"
                                                                        value="<?= $purchase_order->net_amount_due ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- VAT PERCENTAGE -->
                                                        <div class="row">
                                                            <div class="col-md-4 d-inline-block text-right">
                                                                <label for="vat_percentage">Input Vat:</label>
                                                            </div>

                                                            <div class="col-md-6 d-inline-block">
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control form-control-sm"
                                                                        name="input_vat_amount" id="input_vat_amount"
                                                                        value="<?= $purchase_order->po_input_vat ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- VATATABLE -->
                                                        <div class="row">
                                                            <div class="col-md-4 d-inline-block text-right">
                                                                <label for="">Vatable 12%:</label>
                                                            </div>

                                                            <div class="col-md-6 d-inline-block">
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control form-control-sm"
                                                                        name="vatable_amount" id="vatable_amount"
                                                                        value="<?= $purchase_order->vatable_amount ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- VAT ZERO RATED -->
                                                        <div class="row">
                                                            <div class="col-md-4 d-inline-block text-right">
                                                                <label for="">Zero-rated:</label>
                                                            </div>

                                                            <div class="col-md-6 d-inline-block">
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control form-control-sm"
                                                                        name="zero_rated_amount" id="zero_rated_amount"
                                                                        value="<?= $purchase_order->zero_rated_amount ?>"
                                                                        readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- VAT EXEMPT -->
                                                        <div class="row">
                                                            <div class="col-md-4 d-inline-block text-right">
                                                                <label for="">Vat-Exempt:</label>
                                                            </div>

                                                            <div class="col-md-6 d-inline-block">
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control form-control-sm"
                                                                        name="vat_exempt_amount" id="vat_exempt_amount"
                                                                        value="<?= $purchase_order->vat_exempt ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <!-- TOTAL AMOUNT DUE -->
                                                        <div class="row" style="font-size: 20px">
                                                            <div class="col-md-6 d-inline-block text-right">
                                                                <label>Total Amount Due:</label>
                                                            </div>

                                                            <div class="col-md-6 d-inline-block">
                                                                <div class="input-group input-group-sm">
                                                                    <input type="text" class="form-control form-control-sm"
                                                                        name="total_amount_due" id="total_amount_due"
                                                                        value="<?= $purchase_order->total_amount ?>" readonly>
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
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                    <a class="text-success ml-2" href="#"
                                                        onclick="printPurchaseOrder(<?= $purchase_order->po_id ?>); return false;">
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
                                    echo "PO not found.";
                                    exit;
                                }
                            } else {
                                // Handle the case where the ID is not provided
                                echo "No ID provided.";
                                exit;
                            }


                            ?>


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
            const printContentUrl = `print_purchase_order?action=print&id=${poId}`;

            printFrame.src = printContentUrl;

            printFrame.onload = function () {
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
                hideLoadingOverlay();
            };
        }
    </script>




    <script>

        function openModal() {
            $('#myModal').modal('show');
        }



        $(document).ready(function () {
            $('#vendor_id').change(function () {
                var selectedVendor = $(this).find(':selected');
                var address = selectedVendor.data('address');
                var tin = selectedVendor.data('account');
                $('#vendor_address').val(address);
                $('#tin').val(tin);
            });

            $('#terms').change(function () {
                var terms = $(this).val();
                var deliveryDate = calculateDeliveryDate(terms);
                $('#delivery_date').val(deliveryDate);
            });

            function calculateDeliveryDate(terms) {
                var currentDate = new Date();
                var deliveryDate = new Date(currentDate);

                if (terms === 'Due on Receipt') {
                    // Delivery date is the same as the current date
                    return currentDate.toISOString().split('T')[0];
                } else {
                    var daysToAdd = parseInt(terms.replace('NET ', ''));
                    deliveryDate.setDate(deliveryDate.getDate() + daysToAdd);
                    return deliveryDate.toISOString().split('T')[0];
                }
            }

            // Populate dropdowns with accounts from PHP
            const products = <?php echo json_encode($products); ?>;
            let itemDropdownOptions = '';
            $.each(products, function (index, product) {
                itemDropdownOptions += `<option value="${product.id}" data-description="${product.purchase_description}" data-uom="${product.uom_id}">${product.item_name}</option>`;
            });

            // Populate dropdowns with accounts from PHP
            const discountOptions = <?php echo json_encode($discounts); ?>;
            let discountDropdownOptions = '';
            discountOptions.forEach(function (discount) {
                discountDropdownOptions += `<option value="${discount.discount_rate}" data-account-id="${discount.discount_account_id}">${discount.discount_name}</option>`;
            });

            const inputVatOption = <?php echo json_encode($input_vats); ?>;
            let inputVatDropdownOptions = '';
            inputVatOption.forEach(function (input_vat) {
                inputVatDropdownOptions += `<option value="${input_vat.input_vat_rate}" data-account-id="${input_vat.id}">${input_vat.input_vat_name}</option>`;
            });

            // Add a new row to the table
            function addRow() {
                const newRow = `
            <tr>
                <td><select class="form-control form-control-sm account-dropdown" name="item_id[]" onchange="populateFields(this)">${itemDropdownOptions}</select></td>
                <td><input type="text" class="form-control form-control-sm description-field" name="description[]" readonly></td>
                <td><input type="text" class="form-control form-control-sm uom" name="uom[]" readonly></td>
                <td><input type="text" class="form-control form-control-sm quantity" name="quantity[]" placeholder="Qty"></td>
                <td><input type="text" class="form-control form-control-sm cost" name="cost[]" placeholder="Enter Cost"></td>
                <td><input type="text" class="form-control form-control-sm amount" name="amount[]" placeholder="Amount" readonly></td>
                <td><select class="form-control form-control form-control-sm discount_percentage" name="discount_percentage[]">${discountDropdownOptions}</select></td>
                <td><input type="text" class="form-control form-control-sm discount_amount" name="discount_amount[]" placeholder="" readonly></td>
                <td><input type="text" class="form-control form-control-sm net_amount_before_input_vat" name="net_amount_before_input_vat[]" placeholder="" readonly></td>
                <td><input type="text" class="form-control form-control-sm net_amount" name="net_amount[]" placeholder=""></td>
                <td><select class="form-control form-control-sm input_vat_percentage" name="input_vat_percentage[]">${inputVatDropdownOptions}</select></td>
                <td><input type="text" class="form-control form-control-sm input_vat_amount" name="input_vat_amount[]" placeholder="" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="fas fa-trash"></i></button></td>
            </tr>`;
                $('#itemTableBody').append(newRow);

                // Add event listener to calculate all values
                $('.quantity, .cost, .discount_percentage, .input_vat_percentage, .input_vat_amount').on('input', function () {
                    calculateRowValues($(this).closest('tr'));
                    calculateTotalAmount();
                });

                // Calculate totals for existing rows and update totals fields
                $('tr').each(function () {
                    calculateRowValues($(this));
                    calculateTotalAmount();
                });
            }


            // Function to calculate amounts, discount amounts, and sales tax amounts
            // Function to calculate amounts, discount amounts, and sales tax amounts
            function calculateRowValues(row) {

                const quantity = parseFloat(row.find('.quantity').val()) || 0;
                const cost = parseFloat(row.find('.cost').val()) || 0;
                const discountPercentage = parseFloat(row.find('.discount_percentage').val()) || 0;
                const salesTaxPercentage = parseFloat(row.find('.input_vat_percentage').val()) || 0 || 0;

                const amount = quantity * cost;
                const discountAmount = (amount * discountPercentage) / 100;

                const netAmountBeforeTax = amount - discountAmount;

                const vatPercentageAmount = salesTaxPercentage / 100;
                const salesTaxAmount = (netAmountBeforeTax / (1 + salesTaxPercentage / 100)) * (salesTaxPercentage / 100);
                const netAmount = netAmountBeforeTax - salesTaxAmount;

                row.find('.amount').val(amount.toFixed(4));
                row.find('.discount_amount').val(discountAmount.toFixed(4));
                row.find('.net_amount_before_input_vat').val(netAmountBeforeTax.toFixed(4));
                row.find('.input_vat_amount').val(salesTaxAmount.toFixed(4));
                row.find('.net_amount').val(netAmount.toFixed(4));

            }

            // Function to calculate total amount
            function calculateTotalAmount() {
                let totalAmount = 0;
                let totalDiscountAmount = 0;
                let totalNetAmountBeforeTax = 0;
                let totalInputVatAmount = 0;

                let vatableAmount = 0;
                let zeroRatedAmount = 0;
                let vatExemptAmount = 0;

                let totalAmountDue = 0;


                $('.amount').each(function () {
                    const amount = parseFloat($(this).val()) || 0;
                    if (!isNaN(amount)) {
                        totalAmount += amount;
                    }
                });

                $('.discount_amount').each(function () {
                    const discount_amount = parseFloat($(this).val()) || 0;
                    if (!isNaN(discount_amount)) {
                        totalDiscountAmount += discount_amount;
                    }
                });

                $('.net_amount_before_input_vat').each(function () {
                    const net_amount_before_input_vat = parseFloat($(this).val()) || 0;
                    if (!isNaN(net_amount_before_input_vat)) {
                        totalNetAmountBeforeTax += net_amount_before_input_vat;
                    }
                });


                $('.input_vat_amount').each(function () {
                    const input_vat_amount = parseFloat($(this).val()) || 0;
                    if (!isNaN(input_vat_amount)) {
                        totalInputVatAmount += input_vat_amount;
                    }
                });


                $('.net_amount').each(function () {
                    const net_amount = parseFloat($(this).val());
                    const inputVatName = $(this).closest('tr').find('.input_vat_percentage option:selected').text();
                    if (!isNaN(net_amount)) {

                        if (inputVatName === '12%') {
                            vatableAmount += net_amount;
                            console.log("TOTAL 12%: ", vatableAmount); // Add this line for debugging

                        } else if (inputVatName === 'E') {
                            vatExemptAmount += net_amount;
                            console.log("TOTAL E: ", net_amount); // Add this line for debugging

                        } else if (inputVatName === 'Z') {
                            zeroRatedAmount += net_amount;
                            console.log("TOTAL Z: ", net_amount); // Add this line for debugging

                        }



                    }
                });


                totalAmountDue = totalInputVatAmount + vatableAmount + vatExemptAmount + zeroRatedAmount;

                $("#gross_amount").val(totalAmount.toFixed(4));
                $("#discount_amount").val(totalDiscountAmount.toFixed(4));
                $("#net_amount_due").val(totalNetAmountBeforeTax.toFixed(4));
                $("#input_vat_amount").val(totalInputVatAmount.toFixed(4));
                $("#vatable_amount").val(vatableAmount.toFixed(4));
                $("#zero_rated_amount").val(zeroRatedAmount.toFixed(4));
                $("#vat_exempt_amount").val(vatExemptAmount.toFixed(4));
                $("#total_amount_due").val(totalAmountDue.toFixed(4));
            }

            // Event listeners
            $('#addItemBtn').click(addRow);

            $(document).on('click', '.removeRow', function () {
                $(this).closest('tr').remove();
                calculateRowValues($(this).closest('tr'));
                calculateTotalAmount();
            });

            // Gather table items and submit form
            function gatherTableItems() {
                const items = [];
                $('#itemTableBody tr').each(function (index) {
                    const item = {
                        item_id: $(this).find('select[name="item_id[]"]').val(),
                        description: $(this).find('input[name="description[]"]').val(),
                        uom: $(this).find('input[name="uom[]"]').val(),
                        quantity: $(this).find('input[name="quantity[]"]').val(),
                        cost: $(this).find('input[name="cost[]"]').val(),
                        amount: $(this).find('input[name="amount[]"]').val(),
                        discount_percentage: $(this).find('select[name="discount_percentage[]"]').val(),
                        discount_amount: $(this).find('input[name="discount_amount[]"]').val(),
                        discount_account_id: $(this).find('.discount_percentage option:selected').data('account-id'),
                        net_amount_before_input_vat: $(this).find('input[name="net_amount_before_input_vat[]"]').val(),
                        net_amount: $(this).find('input[name="net_amount[]"]').val(),
                        input_vat_percentage: $(this).find('select[name="input_vat_percentage[]"]').val(),
                        input_vat_amount: $(this).find('input[name="input_vat_amount[]"]').val(),
                        input_vat_id: $(this).find('.input_vat_percentage option:selected').data('account-id')

                    };


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


        // Function to populate multiple fields based on selected option
        function populateFields(select) {
            const selectedOption = $(select).find('option:selected');
            const description = selectedOption.data('description');
            const uom = selectedOption.data('uom');
            // Add more fields as needed

            const row = $(select).closest('tr');
            row.find('.description-field').val(description);
            row.find('.uom').val(uom);
            // Populate more fields as needed
        }


    </script>