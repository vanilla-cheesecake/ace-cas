<style>
    /* Customize modal width */
    .modal-custom {
        max-width: 100%;
        width: 100%;

    }

    label {
        font-size: 10px;
    }

    /* Fix header when scrolling */
    #itemTable thead th {
        position: sticky;
        top: 0;
        background-color: white;
        /* Ensure header is visible on scroll */
        z-index: 1;
        /* Ensure header is above other elements */
    }

    /* Prevent text wrapping */
    #itemTable thead th {
        white-space: nowrap;
        font-size: 10px;
    }

    /* Custom styles for table inputs */
    .table-sm .form-control-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }

    .table-sm select.form-control-sm {
        height: calc(1.8125rem + 2px);
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }

    .table-sm input[readonly] {
        background-color: #e9ecef;
        opacity: 1;
    }

    .table-sm .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
</style>

<!-- 

purchase_order table

po_no
po_date
delivery_date
vendor_id
vendor_address
terms
gross_amount
discount_amount
net_amount
input_vat
vatable_amount
zero_rated
vat_exempt
total_amount
memo
status

purchase_order_details table

po_id
item_id
qty
cost
amount
discount_type_id
discount
net_amount
taxable_amount
tax_type_id
input_vat

 -->

<!-- modal_template.html -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-custom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <img src="./photos/logo.jpg" alt="AdminLTE Logo" class="brand-image image-fluid"
                    style="opacity: .8 width: 50px; height: 50px">
                <h5 class="modal-title" id="exampleModalLabel">New Purchase Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="writeCheckForm" action="api/purchase_order_controller.php?action=add" method="POST">
                    <input type="hidden" name="action" id="modalAction" value="add" />
                    <input type="hidden" name="id" id="itemId" value="" />
                    <input type="hidden" name="item_data" id="item_data" />
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <!-- PURCHASE ORDER NO -->
                                <label for="po_no">PO NO:</label>
                                <input type="text" class="form-control form-control-sm" id="po_no" name="po_no"
                                    placeholder="Enter po no">
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
                                <input type="date" class="form-control form-control-sm" id="po_date" name="po_date"
                                    value="<?php echo date('Y-m-d'); ?>">
                                <!-- DELIVERY DATE -->
                                <label for="">DELIVERY DATE</label>
                                <input type="date" class="form-control form-control-sm" id="delivery_date"
                                    name="delivery_date" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <!-- SELECT VENDOR -->
                            <div class="form-group">
                                <label for="vendor_id">VENDOR</label>
                                <select class="form-control form-control-sm" id="vendor_id" name="vendor_id">
                                    <option value="">Select Vendor</option>
                                    <?php foreach ($vendors as $vendor): ?>
                                        <option value="<?= $vendor->id ?>" data-address="<?= $vendor->vendor_address ?>"
                                            data-terms="<?= $vendor->terms ?>"
                                            data-account="<?= $vendor->account_number ?>"><?= $vendor->vendor_name ?>
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
                                    name="vendor_address">
                            </div>

                        </div>
                        <div class="col-md-3">
                            <!-- TIN -->
                            <div class="form-group">
                                <label for="">TIN</label>
                                <input type="text" class="form-control form-control-sm" id="tin" name="tin">
                            </div>

                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <!-- <label for="ref_no">TERMS</label>
                                <input type="text" class="form-control form-control-sm" id="ref_no" name="ref_no"
                                    placeholder="Enter ref no"> -->
                                <label for="">TERMS</label>
                                <select class="form-control form-control-sm" id="terms" name="terms">
                                    <option value="Due on Receipt">Due on Receipt</option>
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
                                        placeholder="Enter memo"></textarea>
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
                                                    name="gross_amount" id="gross_amount" readonly>
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

                                        <div class="col-md-6 d-inline-block">
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

                                        <div class="col-md-6 d-inline-block">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control form-control-sm"
                                                    name="input_vat_amount" id="input_vat_amount" readonly>
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
                                                    name="vatable_amount" id="vatable_amount" readonly>
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
                                                    name="zero_rated_amount" id="zero_rated_amount" readonly>
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
                                                    name="vat_exempt_amount" id="vat_exempt_amount" readonly>
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