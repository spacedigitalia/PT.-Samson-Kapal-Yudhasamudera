<?php

class ShiptypesController
{
    private mysqli $db;
    private ?AuthController $auth;

    public function __construct(mysqli $db, ?AuthController $auth = null)
    {
        $this->db = $db;
        $this->auth = $auth;
    }

    private function log(?int $userId, string $action, ?string $description = null): void
    {
        if ($this->auth) {
            $this->auth->log($userId, $action, $description);
        }
    }

    /**
     * @return array<int, array>
     */
    public function getAll(): array
    {
        $list = [];
        $stmt = $this->db->prepare("SELECT id, title, description, image, user_id, created_at, updated_at FROM `ship_types` ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $list[] = $row;
        }
        $stmt->close();
        return $list;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT s.id, s.title, s.description, s.image, s.user_id, s.created_at, s.updated_at, a.fullname FROM `ship_types` s LEFT JOIN `accounts` a ON s.user_id = a.id WHERE s.id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function create(string $title, string $description, string $image, ?int $userId): bool
    {
        $stmt = $this->db->prepare("INSERT INTO `ship_types` (title, description, image, user_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sssi', $title, $description, $image, $userId);
        $ok = $stmt->execute();
        if ($ok) {
            $this->log($userId, 'ship_type_create', 'Created ship type: ' . $title);
        }
        $stmt->close();
        return (bool) $ok;
    }

    public function update(int $id, string $title, string $description, string $image, ?int $userId): bool
    {
        $stmt = $this->db->prepare("UPDATE `ship_types` SET title = ?, description = ?, image = ?, user_id = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param('sssii', $title, $description, $image, $userId, $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'ship_type_update', 'Updated ship type id ' . $id);
        }
        return (bool) $ok;
    }

    public function delete(int $id, ?int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `ship_types` WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'ship_type_delete', 'Deleted ship type id ' . $id);
        }
        return (bool) $ok;
    }
}
