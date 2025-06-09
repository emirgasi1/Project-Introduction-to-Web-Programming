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
    file_put_contents('C:\xampps\htdocs\EmirGasi\Project-Introduction-to-Web-Programming\frontend\REGISTER-DEBUG.txt', "ULAZ U CREATE\n", FILE_APPEND);

    $cols = $this->columns();
    $fields = implode(', ', $cols);
    $placeholders = implode(', ', array_fill(0, count($cols), '?'));
    $values = [];
    foreach ($cols as $col) {
        if (!array_key_exists($col, $data)) {
            file_put_contents('C:\xampps\htdocs\EmirGasi\Project-Introduction-to-Web-Programming\frontend\REGISTER-DEBUG.txt', "ENTITY: ".print_r($data, 1), FILE_APPEND);
            throw new InvalidArgumentException("Missing required field: $col");
        }
        $values[] = $data[$col];
    }
    $sql = "INSERT INTO {$this->tableName()} ($fields) VALUES ($placeholders)";
        file_put_contents('C:\xampps\htdocs\EmirGasi\Project-Introduction-to-Web-Programming\frontend\REGISTER-DEBUG.txt', "DATA: ".print_r($data, 1), FILE_APPEND);

    try {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);
        file_put_contents('C:\xampps\htdocs\EmirGasi\Project-Introduction-to-Web-Programming\frontend\REGISTER-DEBUG.txt', "EXECUTE OK\n", FILE_APPEND);
    } catch (Exception $ex) {
        file_put_contents('C:\xampps\htdocs\EmirGasi\Project-Introduction-to-Web-Programming\frontend\REGISTER-DEBUG.txt', "PDO EXCEPTION: " . $ex->getMessage() . "\n", FILE_APPEND);
        throw $ex;
    }

    $lastId = (int)$this->db->lastInsertId();
    file_put_contents('C:\xampps\htdocs\EmirGasi\Project-Introduction-to-Web-Programming\frontend\REGISTER-DEBUG.txt', "LAST INSERT ID: $lastId\n", FILE_APPEND);
    return $lastId;
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
