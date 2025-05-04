<?php
// backend/dao/OrderItemDao.php

require_once __DIR__ . '/BaseDAO.php';

class OrderItemDao extends BaseDAO {
    protected function tableName(): string {
        return 'order_items';
    }

    protected function primaryKey(): string {
        return 'order_item_id';
    }

    // Kolone koje BaseDAO koristi za INSERT/UPDATE
    protected function columns(): array {
        return ['order_id', 'product_id', 'quantity', 'price'];
    }

    // Override getAll da dohvaća i product_name
    public function getAll(): array {
        $sql = "SELECT oi.*, p.product_name
                  FROM order_items oi
             LEFT JOIN products p ON oi.product_id = p.product_id";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array {
        $sql = "SELECT oi.*, p.product_name
                  FROM order_items oi
             LEFT JOIN products p ON oi.product_id = p.product_id
                 WHERE oi.order_item_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
