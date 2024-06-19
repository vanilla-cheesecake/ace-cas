<style>
    /* Customize modal width */
    .modal-custom {
        max-width: 90%;
        /* Adjust this value as needed */
    }
</style>

<!-- modal_template.html -->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <div class="row">
                        <div class="col-md-3">
                            <!-- SELECT BANK ACCOUNT -->
                            <div class="form-group">
                                <label for="account_id">BANK ACCOUNT</label>
                                <select class="form-control" id="account_id" name="account_id">
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