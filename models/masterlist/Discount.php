<?php

require_once __DIR__ . '/../../_init.php';

class Discount
{
    public $id;
    public $discount_name;
    public $discount_rate;
    public $description;
    public $discount_account_id;
    public $discount_account_name;
    private static $cache = null;

    public function __construct($discount)
    {
        $this->id = $discount['id'];
        $this->discount_name = $discount['discount_name'];
        $this->discount_rate = $discount['discount_rate'];
        $this->description = $discount['description'];
        $this->discount_account_id = $discount['discount_account_id'];
        $this->discount_account_name = $discount['account_name'];
    }
    public function update()
    {
        global $connection;
        //Check if name is unique
        $discount = self::findByName($this->discount_name);
        if ($discount && $discount->id !== $this->id)
            throw new Exception('Withholding tax already exists.');

        $stmt = $connection->prepare('UPDATE discount SET discount_name=:discount_name WHERE id=:id');
        $stmt->bindParam('discount_name', $this->discount_name);
        $stmt->bindParam('id', $this->id);
        $stmt->execute();
    }
    public function delete()
    {
        global $connection;

        $stmt = $connection->prepare('DELETE FROM `discount` WHERE id=:id');
        $stmt->bindParam('id', $this->id);
        $stmt->execute();
    }
    public static function all()
    {
        global $connection;

        if (static::$cache)
            return static::$cache;

        $stmt = $connection->prepare('
            SELECT discount.*, chart_of_account.account_name
            FROM discount
            JOIN chart_of_account
            ON discount.discount_account_id = chart_of_account.id
        ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        static::$cache = array_map(function ($item) {
            return new Discount($item);
        }, $result);

        return static::$cache;
    }

    public static function add($discount_name, $discount_rate, $description, $discount_account_id)
    {
        global $connection;

        if (static::findByName($discount_name))
            throw new Exception('Withholding tax already exists');

        $stmt = $connection->prepare('INSERT INTO `discount`(discount_name, discount_rate, description, discount_account_id) 
        VALUES (:discount_name, :discount_rate, :description, :discount_account_id)');
        $stmt->bindParam("discount_name", $discount_name);
        $stmt->bindParam("discount_rate", $discount_rate);
        $stmt->bindParam("description", $description);
        $stmt->bindParam("discount_account_id", $discount_account_id);
        $stmt->execute();
    }

    public static function findByName($discount_name)
    {
        global $connection;

        $stmt = $connection->prepare("SELECT * FROM `discount` WHERE discount_name=:discount_name");
        $stmt->bindParam("discount_name", $discount_name);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        if (count($result) >= 1) {
            return new Discount($result[0]);
        }

        return null;
    }

    public static function find($id)
    {
        global $connection;

        $stmt = $connection->prepare("SELECT * FROM `discount` WHERE id=:id");
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        if (count($result) >= 1) {
            return new Discount($result[0]);
        }

        return null;
    }
}