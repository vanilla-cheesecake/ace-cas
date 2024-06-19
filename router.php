<?php
// router.php 
// Define the mapping of pages to include files
$pages = [
    'index' => 'index.php',
    // DEVELOPER ONLY
    'dashboard' => 'views/admin_home.php',

    // SALES INVOICE
    'invoice' => 'views/invoice/invoice_list.php',
    'create_invoice' => 'views/invoice/create_invoice.php',

    // RECEIVE PAYMENT
    'receive_payment' => 'views/receive_payment/receive_payment_list.php',
    'create_receive_payment' => 'views/receive_payment/create_receive_payment.php',

    // BILLING STATEMENT
    'billing_statement' => 'views/billing_statement/billing_statement_list.php',

    // CREDIT MEMO
    'credit_memo' => 'views/credit_memo/credit_memo_list.php',

    // SALES RETURN
    'sales_return' => 'views/sales_return/sales_return_list.php',

    // PURCHASE ORDER
    'purchase_order' => 'views/purchase_order/purchase_order_list.php',
    'view_purchase_order' => 'views/purchase_order/view_purchase_order.php',
    'create_purchase_order' => 'views/purchase_order/create_purchase_order.php',
    'print_purchase_order' => 'views/purchase_order/print_purchase_order.php',

    // WRITE CHECK
    'write_check' => 'views/write_check/write_check_list.php',
    'create_check' => 'views/write_check/create_write_check.php',
    'view_check' => 'views/write_check/view_check.php',
    'print_check' => 'views/write_check/print_check.php',

    // RECEIVE ITEMS
    'receive_items' => 'views/receive_items/receive_item_list.php',
    'create_receive_item' => 'views/receive_items/create_receive_item.php',

    // ENTER BILLS 
    'enter_bills' => 'views/enter_bills/enter_bills_list.php',

    // PURCHASE RETURN
    'purchase_return' => 'views/purchase_return/purchase_return_list.php',

    // PAY BILLS
    'pay_bills' => 'views/pay_bills/pay_bills_list.php',

    // MAKE DEPOSIT
    'make_deposit' => 'views/make_deposit/make_deposit_list.php',

    // BANK TRANSFER
    'bank_transfer' => 'views/bank_transfer/bank_transfer_list.php',

    // RECONCILE
    'reconcile' => 'views/reconcile/reconcile_list.php',

    // GENERAL JOURBAL
    'general_journal' => 'views/general_journal/general_journal_list.php',
    'create_general_journal' => 'views/general_journal/create_general_journal.php',

    // TRANSACTION ENTRIES
    'transaction_entries' => 'views/transaction_entries/transaction_entries_list.php',

    // TRIAL BALANCE
    'trial_balance' => 'views/trial_balance/trial_balance_list.php',

    // AUDIT TRAIL
    'audit_trail' => 'views/audit_trail/audit_trail_list.php',

    // PROFIT LOSS
    'profit_loss' => 'views/profit_loss/profit_loss_list.php',

    // BALANCE SHEET
    'balance_sheet' => 'views/balance_sheet/balance_sheet_list.php',

    // CHART OF ACCOUNTS
    'chart_of_accounts' => 'views/chart_of_accounts/chart_of_accounts_list.php',

    // ITEM LIST
    'item_list' => 'views/items/item_list.php',

    // CUSTOMER LIST
    'customer' => 'views/customer/customer_list.php',

    // VENDOR LIST
    'vendor' => 'views/vendor/vendor_list.php',

    // OTHER NAME
    'other_name' => 'views/other_name/other_name_list.php',

    // LOCATION LIST
    'location' => 'views/location/location_list.php',

    // OUM LIST
    'oum' => 'views/uom/oum_list.php',

    // CATEGORY LIST
    'category' => 'views/category/category_list.php',

    // TERMS LIST
    'terms' => 'views/terms/terms_list.php',

    // PAYMENT METHOD
    'payment_method' => 'views/payment_method/payment_method_list.php',

    // DISCOUNT LIST
    'discount' => 'views/discount/discount_list.php',

    // INPUT VAT
    'input_vat' => 'views/input_vat/input_vat_list.php',

    // SALES TAX
    'sales_tax' => 'views/sales_tax/sales_tax_list.php',

    // WTAX RATE
    'wtax_rate' => 'views/wtax/wtax_rate_list.php',




    'main/clean_database' => 'main/Developer/clean_database.view.php',
    'main/backup_database' => 'main/Developer/backup_database.view.php',
    'logout' => 'logout.php',
    'notfound' => '404.php',
];

// Get the requested page from the rewritten URL
$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'index';
$page = array_key_exists($page, $pages) ? $page : 'notfound';

$file = $pages[$page];

if (file_exists($file)) {
    include $file;
} else {
    include 'views/404.php';
}