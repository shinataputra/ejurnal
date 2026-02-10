<?php
// models/Task.php
require_once __DIR__ . '/../core/Model.php';

class Task extends Model
{
    public function all()
    {
        $stmt = $this->db->query('SELECT t.*, u.name as teacher_name, c.name as class_name FROM tasks t 
                                  JOIN users u ON t.user_id = u.id 
                                  JOIN classes c ON t.class_id = c.id 
                                  ORDER BY t.created_at DESC');
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT t.*, u.name as teacher_name, c.name as class_name, 
                                           admin.name as verified_by_name 
                                    FROM tasks t 
                                    JOIN users u ON t.user_id = u.id 
                                    JOIN classes c ON t.class_id = c.id 
                                    LEFT JOIN users admin ON t.verified_by = admin.id 
                                    WHERE t.id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function findByTeacher($user_id)
    {
        $stmt = $this->db->prepare('SELECT t.*, c.name as class_name FROM tasks t 
                                    JOIN classes c ON t.class_id = c.id 
                                    WHERE t.user_id = :u ORDER BY t.date DESC, t.jam_ke');
        $stmt->execute([':u' => $user_id]);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare('INSERT INTO tasks (user_id, jenjang, class_id, date, jam_ke, file_path, status, created_at) 
                                    VALUES (:user_id, :jenjang, :class_id, :date, :jam_ke, :file_path, :status, NOW())');
        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare('UPDATE tasks SET jenjang = :jenjang, class_id = :class_id, date = :date, jam_ke = :jam_ke, file_path = :file_path WHERE id = :id');
        $data[':id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($id, $user_id = null)
    {
        // If user_id provided, only allow teacher to delete their own tasks
        if ($user_id !== null) {
            $task = $this->findById($id);
            if (!$task || $task['user_id'] != $user_id) {
                return false;
            }
            // Delete file if exists
            if ($task['file_path'] && file_exists(__DIR__ . '/../' . $task['file_path'])) {
                unlink(__DIR__ . '/../' . $task['file_path']);
            }
        }
        $stmt = $this->db->prepare('DELETE FROM tasks WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function verify($id, $verified_by_user_id)
    {
        $stmt = $this->db->prepare('UPDATE tasks SET status = :status, verified_by = :verified_by, verified_at = NOW() WHERE id = :id');
        return $stmt->execute([
            ':status' => 'verified',
            ':verified_by' => $verified_by_user_id,
            ':id' => $id
        ]);
    }

    public function reject($id, $admin_notes, $verified_by_user_id)
    {
        $stmt = $this->db->prepare('UPDATE tasks SET status = :status, admin_notes = :notes, verified_by = :verified_by, verified_at = NOW() WHERE id = :id');
        return $stmt->execute([
            ':status' => 'rejected',
            ':notes' => $admin_notes,
            ':verified_by' => $verified_by_user_id,
            ':id' => $id
        ]);
    }

    public function listByStatus($status = null, $start_date = null, $end_date = null)
    {
        $sql = 'SELECT t.*, u.name as teacher_name, c.name as class_name FROM tasks t 
                JOIN users u ON t.user_id = u.id 
                JOIN classes c ON t.class_id = c.id 
                WHERE 1=1';
        $params = [];

        if ($status !== null) {
            $sql .= ' AND t.status = :status';
            $params[':status'] = $status;
        }

        if ($start_date !== null) {
            $sql .= ' AND t.date >= :start_date';
            $params[':start_date'] = $start_date;
        }

        if ($end_date !== null) {
            $sql .= ' AND t.date <= :end_date';
            $params[':end_date'] = $end_date;
        }

        $sql .= ' ORDER BY t.date DESC, t.jam_ke';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countByStatus($status)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) as count FROM tasks WHERE status = :status');
        $stmt->execute([':status' => $status]);
        $result = $stmt->fetch();
        return $result ? (int)$result['count'] : 0;
    }

    public function countByTeacherAndStatus($user_id, $status)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) as count FROM tasks WHERE user_id = :u AND status = :status');
        $stmt->execute([':u' => $user_id, ':status' => $status]);
        $result = $stmt->fetch();
        return $result ? (int)$result['count'] : 0;
    }
}
