<?php
require_once __DIR__ . '/BaseDao.php';

class CategoryDao extends BaseDao {
    protected function tableName(): string {
        return 'categories';
    }

    protected function primaryKey(): string {
        return 'category_id';
    }

    protected function columns(): array {
        return ['category_name'];
    }
}
