<?php
require_once __DIR__ . '/BaseDAO.php';

class UserDao extends BaseDAO {
    protected function tableName(): string {
        return 'users';
    }

    protected function primaryKey(): string {
        return 'user_id';
    }

    protected function columns(): array {
        return ['username', 'email', 'password_hash', 'role'];
    }
}
