<?php
// models/AcademicYear.php
require_once __DIR__ . '/../core/Model.php';

class AcademicYear extends Model
{
    public function active()
    {
        $stmt = $this->db->query('SELECT * FROM academic_years WHERE is_active = 1 LIMIT 1');
        return $stmt->fetch();
    }

    public function all()
    {
        $stmt = $this->db->query('SELECT * FROM academic_years ORDER BY start_date DESC');
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM academic_years WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare('INSERT INTO academic_years (name,start_date,end_date,is_active) VALUES (:name,:start_date,:end_date,:is_active)');
        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare('UPDATE academic_years SET name = :name, start_date = :start_date, end_date = :end_date WHERE id = :id');
        $data[':id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM academic_years WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function setActive($id)
    {
        $this->db->beginTransaction();
        $this->db->exec('UPDATE academic_years SET is_active = 0');
        $stmt = $this->db->prepare('UPDATE academic_years SET is_active = 1 WHERE id = :id');
        $res = $stmt->execute([':id' => $id]);
        $this->db->commit();
        return $res;
    }
}
