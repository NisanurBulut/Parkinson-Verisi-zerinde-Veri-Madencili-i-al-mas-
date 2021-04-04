<?php

namespace app\core\db;

use app\core\Model;
use app\core\Application;

abstract class DbModel extends Model
{
    abstract public function tableName(): string;
    abstract public function attributes(): array;
    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn ($attr) => ":$attr", $attributes);

        $statement = self::prepare("INSERT INTO $tableName (" . implode(',', $attributes) . ")
        VALUES(" . implode(',', $params) . ")");

        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        $statement->execute();
        return true;
    }
    public function update($id)
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = [];
        $statement = "";
        foreach ($attributes as $key)
        {
            if (isset($_POST[$key]) && $key != "id")
            {
                $statement .= "`$key` = :$key,";
                $params[$key] = $this->{$key};
            }
        }
        $statement = rtrim($statement, ",");
        $params['id'] = $id;
        self::prepare("UPDATE $tableName SET $statement WHERE id = :id")->execute($params);
        return true;
    }
    public function where($where) { // [email=>nisanurrunasin@gmail.com, firstName=> Nisanur]
        $tableName = $this->tableName();
        $attributes = array_keys($where);
        $sql = implode("AND ",array_map(fn($attr)=>"$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach($where as $key=>$value){
            $statement->bindValue(":$key",$value);
        }
        $statement->execute();
        return $statement->fetchObject(static::class); // gives me instance
    }
    public function delete($value)
    {
        $tableName = $this->tableName();

        $query="DELETE FROM $tableName WHERE id=".($value);

        $statement = self::prepare($query);
        $statement->execute();
        return true;
    }
    public function select()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn ($attr) => ":$attr", $attributes);
        $statement = self::prepare("SELECT * FROM $tableName");
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
        // echo '<pre>';
        // var_dump($statement, $params, $attributes);
        // '</pre>';
        // exit;
    }
    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}

        // echo '<pre>';
        // var_dump($statement, $params, $attributes);
        // '</pre>';
        // exit;