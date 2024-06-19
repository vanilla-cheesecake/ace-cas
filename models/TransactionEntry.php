<?php

require_once __DIR__ . '/../_init.php';


class TransactionEntry
{

    private static $cache = null;

    public $id;

    public $type;
    public $ref_no;

    public $account_id;

    public $debit;

    public $credit;

    public $account_name;
    public $date;

    public function __construct($entry)
    {
        $this->id = $entry['id'];
        $this->type = $entry['type'];
        $this->ref_no = $entry['ref_no'];
        $this->account_id = $entry['account_id'];
        $this->debit = $entry['debit'];
        $this->credit = $entry['credit'];
        $this->date = $entry['created_at'];
        $this->account_name = $entry['account_name'];

    }


    public static function all()
    {
        global $connection;

        if (static::$cache)
            return static::$cache;

        $stmt = $connection->prepare('
        SELECT transaction_entries.*, chart_of_account.account_name
        FROM transaction_entries                
        JOIN chart_of_account
        ON transaction_entries.account_id = chart_of_account.id');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        static::$cache = array_map(function ($entry) {
            return new TransactionEntry($entry);
        }, $result);

        return static::$cache;
    }


    public static function filterByDateRange($fromDate, $toDate)
    {
        global $connection;

        $stmt = $connection->prepare('
            SELECT transaction_entries.*, chart_of_account.account_name
            FROM transaction_entries                
            JOIN chart_of_account
            ON transaction_entries.account_id = chart_of_account.id
            WHERE DATE(transaction_entries.created_at) BETWEEN :from_date AND :to_date');
        $stmt->bindParam(':from_date', $fromDate);
        $stmt->bindParam(':to_date', $toDate);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        return array_map(function ($entry) {
            return new TransactionEntry($entry);
        }, $result);
    }

}