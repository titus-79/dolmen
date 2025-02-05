<?php
namespace Titus\Dolmen\Models;
use Models\Database;

abstract class BaseModel {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findAll() {
        return $this->db->query("SELECT * FROM {$this->table}");
    }

    public function findById($id) {
        return $this->db->query("SELECT * FROM {$this->table} WHERE id = ?", [$id]);
    }
}