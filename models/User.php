<?php
// models/User.php
require_once __DIR__ . '/../core/Model.php';

class User extends Model
{
    public function findByUsername($username)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :u LIMIT 1');
        $stmt->execute([':u' => $username]);
        return $stmt->fetch();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function allTeachers()
    {
        $stmt = $this->db->query("SELECT * FROM users WHERE role='teacher' ORDER BY name");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare('INSERT INTO users (name,nip,username,password,role) VALUES (:name,:nip,:username,:password,:role)');
        return $stmt->execute($data);
    }

    public function resetPassword($id, $passwordHash)
    {
        $stmt = $this->db->prepare('UPDATE users SET password = :p WHERE id = :id');
        return $stmt->execute([':p' => $passwordHash, ':id' => $id]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare('UPDATE users SET name = :name, nip = :nip, username = :username, role = :role WHERE id = :id');
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':nip' => $data['nip'],
            ':username' => $data['username'],
            ':role' => $data['role']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id AND role = :role');
        return $stmt->execute([':id' => $id, ':role' => 'teacher']);
    }
}
