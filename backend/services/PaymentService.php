<?php
require_once __DIR__ . '/../dao/PaymentDao.php';

class PaymentService {
    private $dao;

    public function __construct($db) {
        $this->dao = new PaymentDao($db);
    }

    public function getAll() {
        return $this->dao->getAll();
    }

    public function getById($id) {
        return $this->dao->getById($id);
    }

    public function create($data) {
        return $this->dao->create($data);
    }

    public function update($id, $data) {
        return $this->dao->update($id, $data);
    }

    public function delete($id) {
        return $this->dao->delete($id);
    }
}
