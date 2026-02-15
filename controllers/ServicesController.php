<?php

class ServicesController
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
        $stmt = $this->db->prepare("SELECT id, title, description, image, user_id, created_at, updated_at FROM `services` ORDER BY id DESC");
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
        $stmt = $this->db->prepare("SELECT s.id, s.title, s.description, s.image, s.user_id, s.created_at, s.updated_at, a.fullname FROM `services` s LEFT JOIN `accounts` a ON s.user_id = a.id WHERE s.id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function create(string $title, ?string $description, string $image, ?int $userId): bool
    {
        $stmt = $this->db->prepare("INSERT INTO `services` (title, description, image, user_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sssi', $title, $description, $image, $userId);
        $ok = $stmt->execute();
        if ($ok) {
            $this->log($userId, 'service_create', 'Created service: ' . $title);
        }
        $stmt->close();
        return (bool) $ok;
    }

    public function update(int $id, string $title, ?string $description, string $image, ?int $userId): bool
    {
        $stmt = $this->db->prepare("UPDATE `services` SET title = ?, description = ?, image = ?, user_id = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param('sssii', $title, $description, $image, $userId, $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'service_update', 'Updated service id ' . $id);
        }
        return (bool) $ok;
    }

    public function delete(int $id, ?int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `services` WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'service_delete', 'Deleted service id ' . $id);
        }
        return (bool) $ok;
    }
}
