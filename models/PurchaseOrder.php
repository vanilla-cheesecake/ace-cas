<?php

require_once __DIR__ . '/../_init.php';


class PurchaseOrder
{

    // Define properties to match your form fields
    public $po_id;
    public $po_no;
    public $po_date;
    public $delivery_date;
    public $vendor_id;
    public $vendor_name;
    public $vendor_address;
    public $tin;
    public $terms;
    public $gross_amount;
    public $discount_amount;
    public $discount_name;
    public $discount_rate;
    public $net_amount_due;
    public $input_vat_amount;
    public $po_input_vat;
    public $vatable_amount;
    public $zero_rated_amount;
    public $vat_exempt;
    public $total_amount;
    public $memo;
    public $status;

    public static $cache = null;


    // Define properties for purchase order details
    public $details;
    // Constructor to initialize the object with form data
    public function __construct($formData)
    {
        $this->po_id = $formData['po_id'] ?? null;
        $this->po_no = $formData['po_no'] ?? null;
        $this->po_date = $formData['po_date'] ?? null;
        $this->delivery_date = $formData['delivery_date'] ?? null;
        $this->vendor_id = $formData['vendor_id'] ?? null;
        $this->vendor_name = $formData['vendor_name'] ?? null;
        $this->vendor_address = $formData['vendor_address'] ?? null;
        $this->tin = $formData['tin'] ?? null;
        $this->terms = $formData['terms'] ?? null;
        $this->gross_amount = $formData['gross_amount'] ?? null;
        $this->discount_amount = $formData['discount_amount'] ?? null;
        $this->net_amount_due = $formData['po_net_amount'] ?? null; // changed from 'net_amount_due'
        $this->po_input_vat = $formData['po_input_vat'] ?? null;
        $this->vatable_amount = $formData['vatable'] ?? null; // changed from 'vatable_amount'
        $this->zero_rated_amount = $formData['zero_rated'] ?? null; // changed from 'zero_rated_amount'
        $this->vat_exempt = $formData['vat_exempt'] ?? null; // changed from 'zero_rated_amount'
        $this->total_amount = $formData['total_amount'] ?? null;
        $this->memo = $formData['memo'] ?? null;
        $this->status = $formData['status'] ?? null;

        // Initialize details as an empty array
        $this->details = [];

        // Populate other properties as before...

        // Optionally, you can populate details if provided in $formData
        if (isset($formData['details'])) {
            foreach ($formData['details'] as $detail) {
                // Push each detail to the details array
                $this->details[] = $detail;
            }
        }

    }

    // Static method to find a purchase order by ID
    public static function find($id)
    {
        global $connection;

        $stmt = $connection->prepare('
            SELECT 
                po.id AS po_id,
                po.po_no,
                po.date AS po_date,
                po.delivery_date,
                po.vendor_id,
                v.vendor_name,
                v.vendor_address,
                v.account_number,
                po.terms,
                po.gross_amount,
                po.discount_amount,
                po.net_amount AS po_net_amount,
                po.input_vat AS po_input_vat,
                po.vatable,
                po.zero_rated,
                po.vat_exempt,
                po.total_amount,
                po.memo,
                po.status,
                po.created_at AS po_created_at,
                pod.id AS pod_id,
                pod.item_id,
                pod.qty,
                pod.cost,
                pod.amount,
                pod.discount_type_id,
                d.discount_name,
                d.discount_rate,
                pod.discount,
                pod.net_amount AS pod_net_amount,
                pod.taxable_amount,
                pod.tax_type_id,
                te.input_vat_name,
                te.input_vat_rate,
                pod.input_vat AS pod_input_vat,
                pod.created_at AS pod_created_at,
                i.item_name,
                i.purchase_description,
                uom.name AS uom_name
            FROM purchase_order po
            INNER JOIN purchase_order_details pod ON po.id = pod.po_id
            INNER JOIN vendors v ON po.vendor_id = v.id
            LEFT JOIN discount d ON pod.discount_type_id = d.id
            LEFT JOIN input_vat te ON pod.tax_type_id = te.id
            INNER JOIN items i ON pod.item_id = i.id
            LEFT JOIN uom ON i.uom_id = uom.id
            WHERE po.id = :id
        ');

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        if (empty($result)) {
            return null;
        }

        // Initialize purchase order with the first row
        $firstRow = $result[0];
        $purchaseOrder = [
            'po_id' => $firstRow['po_id'],
            'po_no' => $firstRow['po_no'],
            'po_date' => $firstRow['po_date'],
            'delivery_date' => $firstRow['delivery_date'],
            'vendor_id' => $firstRow['vendor_id'],
            'vendor_name' => $firstRow['vendor_name'],
            'vendor_address' => $firstRow['vendor_address'],
            'tin' => $firstRow['account_number'],
            'terms' => $firstRow['terms'],
            'gross_amount' => $firstRow['gross_amount'],
            'discount_amount' => $firstRow['discount_amount'],
            'po_net_amount' => $firstRow['po_net_amount'],
            'po_input_vat' => $firstRow['po_input_vat'],
            'vatable' => $firstRow['vatable'],
            'zero_rated' => $firstRow['zero_rated'],
            'vat_exempt' => $firstRow['vat_exempt'],
            'total_amount' => $firstRow['total_amount'],
            'memo' => $firstRow['memo'],
            'status' => $firstRow['status'],
            'created_at' => $firstRow['po_created_at'],
            'details' => []
        ];

        // Populate details
        foreach ($result as $row) {
            $purchaseOrder['details'][] = [
                'pod_id' => $row['pod_id'],
                'item_id' => $row['item_id'],
                'item_name' => $row['item_name'],
                'purchase_description' => $row['purchase_description'],
                'uom_name' => $row['uom_name'],
                'qty' => $row['qty'],
                'cost' => $row['cost'],
                'amount' => $row['amount'],
                'discount_type_id' => $row['discount_type_id'],
                'discount_name' => $row['discount_name'],
                'discount_rate' => $row['discount_rate'],
                'discount' => $row['discount'],
                'net_amount' => $row['pod_net_amount'],
                'taxable_amount' => $row['taxable_amount'],
                'tax_type_id' => $row['tax_type_id'],
                'input_vat' => $row['pod_input_vat'],
                'input_vat_name' => $row['input_vat_name'],
                'input_vat_rate' => $row['input_vat_rate'],
                'created_at' => $row['pod_created_at']
            ];
        }

        return new PurchaseOrder($purchaseOrder);
    }



    public static function add($po_no, $po_date, $delivery_date, $vendor_id, $terms, $gross_amount, $discount_amount, $net_amount_due, $input_vat_amount, $vatable_amount, $zero_rated_amount, $vat_exempt_amount, $total_amount_due, $memo)
    {

        global $connection;

        $stmt = $connection->prepare("INSERT INTO purchase_order (po_no, date, delivery_date, vendor_id,
            terms,
            gross_amount,
            discount_amount,
            net_amount,
            input_vat,
            vatable,
            zero_rated,
            vat_exempt,
            total_amount,
            memo
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $po_no,
            $po_date,
            $delivery_date,
            $vendor_id,
            $terms,
            $gross_amount,
            $discount_amount,
            $net_amount_due,
            $input_vat_amount,
            $vatable_amount,
            $zero_rated_amount,
            $vat_exempt_amount,
            $total_amount_due,
            $memo
        ]);
    }

    public static function addItem($transaction_id, $item_id, $quantity, $cost, $amount, $discount_percentage, $discount_amount, $net_amount_before_input_vat, $net_amount, $input_vat_percentage, $input_vat_amount)
    {
        global $connection;
        $stmt = $connection->prepare("INSERT INTO purchase_order_details (
            po_id, 
            item_id, 
            qty, 
            cost, 
            amount, 
            discount_type_id, 
            discount,
            net_amount,
            taxable_amount,
            tax_type_id,
            input_vat) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute(
            [
                $transaction_id,
                $item_id,
                $quantity,
                $cost,
                $amount,
                $discount_percentage,
                $discount_amount,
                $net_amount_before_input_vat,
                $net_amount,
                $input_vat_percentage,
                $input_vat_amount
            ]
        );

    }

    public static function findByPoNo($po_no)
    {
        global $connection;

        $stmt = $connection->prepare('SELECT * FROM purchase_order WHERE po_no = :po_no');
        $stmt->bindParam("po_no", $po_no);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        if (count($result) >= 1) {
            return new PurchaseOrder($result[0]);
        }

        return null;
    }

    // Method to get the last transaction_id
    public static function getLastTransactionId()
    {
        global $connection;

        $stmt = $connection->query("SELECT MAX(id) AS last_transaction_id FROM purchase_order");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        if ($result && isset($result['last_transaction_id'])) {
            return $result['last_transaction_id'];
        }

        return null;
    }


    // Static method to get all purchase orders with details
    public static function all()
    {
        global $connection;

        $stmt = $connection->prepare('
        SELECT DISTINCT
            po.id AS po_id,
            po.po_no,
            po.date AS po_date,
            po.delivery_date,
            po.vendor_id,
            v.vendor_name,
            v.vendor_address,
            po.terms,
            po.gross_amount,
            po.discount_amount,
            po.net_amount AS po_net_amount,
            po.input_vat AS po_input_vat,
            po.vatable,
            po.zero_rated,
            po.vat_exempt,
            po.total_amount,
            po.memo,
            po.status,
            po.created_at AS po_created_at,
            pod.id AS pod_id,
            pod.item_id,
            pod.qty,
            pod.cost,
            pod.amount,
            pod.discount_type_id,
            pod.discount,
            pod.net_amount AS pod_net_amount,
            pod.taxable_amount,
            pod.tax_type_id,
            pod.input_vat AS pod_input_vat,
            pod.created_at AS pod_created_at
        FROM purchase_order po
        INNER JOIN purchase_order_details pod ON po.id = pod.po_id
        INNER JOIN vendors v ON po.vendor_id = v.id
    ');

        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        $purchaseOrders = [];
        foreach ($result as $row) {
            $poId = $row['po_id'];
            if (!isset($purchaseOrders[$poId])) {
                $purchaseOrders[$poId] = [
                    'po_id' => $poId,
                    'po_no' => $row['po_no'],
                    'po_date' => $row['po_date'],
                    'delivery_date' => $row['delivery_date'],
                    'vendor_id' => $row['vendor_id'],
                    'vendor_name' => $row['vendor_name'],
                    'vendor_address' => $row['vendor_address'],
                    'gross_amount' => $row['gross_amount'],
                    'discount_amount' => $row['discount_amount'],
                    'po_net_amount' => $row['po_net_amount'],
                    'po_input_vat' => $row['po_input_vat'],
                    'vatable' => $row['vatable'],
                    'zero_rated' => $row['zero_rated'],
                    'vat_exempt' => $row['vat_exempt'],
                    'total_amount' => $row['total_amount'],
                    'memo' => $row['memo'],
                    'status' => $row['status'],
                    'created_at' => $row['po_created_at'],
                    'details' => []
                ];
            }
            $purchaseOrders[$poId]['details'][] = [
                'pod_id' => $row['pod_id'],
                'item_id' => $row['item_id'],
                'qty' => $row['qty'],
                'cost' => $row['cost'],
                'amount' => $row['amount'],
                'discount_type_id' => $row['discount_type_id'],
                'discount' => $row['discount'],
                'net_amount' => $row['pod_net_amount'],
                'taxable_amount' => $row['taxable_amount'],
                'tax_type_id' => $row['tax_type_id'],
                'input_vat' => $row['pod_input_vat'],
                'created_at' => $row['pod_created_at']
            ];
        }

        return array_map(fn($data) => new PurchaseOrder($data), array_values($purchaseOrders));
    }



}