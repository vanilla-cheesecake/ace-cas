<?php

require_once __DIR__ . '/../../_init.php';

class InputVat
{
    public $id;
    public $input_vat_name;
    public $input_vat_rate;
    public $description;

    public $input_vat_account_id;

    public $input_vat_account_name;

    private static $cache = null;

    public function __construct($input_vat)
    {
        $this->id = $input_vat['id'];
        $this->input_vat_name = $input_vat['input_vat_name'];
        $this->input_vat_rate = $input_vat['input_vat_rate'];
        $this->description = $input_vat['description'];
        $this->input_vat_account_id = $input_vat['input_vat_account_id'];
        $this->input_vat_account_name = $input_vat['account_name'];
    }

    public function update()
    {
        global $connection;
        //Check if name is unique
        $input_vat = self::findByName($this->input_vat_name);
        if ($input_vat && $input_vat->id !== $this->id)
            throw new Exception('Input Vat already exists.');

        $stmt = $connection->prepare('UPDATE input_vat SET input_vat_name=:input_vat_name WHERE id=:id');
        $stmt->bindParam('input_vat_name', $this->input_vat_name);
        $stmt->bindParam('id', $this->id);
        $stmt->execute();
    }

    public function delete()
    {
        global $connection;

        $stmt = $connection->prepare('DELETE FROM `input_vat` WHERE id=:id');
        $stmt->bindParam('id', $this->id);
        $stmt->execute();
    }

    public static function all()
    {
        global $connection;

        if (static::$cache)
            return static::$cache;

        $stmt = $connection->prepare('
            SELECT input_vat.*, chart_of_account.account_name
            FROM input_vat
            JOIN chart_of_account 
            ON input_vat.input_vat_account_id = chart_of_account.id
        ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        static::$cache = array_map(function ($item) {
            return new InputVat($item);
        }, $result);

        return static::$cache;
    }

    public static function add($input_vat_name, $input_vat_rate, $description, $input_vat_account_id)
    {
        global $connection;

        if (static::findByName($input_vat_name))
            throw new Exception('Input Vat already exists');

        $stmt = $connection->prepare('INSERT INTO `input_vat`(input_vat_name, input_vat_rate, description, input_vat_account_id) 
        VALUES (:input_vat_name, :input_vat_rate, :description, :input_vat_account_id)');
        $stmt->bindParam("input_vat_name", $input_vat_name);
        $stmt->bindParam("input_vat_rate", $input_vat_rate);
        $stmt->bindParam("description", $description);
        $stmt->bindParam("input_vat_account_id", $input_vat_account_id);
        $stmt->execute();
    }

    public static function findByName($input_vat_name)
    {
        global $connection;

        $stmt = $connection->prepare("SELECT * FROM `input_vat` WHERE input_vat_name=:input_vat_name");
        $stmt->bindParam("input_vat_name", $input_vat_name);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        if (count($result) >= 1) {
            return new InputVat($result[0]);
        }

        return null;
    }

    public static function find($id)
    {
        global $connection;

        $stmt = $connection->prepare("SELECT * FROM `input_vat` WHERE id=:id");
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $result = $stmt->fetchAll();

        if (count($result) >= 1) {
            return new InputVat($result[0]);
        }

        return null;
    }
}