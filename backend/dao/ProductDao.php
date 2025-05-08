<?php
require_once __DIR__ . '/BaseDao.php';

class ProductDao extends BaseDao {
    protected function tableName(): string {
        return 'products';
    }

    protected function primaryKey(): string {
        return 'product_id';
    }

    protected function columns(): array {
        return ['product_name', 'description', 'price', 'category_id', 'image_url'];
    }
}
