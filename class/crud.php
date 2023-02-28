<?php
require_once 'config.php';

class Crud
{
    private $db;

    public function __construct()
    {
        $db = new Config();
        $this->db =  $db->getConnection();
    }

    public function create($table, $data)
    {
        $fields = implode(',', array_keys($data));
        $values = ':' . implode(',:', array_keys($data));
        $sql = "INSERT INTO $table ($fields) VALUES ($values)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    public function read($table, $condition = null, $order = null, $limit = null)
    {
        $sql = "SELECT * FROM $table";
        if ($condition) {
            $sql .= " WHERE $condition";
        }
        if ($order) {
            $sql .= " ORDER BY $order";
        }
        if ($limit) {
            $sql .= " LIMIT $limit";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($table, $data, $condition)
    {
        $set = [];
        foreach ($data as $field => $value) {
            $set[] = "$field=:$field";
        }
        $set = implode(',', $set);
        $sql = "UPDATE $table SET $set WHERE $condition";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $stmt->rowCount();
    }

    public function delete($table, $id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM $table WHERE id=?");
            $stmt->execute([$id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
