<?php

require_once 'BaseDao.php';

class AuthDao extends BaseDao {
    protected function tableName(): string {
        return 'users';  // Tabela u kojoj se nalaze podaci o korisnicima
    }

    protected function primaryKey(): string {
        return 'user_id';  // Primarni ključ tabele
    }

    protected function columns(): array {
        return ['email', 'password_hash', 'username', 'role'];  // ← OVO MORA BITI
    }


    public function get_user_by_email($email) {
        $sql = "SELECT * FROM {$this->tableName()} WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);  // Vraća korisnika sa tim email-om ili null
    }
}
