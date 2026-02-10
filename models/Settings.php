<?php
// models/Settings.php
require_once __DIR__ . '/../core/Model.php';

class Settings extends Model
{
    /**
     * Get a setting value by key
     * @param string $key
     * @return string|null
     */
    public function get($key)
    {
        $stmt = $this->db->prepare('SELECT value FROM settings WHERE `key` = :key LIMIT 1');
        $stmt->execute([':key' => $key]);
        $result = $stmt->fetch();
        return $result ? $result['value'] : null;
    }

    /**
     * Set a setting value using UPSERT pattern (UPDATE then INSERT if needed)
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function set($key, $value)
    {
        // Try to update existing record
        $updateStmt = $this->db->prepare('UPDATE settings SET value = :value, updated_at = CURRENT_TIMESTAMP WHERE `key` = :key');
        $updateStmt->execute([':key' => $key, ':value' => $value]);

        // If no rows were affected, insert a new record
        if ($updateStmt->rowCount() === 0) {
            $insertStmt = $this->db->prepare('INSERT INTO settings (`key`, value) VALUES (:key, :value)');
            return $insertStmt->execute([':key' => $key, ':value' => $value]);
        }

        return true;
    }
}
