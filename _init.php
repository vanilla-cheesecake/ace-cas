<?php

require_once '_config.php';
require_once '_helper.php';
require_once 'models/User.php';
require_once 'models/Category.php';
require_once 'models/Order.php';
require_once 'models/OrderItem.php';
require_once 'models/Sales.php';
require_once 'models/PurchaseOrder.php';
require_once 'models/WriteCheck.php';
require_once 'models/TransactionEntry.php';

// MASTERLIST
require_once 'models/masterlist/Service.php';
require_once 'models/masterlist/ChartOfAccount.php';
require_once 'models/masterlist/Vendor.php';
require_once 'models/masterlist/OtherNameList.php';
require_once 'models/masterlist/Uom.php';
require_once 'models/masterlist/Product.php';
require_once 'models/masterlist/Customer.php';
require_once 'models/masterlist/Location.php';
require_once 'models/masterlist/Term.php';
require_once 'models/masterlist/PaymentMethod.php';
require_once 'models/masterlist/InputVat.php';
require_once 'models/masterlist/SalesTax.php';
require_once 'models/masterlist/WithholdingTax.php';
require_once 'models/masterlist/CostCenter.php';
require_once 'models/masterlist/Employee.php';
require_once 'models/masterlist/Discount.php';


// GUARD ROLE XX
require_once '_guards.php';

session_start();

try {
    $connection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {

    header('Content-type: text/plain');

    die("
        Error: Failed to connect to database
        Reason: {$e->getMessage()}
        Note: 
            - Try to open config.php and check if the mysql is configured correctly.
            - Make sure that the mysql server is running.
    ");
}