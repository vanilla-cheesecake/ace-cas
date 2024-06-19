<?php

require_once __DIR__ . '/../../_init.php';

class ChartOfAccount
{
    public $id;
    public $account_type;
    public $accountName;
    public $account_code;

    public $description;
    public $subAccount;

    private static $cache = null;

    public function __construct($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->account_type = isset($data['account_type']) ? $data['account_type'] : null;
        $this->accountName = isset($data['account_name']) ? $data['account_name'] : null;
        $this->account_code = isset($data['account_code']) ? $data['account_code'] : null;

        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->subAccount = isset($data['sub_account']) ? $data['sub_account'] : null;
    }


    // public function update() 
    // {
    //     global $connection;
    //     //Check if name is unique
    //     $category = self::findByName($this->name);
    //     if ($category && $category->id !== $this->id) throw new Exception('Name already exists.');

    //     $stmt = $connection->prepare('UPDATE categories SET name=:name WHERE id=:id');
    //     $stmt->bindParam('name', $this->name);
    //     $stmt->bindParam('id', $this->id);
    //     $stmt->execute();
    // }

    public function delete()
    {
        global $connection;

        $stmt = $connection->prepare('DELETE FROM `chart_of_account` WHERE id=:id');
        $stmt->bindParam('id', $this->id);
        $stmt->execute();
    }

    public static function all()
    {
        global $connection;

        if (static::$cache)
            return static::$cache;

        $stmt = $connection->prepare('SELECT * FROM `chart_of_account`');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        static::$cache = array_map(function ($item) {
            return new ChartOfAccount($item);
        }, $result);

        return static::$cache;
    }


    public static function add($accountType, $accountName, $accountCode, $description, $subAccount)
    {
        global $connection;

        if (static::findByAccountCode($accountCode)) {
            throw new Exception('Account with this code already exists');
        }

        $stmt = $connection->prepare('INSERT INTO `chart_of_account` 
                                      (account_type, account_name, account_code, description, sub_account) 
                                      VALUES (:accountType, :accountName, :accountCode, :description, :subAccount)');
        $stmt->bindParam("accountType", $accountType);
        $stmt->bindParam("accountName", $accountName);
        $stmt->bindParam("accountCode", $accountCode);

        $stmt->bindParam("description", $description);
        $stmt->bindParam("subAccount", $subAccount);
        $stmt->execute();
    }

    public static function findByAccountCode($accountCode)
    {
        global $connection;

        $stmt = $connection->prepare("SELECT * FROM `chart_of_account` WHERE account_code=:accountCode");
        $stmt->bindParam("accountCode", $accountCode);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        if (count($result) >= 1) {
            return new ChartOfAccount($result[0]);
        }

        return null;
    }

    public static function find($id)
    {
        global $connection;

        $stmt = $connection->prepare("SELECT * FROM `chart_of_account` WHERE id=:id");
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        if (count($result) >= 1) {
            return new ChartOfAccount($result[0]);
        }

        return null;
    }
}