<style>
    .sidebar-item.active {

        background: linear-gradient(to right, rgba(34,48,94, 1) 0%, rgba(34,48,94, 0.4) 100%);
    }
</style>


<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar" id="sidebar-content">
        <a class="sidebar-brand" href="dashboard">
            <span class="align-middle">ACE ACCOUNTING</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Pages
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'dashboard' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="dashboard">
                    <i class="align-middle" data-feather="home"></i> <span class="align-middle">Dashboard</span>
                </a>
            </li>

            <li class="sidebar-header">
                Customer Center
            </li>

            <li
                class="sidebar-item <?php echo in_array(getCurrentPage(), ['invoice', 'create_invoice']) ? 'active' : ''; ?>">
                <a class="sidebar-link" href="invoice">
                    <i class="align-middle" data-feather="shopping-cart"></i> <span class="align-middle">Sales
                        Invoice</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'receive_payment' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="receive_payment">
                    <i class="align-middle" data-feather="dollar-sign"></i> <span class="align-middle">Receive
                        Payment</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'billing_statement' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="billing_statement">
                    <i class="align-middle" data-feather="file-text"></i> <span class="align-middle">Billing
                        Statement</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'credit_memo' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="credit_memo">
                    <i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Credit
                        Memo</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'sales_return' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="sales_return">
                    <i class="align-middle" data-feather="arrow-left-circle"></i> <span class="align-middle">Sales
                        Return</span>
                </a>
            </li>
            <li class="sidebar-header">
                Vendor Center
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'purchase_order' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="purchase_order">
                    <i class="align-middle" data-feather="shopping-cart"></i> <span class="align-middle">Purchase
                        Order</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'receive_items' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="receive_items">
                    <i class="align-middle" data-feather="box"></i> <span class="align-middle">Receive Items</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'enter_bills' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="enter_bills">
                    <i class="align-middle" data-feather="file-text"></i> <span class="align-middle">Enter Bills</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'purchase_return' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="purchase_return">
                    <i class="align-middle" data-feather="rotate-ccw"></i> <span class="align-middle">Purchase
                        Return</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'pay_bills' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="pay_bills">
                    <i class="align-middle" data-feather="dollar-sign"></i> <span class="align-middle">Pay Bills</span>
                </a>
            </li>

            <li class="sidebar-header">
                Banking
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'write_check' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="write_check">
                    <i class="align-middle" data-feather="file-text"></i> <span class="align-middle">Write Check</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'make_deposit' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="make_deposit">
                    <i class="align-middle" data-feather="arrow-down-circle"></i> <span class="align-middle">Make
                        Deposit</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'bank_transfer' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="bank_transfer">
                    <i class="align-middle" data-feather="refresh-cw"></i> <span class="align-middle">Bank
                        Transfer</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'reconcile' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="reconcile">
                    <i class="align-middle" data-feather="check-circle"></i> <span class="align-middle">Reconcile</span>
                </a>
            </li>

            <li class="sidebar-header">
                Accounting
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'general_journal' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="general_journal">
                    <i class="align-middle" data-feather="file-text"></i> <span class="align-middle">General
                        Journal</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'transaction_entries' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="transaction_entries">
                    <i class="align-middle" data-feather="file-text"></i> <span class="align-middle">Transaction
                        Entries</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'trial_balance' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="trial_balance">
                    <i class="align-middle" data-feather="bar-chart-2"></i> <span class="align-middle">Trial
                        Balance</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'audit_trail' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="audit_trail">
                    <i class="align-middle" data-feather="clipboard"></i> <span class="align-middle">Audit Trail</span>
                </a>
            </li>

            <li class="sidebar-header">
                Reports
            </li>


            <li class="sidebar-item <?php echo getCurrentPage() == 'profit_loss' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="profit_loss">
                    <i class="align-middle" data-feather="trending-up"></i> <span class="align-middle">Profit &
                        Loss</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'balance_sheet' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="balance_sheet">
                    <i class="align-middle" data-feather="bar-chart"></i> <span class="align-middle">Balance
                        Sheet</span>
                </a>
            </li>


            <li class="sidebar-header">
                MasterList
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'chart_of_accounts' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="chart_of_accounts">
                    <i class="align-middle" data-feather="list"></i> <span class="align-middle">Chart of Accounts</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'item_list' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="item_list">
                    <i class="align-middle" data-feather="shopping-bag"></i> <span class="align-middle">Item List</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'customer' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="customer">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">Customer List</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'vendor' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="vendor">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">Vendor List</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'other_name' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="other_name">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">Other Name List</span>
                </a>
            </li>

            <li class="sidebar-header">
                Other List
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'location' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="location">
                    <i class="align-middle" data-feather="map-pin"></i> <span class="align-middle">Location List</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'oum' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="oum">
                    <i class="align-middle" data-feather="layers"></i> <span class="align-middle">Uom List</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'category' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="category">
                    <i class="align-middle" data-feather="list"></i> <span class="align-middle">Category List</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'terms' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="terms">
                    <i class="align-middle" data-feather="file-text"></i> <span class="align-middle">Terms List</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'payment_method' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="payment_method">
                    <i class="align-middle" data-feather="credit-card"></i> <span class="align-middle">Payment
                        Methods</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'discount' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="discount">
                    <i class="align-middle" data-feather="percent"></i> <span class="align-middle">Discount</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'input_vat' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="input_vat">
                    <i class="align-middle" data-feather="percent"></i> <span class="align-middle">Input Vat</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'sales_tax' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="sales_tax">
                    <i class="align-middle" data-feather="percent"></i> <span class="align-middle">Sales Tax Rate</span>
                </a>
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'wtax_rate' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="wtax_rate">
                    <i class="align-middle" data-feather="percent"></i> <span class="align-middle">WTax Rate</span>
                </a>
            </li>
            <hr>
            <!-- ===================================================================================== -->
            <li class="sidebar-item">
                <a data-bs-target="#material" data-bs-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="box"></i> <span class="align-middle">Material
                        Management</span>
                </a>
                <ul id="material" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <a class='sidebar-link' href='/material-request'>
                            <i class="align-middle" data-feather="file-text"></i> Material Request
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class='sidebar-link' href='/material-issuance'>
                            <i class="align-middle" data-feather="file-text"></i> Material Issuance
                        </a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-item">
                <a data-bs-target="#purchase" data-bs-toggle="collapse" class="sidebar-link collapsed">
                    <i class="align-middle" data-feather="shopping-cart"></i> <span class="align-middle">Purchase
                        Management</span>
                </a>
                <ul id="purchase" class="sidebar-dropdown list-unstyled show" data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <a class='sidebar-link' href='/purchase-request'>
                            <i class="align-middle" data-feather="file-text"></i> Purchase Request
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class='sidebar-link' href='/canvass-sheet'>
                            <i class="align-middle" data-feather="file-text"></i> Canvass Sheet
                        </a>
                    </li>
                </ul>
            </li>
            <!-- ===================================================================================== -->

            <li class="sidebar-header">
                System Maintenance
            </li>

            <li class="sidebar-item <?php echo getCurrentPage() == 'admin_user.php' ? 'active' : ''; ?>">
                <a class="sidebar-link" href="admin_user.php">
                    <i class="align-middle" data-feather="user"></i> <span class="align-middle">Users</span>
                </a>
            </li>
        </ul>

    </div>
</nav>