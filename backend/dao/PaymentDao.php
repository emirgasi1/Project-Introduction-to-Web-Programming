<?php
require_once __DIR__ . '/BaseDAO.php';

class PaymentDao extends BaseDAO {
    protected function tableName(): string {
        return 'payments';
    }

    protected function primaryKey(): string {
        return 'payment_id';
    }

    protected function columns(): array {
        return ['order_id', 'payment_method', 'payment_status'];
    }
}
