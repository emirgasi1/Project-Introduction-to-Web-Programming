<?php

abstract class BaseService {
    protected BaseDao $dao;

    public function __construct(BaseDao $dao) {
        $this->dao = $dao;
    }

    public function getAll(): array {
        return $this->dao->getAll();
    }

    public function getById(int $id): ?array {
        return $this->dao->getById($id);
    }

    public function create(array $data): int {
        return $this->dao->create($data);
    }

    public function update(int $id, array $data): bool {
        return $this->dao->update($id, $data);
    }

    public function delete(int $id): bool {
        return $this->dao->delete($id);
    }
}
