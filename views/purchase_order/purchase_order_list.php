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

            <h1 class="h3 mb-3"><strong>Purchases</strong> Order</h1>

            <div class="row">
                <div class="col-12">
                    <?php displayFlashMessage('add_purchase_order') ?>
                    <?php displayFlashMessage('delete_payment_method') ?>
                    <?php displayFlashMessage('update_payment_method') ?>
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <a href="create_purchase_order" class="btn btn-secondary">Purchase Order</a>

                            <br><br><br>
                            <table id="table" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>PO No</th>
                                        <th>VENDOR</th>
                                        <th>Total Amount Due</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($purchase_orders as $purchase_order): ?>
                                        <tr>
                                            <td><?= $purchase_order->po_id ?></td>
                                            <td><?= $purchase_order->po_date ?></td>
                                            <td><?= $purchase_order->po_no ?></td>
                                            <td><?= $purchase_order->vendor_name ?></td>
                                            <td><?= $purchase_order->total_amount ?></td>
                                            <td>
                                                <?php if ($purchase_order->status == 1): ?>
                                                    <span class="badge bg-danger">WAITING FOR DELIVERY</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success"><?= $purchase_order->status ?></span>
                                                <?php endif; ?>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
    </main>



    <!-- Modal -->
    <!-- EXPENSE MODAL -->
    <div id="modal-container">
        <?php include 'modals/purchase_order.php'; ?>
    </div>



    <?php require 'views/templates/footer.php' ?>


    <script>

        // Attach click event to table rows
        $("#table tbody").on("click", "tr", function () {
            // Get the Purchase Order ID (poID) from the first cell of the clicked row
            var poID = $(this).find("td:first").text();
            // Redirect to the view_purchase_order.php with the poID parameter
            window.location.href = "view_purchase_order?id=" + poID;
        });

        $(function () {
            $("#table").DataTable({
                "responsive": true,
                "lengthChange": true, // Allow changing the number of rows displayed
                "lengthMenu": [10, 20, 50, 100], // Define the options
                "autoWidth": false,
                "buttons": ["csv", "excel", "pdf", "colvis"],
            }).buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)');

            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
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