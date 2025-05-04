<?php
require_once __DIR__ . '/BaseDAO.php';

class OrderDao extends BaseDAO {
    protected function tableName(): string {
        return 'orders';
    }

    protected function primaryKey(): string {
        return 'order_id';
    }

    protected function columns(): array {
        // ovdje navodimo samo one kolone koje BaseDAO koristi za INSERT/UPDATE
        return ['user_id', 'total_price', 'status'];
    }
}
