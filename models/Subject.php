<?php
// models/Subject.php
require_once __DIR__ . '/../core/Model.php';

class Subject extends Model
{
    public function all()
    {
        $stmt = $this->db->query('SELECT * FROM subjects ORDER BY name');
        return $stmt->fetchAll();
    }

    public function create($name)
    {
        $stmt = $this->db->prepare('INSERT INTO subjects (name) VALUES (:name)');
        return $stmt->execute([':name' => $name]);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM subjects WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function update($id, $name)
    {
        $stmt = $this->db->prepare('UPDATE subjects SET name = :name WHERE id = :id');
        return $stmt->execute([':name' => $name, ':id' => $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM subjects WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
