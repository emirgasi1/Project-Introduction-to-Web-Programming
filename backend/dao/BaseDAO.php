<?php
abstract class BaseDao {
    protected PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    abstract protected function tableName(): string;

    abstract protected function primaryKey(): string;

    abstract protected function columns(): array;

    public function getAll(): array {
    $stmt = $this->db->query("SELECT * FROM {$this->tableName()}");
    if ($stmt === false) {
        return [];
    }
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

    // Filter samo kolone koje postoje u $data
    $sets = [];
    $values = [];
    foreach ($cols as $col) {
        if (array_key_exists($col, $data)) {
            $sets[] = "$col = ?";
            $values[] = $data[$col];
        }
    }

    if (empty($sets)) {
        // Nema šta da se updateuje, vrati false ili baci grešku
        throw new InvalidArgumentException("No fields to update.");
    }

    $values[] = $id;
    $setList = implode(', ', $sets);
    $sql = "UPDATE {$this->tableName()} SET $setList WHERE {$this->primaryKey()} = ?";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute($values);
}

}
