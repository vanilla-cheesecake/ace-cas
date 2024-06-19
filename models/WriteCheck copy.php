<?php

require_once __DIR__ . '/../_init.php';


class WriteCheck
{
    public $id;
    public $account_id;
    public $check_no;
    public $account_name; // Add account_name property
    public $payee_type;
    public $ref_no;
    public $payee_id;
    public $write_check_date;
    public $payee_address;
    public $memo;
    public $gross_amount;
    public $discount_amount;
    public $net_amount_due;
    public $vat_percentage_amount;
    public $net_of_vat;
    public $tax_withheld_amount;
    public $total_amount_due;
    public $payee_name;
    public $transactionEntries; // Add transactionEntries property

    public $account_type;

    public $cv_no;

    public $details;
    public function __construct($data)
    {
        $this->id = $data['id'] ?? null;
        $this->check_no = $data['check_no'] ?? null;
        $this->cv_no = $data['cv_no'] ?? null;
        $this->account_id = $data['account_id'] ?? null;
        $this->account_name = $data['account_name'] ?? null;
        $this->account_type = $data['account_type'] ?? null;
        $this->payee_type = $data['payee_type'] ?? null;
        $this->ref_no = $data['ref_no'] ?? null;
        $this->write_check_date = $data['write_check_date'] ?? null;
        $this->payee_address = $data['payee_address'] ?? null;
        $this->memo = $data['memo'] ?? null;
        $this->gross_amount = $data['gross_amount'] ?? null;
        $this->discount_amount = $data['discount_amount'] ?? null;
        $this->net_amount_due = $data['net_amount_due'] ?? null;
        $this->vat_percentage_amount = $data['vat_percentage_amount'] ?? null;
        $this->net_of_vat = $data['net_of_vat'] ?? null;
        $this->tax_withheld_amount = $data['tax_withheld_amount'] ?? null;
        $this->total_amount_due = $data['total_amount_due'] ?? null;
        $this->payee_name = $data['payee_name'] ?? null;

        // Initialize transactionEntries property as an empty array
        $this->transactionEntries = [];

        // Initialize details as an empty array
        $this->details = [];

        // Optionally, you can populate details if provided in $formData
        if (isset($data['details'])) {
            foreach ($data['details'] as $detail) {
                // Push each detail to the details array
                $this->details[] = $detail;
            }
        }


    }
    public static function findByRefNo($refNo)
    {
        global $connection;

        $stmt = $connection->prepare('
            SELECT 
                wc.*,
                wcd.transaction_id,
                wcd.account_id AS details_account_id,
                wcd.memo AS details_memo,
                coa.account_name,
                te.ref_no,
                te.debit,
                te.credit
            FROM 
                wchecks wc
            LEFT JOIN 
                wchecks_details wcd ON wc.id = wcd.transaction_id
            LEFT JOIN 
                chart_of_account coa ON wcd.account_id = coa.id
            LEFT JOIN 
                transaction_entries te ON wc.ref_no = te.ref_no
            WHERE 
                wc.ref_no = :refNo
        ');
        $stmt->bindParam("refNo", $refNo);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        if (count($result) >= 1) {
            return new WriteCheck($result[0]);
        }

        return null;
    }

    public static function all()
    {
        global $connection;

        $stmt = $connection->prepare('
        SELECT DISTINCT
            wchecks.id AS check_id,
            wchecks.account_id,
            wchecks.check_no,
            wchecks.ref_no,
            wchecks.payee_type,
            wchecks.write_check_date,
            wchecks.payee_id,
            wchecks.payee_address,
            wchecks.memo,
            wchecks.gross_amount,
            wchecks.net_amount_due,
            wchecks.discount_amount,
            wchecks.vat_percentage_amount,
            wchecks.net_of_vat,
            wchecks.tax_withheld_amount,
            wchecks.total_amount_due,
            wchecks_details.transaction_id,
            wchecks_details.account_id,
            wchecks_details.memo,
            wchecks_details.amount,
            wchecks_details.discount_amount,
            wchecks_details.net_amount,
            wchecks_details.taxable_amount,
            wchecks_details.input_vat,
            chart_of_account.account_name,
            CASE 
                WHEN wchecks.payee_type = "vendors" THEN vendors.vendor_name
                WHEN wchecks.payee_type = "customers" THEN customers.customer_name
                WHEN wchecks.payee_type = "other_name" THEN other_name.other_name
                ELSE NULL
            END AS payee_name
        FROM wchecks
        INNER JOIN wchecks_details
        ON wchecks.id = wchecks_details.transaction_id
        LEFT JOIN vendors
        ON wchecks.payee_type = "vendors" AND wchecks.payee_id = vendors.id
        LEFT JOIN customers
        ON wchecks.payee_type = "customers" AND wchecks.payee_id = customers.id
        LEFT JOIN other_name
        ON wchecks.payee_type = "other_name" AND wchecks.payee_id = other_name.id
        LEFT JOIN chart_of_account
        ON wchecks.account_id = chart_of_account.id
    ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        $checks = [];
        foreach ($result as $row) {
            $checkId = $row['check_id'];
            if (!isset($checks[$checkId])) {
                $checks[$checkId] = [
                    'id' => $checkId,
                    'account_id' => $row['account_id'],
                    'check_no' => $row['check_no'],
                    'account_name' => $row['account_name'],
                    'ref_no' => $row['ref_no'],
                    'payee_type' => $row['payee_type'],
                    'write_check_date' => $row['write_check_date'],
                    'payee_id' => $row['payee_id'],
                    'payee_address' => $row['payee_address'],
                    'memo' => $row['memo'],
                    'gross_amount' => $row['gross_amount'],
                    'discount_amount' => $row['discount_amount'],
                    'net_amount_due' => $row['net_amount_due'],
                    'vat_percentage_amount' => $row['vat_percentage_amount'],
                    'net_of_vat' => $row['net_of_vat'],
                    'tax_withheld_amount' => $row['tax_withheld_amount'],
                    'total_amount_due' => $row['total_amount_due'],
                    'payee_name' => $row['payee_name'],
                    'details' => []
                ];
            }
            $checks[$checkId]['details'][] = [
                'transaction_id' => $row['transaction_id'],
                'account_id' => $row['account_id'],
                'memo' => $row['memo'],
                'amount' => $row['amount'],
                'discount_amount' => $row['discount_amount'],
                'net_amount' => $row['net_amount'],
                'taxable_amount' => $row['taxable_amount'],
                'input_vat' => $row['input_vat']
            ];
        }

        return array_map(fn($data) => new WriteCheck($data), array_values($checks));
    }


    // Add main form data to the database
    public static function add(
        $cv_no,
        $check_no,
        $account_id,
        $payee_type,
        $ref_no,
        $payee_id,
        $write_check_date,
        $payee_address,
        $memo,
        $gross_amount,
        $discount_amount,
        $net_amount_due,
        $vat_percentage_amount,
        $net_of_vat,
        $tax_withheld_amount,
        $total_amount_due
    ) {
        global $connection;
        $stmt = $connection->prepare("INSERT INTO wchecks (
            cv_no,
            check_no,
            account_id, 
            payee_type, 
            ref_no, 
            payee_id, 
            write_check_date, 
            payee_address, 
            memo, 
            gross_amount,
            discount_amount, 
            net_amount_due, vat_percentage_amount, net_of_vat, tax_withheld_amount, total_amount_due) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$cv_no, $check_no, $account_id, $payee_type, $ref_no, $payee_id, $write_check_date, $payee_address, $memo, $gross_amount, $discount_amount, $net_amount_due, $vat_percentage_amount, $net_of_vat, $tax_withheld_amount, $total_amount_due]);
    }

    // Add item details to the database
    public static function addItem($transaction_id, $account_id, $memo, $amount, $discount_amount, $net_amount_before_vat, $net_amount, $vat_amount)
    {
        global $connection;
        $stmt = $connection->prepare("INSERT INTO wchecks_details (
            transaction_id, 
            account_id, 
            memo, 
            amount, 
            discount_amount, 
            net_amount, 
            taxable_amount, 
            input_vat) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute(
            [
                $transaction_id,
                $account_id,
                $memo,
                $amount,
                $discount_amount,
                $net_amount_before_vat,
                $net_amount,
                $vat_amount
            ]
        );

    }

    // Method to get the last transaction_id
    public static function getLastTransactionId()
    {
        global $connection;

        $stmt = $connection->query("SELECT MAX(id) AS last_transaction_id FROM wchecks");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        if ($result && isset($result['last_transaction_id'])) {
            return $result['last_transaction_id'];
        }

        return null;
    }


    // Add entries to transaction_entries table
    // Add entries to transaction_entries table
    public static function addTransactionEntries(
        $transaction_id,
        $type,
        $ref_no,
        $account_id,
        $debit,
        $credit
    ) {
        global $connection;

        // Prepare statement for inserting into transaction_entries
        $stmt = $connection->prepare("INSERT INTO transaction_entries (transaction_id, type, ref_no, account_id, debit, credit) VALUES (?, ?, ?, ?, ?, ?)");

        // Execute the statement
        $stmt->execute([$transaction_id, $type, $ref_no, $account_id, $debit, $credit]);
    }

    public function delete()
    {
        global $connection;

        $stmt = $connection->prepare('DELETE FROM `wchecks` WHERE id=:id');
        $stmt->bindParam('id', $this->id);
        $stmt->execute();
    }


    public static function find($id)
    {
        global $connection;

        $stmt = $connection->prepare('
            SELECT 
                wc.*,
                wcd.transaction_id,
                wcd.account_id AS details_account_id,
                wcd.memo AS details_memo,
                wcd.amount,
                coa.account_name,
                coa.account_type,
                te.type,
                te.ref_no,
                te.account_id,
                te.debit,
                te.credit,
                CASE 
                    WHEN wc.payee_type = "vendors" THEN vendors.vendor_name
                    WHEN wc.payee_type = "customers" THEN customers.customer_name
                    WHEN wc.payee_type = "other_name" THEN other_name.other_name
                    ELSE NULL
                END AS payee_name
            FROM 
                wchecks wc
            LEFT JOIN 
                wchecks_details wcd ON wc.id = wcd.transaction_id
            LEFT JOIN 
                chart_of_account coa ON wcd.account_id = coa.id
            LEFT JOIN 
                vendors ON wc.payee_type = "vendors" AND wc.payee_id = vendors.id
            LEFT JOIN 
                customers ON wc.payee_type = "customers" AND wc.payee_id = customers.id
            LEFT JOIN 
                other_name ON wc.payee_type = "other_name" AND wc.payee_id = other_name.id
            LEFT JOIN 
                transaction_entries te ON wc.id = te.transaction_id
            WHERE 
                wc.id = :id
        ');
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        if (count($result) >= 1) {
            return new WriteCheck($result[0]);
        }

        return null;
    }

    public static function findTransactionEntries($checkId)
    {
        global $connection;

        $stmt = $connection->prepare('
            SELECT 
                te.type,
                te.ref_no,
                te.account_id,
                coa.account_name AS account_name,
                coa.account_code AS account_code,
                coa.account_type AS account_type,
                te.debit,
                te.credit
            FROM 
                transaction_entries te
            INNER JOIN 
                wchecks wc ON te.transaction_id = wc.id
            LEFT JOIN 
                chart_of_account coa ON te.account_id = coa.id
            WHERE 
                wc.id = :checkId
        ');
        $stmt->bindParam("checkId", $checkId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        return $result;
    }
    public function displayTransactionEntries()
    {
        foreach ($this->transactionEntries as $entry) {
            echo "Type: {$entry['type']}, Ref No: {$entry['ref_no']}, Debit: {$entry['debit']}, Credit: {$entry['credit']}<br>";
        }
    }
}