<?php
abstract class BaseDao {
    protected PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /** Ime tabele, npr. "categories" */
    abstract protected function tableName(): string;

    /** Primarni ključ, npr. "category_id" */
    abstract protected function primaryKey(): string;

    /** Kolone za INSERT/UPDATE (bez primarnog ključa i bez automatskih timestamp-ova) */
    abstract protected function columns(): array;

    public function getAll(): array {
        $sql = "SELECT * FROM {$this->tableName()}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array {
        $sql = "SELECT * FROM {$this->tableName()} WHERE {$this->primaryKey()} = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM {$this->tableName()} WHERE {$this->primaryKey()} = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function create(array $data): int {
        $cols = $this->columns();
        $fields = implode(', ', $cols);
        $placeholders = implode(', ', array_fill(0, count($cols), '?'));
        $values = [];
        foreach ($cols as $col) {
            if (!array_key_exists($col, $data)) {
                throw new InvalidArgumentException("Missing required field: $col");
            }
            $values[] = $data[$col];
        }
        $sql = "INSERT INTO {$this->tableName()} ($fields) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $cols = $this->columns();
        $sets = [];
        $values = [];
        foreach ($cols as $col) {
            if (!array_key_exists($col, $data)) {
                throw new InvalidArgumentException("Missing required field: $col");
            }
            $sets[] = "$col = ?";
            $values[] = $data[$col];
        }
        $values[] = $id;
        $setList = implode(', ', $sets);
        $sql = "UPDATE {$this->tableName()} SET $setList WHERE {$this->primaryKey()} = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }
}
