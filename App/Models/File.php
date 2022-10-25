<?php

namespace App\models;

use App\Database\Database;
use App\Interfaces\Repository;

class File extends Model implements Repository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function save(string $fileName): bool
    {
        $sql = "insert into files (name, directory, stored_at) values (
                    :fileName,
                    :directory,
                    NOW()       
                   )";
        $result = $this->db->pdo->prepare($sql);
        $result->bindParam(':fileName', $fileName);
        $storage = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['storage'] . '/' .
            $this->getLastId() + 1 . '-' . $fileName;
        $result->bindParam(':directory', $storage);
        $result->execute();
        return true;
    }

    public function getAll(): array
    {
        $sql = "select * from files";
        $result = $this->db->pdo->prepare($sql);
        $result->execute();
        return $result->fetchAll(Database::FETCH_ASSOC);
    }

    public function findById(int $id)
    {
        $sql = "select * from files where id=:id";
        $result = $this->db->pdo->prepare($sql);
        $result->execute([
            'id' => $id
        ]);
        return $result->fetch(Database::FETCH_ASSOC);
    }

    public function findByName(string $name): array
    {
        $sql = "select * from files where name=:name";
        $result = $this->db->pdo->prepare($sql);
        $result->execute([
            'name' => $name
        ]);
        return $result->fetchAll(Database::FETCH_ASSOC);
    }

    public function getLastId()
    {
        $sql = "SELECT id FROM files ORDER BY id DESC LIMIT 1";
        $result = $this->db->pdo->prepare($sql);
        $result->execute();
        return current($result->fetch(Database::FETCH_ASSOC));
    }

    public function deleteById(int $id)
    {
        $sql = "delete from files where id=:id";
        $result = $this->db->pdo->prepare($sql);
        $result->execute([
            'id' => $id
        ]);
        return null;
    }
}
