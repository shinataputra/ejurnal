<?php
// models/Journal.php
require_once __DIR__ . '/../core/Model.php';

class Journal extends Model
{
    public function create($data)
    {
        $stmt = $this->db->prepare('INSERT INTO journals (user_id,date,class_id,subject_id,jam_ke,materi,notes) VALUES (:user_id,:date,:class_id,:subject_id,:jam_ke,:materi,:notes)');
        return $stmt->execute($data);
    }

    public function countByUserAndDate($user_id, $date)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) as c FROM journals WHERE user_id = :u AND date = :d');
        $stmt->execute([':u' => $user_id, ':d' => $date]);
        $r = $stmt->fetch();
        return $r ? (int)$r['c'] : 0;
    }

    public function listByAcademicYear($start_date, $end_date, $user_id = null)
    {
        $sql = 'SELECT j.*, c.name as class_name, s.name as subject_name FROM journals j JOIN classes c ON j.class_id = c.id JOIN subjects s ON j.subject_id = s.id WHERE j.date BETWEEN :start AND :end';
        $params = [':start' => $start_date, ':end' => $end_date];
        if ($user_id) {
            $sql .= ' AND j.user_id = :u';
            $params[':u'] = $user_id;
        }
        $sql .= ' ORDER BY j.date DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function dailyRecap($user_id, $start_date, $end_date)
    {
        $sql = 'SELECT j.date, COUNT(*) as total_entries, GROUP_CONCAT(DISTINCT c.name) as classes, GROUP_CONCAT(DISTINCT s.name) as subjects FROM journals j JOIN classes c ON j.class_id = c.id JOIN subjects s ON j.subject_id = s.id WHERE j.user_id = :u AND j.date BETWEEN :start AND :end GROUP BY j.date ORDER BY j.date DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':u' => $user_id, ':start' => $start_date, ':end' => $end_date]);
        return $stmt->fetchAll();
    }

    public function monthlyRecap($user_id, $year = null)
    {
        if ($year === null) {
            $year = date('Y');
        }
        $sql = 'SELECT DATE_FORMAT(j.date, "%m") as month, DATE_FORMAT(j.date, "%m-%Y") as month_year, COUNT(*) as total_entries, COUNT(DISTINCT j.date) as days_filled, GROUP_CONCAT(DISTINCT c.name) as classes, GROUP_CONCAT(DISTINCT s.name) as subjects FROM journals j JOIN classes c ON j.class_id = c.id JOIN subjects s ON j.subject_id = s.id WHERE j.user_id = :u AND YEAR(j.date) = :year GROUP BY DATE_FORMAT(j.date, "%m"), DATE_FORMAT(j.date, "%m-%Y") ORDER BY month ASC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':u' => $user_id, ':year' => $year]);
        return $stmt->fetchAll();
    }

    public function getJournalsByMonthAndUser($user_id, $month_year)
    {
        // $month_year format: "MM-YYYY"
        $parts = explode('-', $month_year);
        if (count($parts) !== 2) return [];
        $month = $parts[0];
        $year = $parts[1];
        $sql = 'SELECT j.*, c.name as class_name, s.name as subject_name FROM journals j JOIN classes c ON j.class_id = c.id JOIN subjects s ON j.subject_id = s.id WHERE j.user_id = :u AND MONTH(j.date) = :month AND YEAR(j.date) = :year ORDER BY j.date DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':u' => $user_id, ':month' => $month, ':year' => $year]);
        return $stmt->fetchAll();
    }

    public function findById($id, $user_id)
    {
        $stmt = $this->db->prepare('SELECT j.*, c.name as class_name, s.name as subject_name FROM journals j JOIN classes c ON j.class_id = c.id JOIN subjects s ON j.subject_id = s.id WHERE j.id = :id AND j.user_id = :u');
        $stmt->execute([':id' => $id, ':u' => $user_id]);
        return $stmt->fetch();
    }

    public function update($id, $user_id, $data)
    {
        $stmt = $this->db->prepare('UPDATE journals SET date = :date, class_id = :class_id, subject_id = :subject_id, jam_ke = :jam_ke, materi = :materi, notes = :notes WHERE id = :id AND user_id = :u');
        return $stmt->execute([
            ':date' => $data['date'],
            ':class_id' => $data['class_id'],
            ':subject_id' => $data['subject_id'],
            ':jam_ke' => $data['jam_ke'],
            ':materi' => $data['materi'],
            ':notes' => $data['notes'],
            ':id' => $id,
            ':u' => $user_id
        ]);
    }

    public function delete($id, $user_id)
    {
        $stmt = $this->db->prepare('DELETE FROM journals WHERE id = :id AND user_id = :u');
        return $stmt->execute([':id' => $id, ':u' => $user_id]);
    }
}
