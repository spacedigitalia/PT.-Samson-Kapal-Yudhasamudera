<?php

class HomeController
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
        $stmt = $this->db->prepare("SELECT id, title, description, image, button_text, button_link, user_id, created_at, updated_at FROM `homes` ORDER BY id DESC");
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
        $stmt = $this->db->prepare("SELECT h.id, h.title, h.description, h.image, h.button_text, h.button_link, h.user_id, h.created_at, h.updated_at, a.fullname FROM `homes` h LEFT JOIN `accounts` a ON h.user_id = a.id WHERE h.id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getFirst(): ?array
    {
        $stmt = $this->db->prepare("SELECT h.id, h.title, h.description, h.image, h.button_text, h.button_link, h.user_id, h.created_at, h.updated_at, a.fullname FROM `homes` h LEFT JOIN `accounts` a ON h.user_id = a.id ORDER BY h.id DESC LIMIT 1");
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function create(string $title, string $description, string $image, ?string $buttonText, ?string $buttonLink, ?int $userId): bool
    {
        $stmt = $this->db->prepare("INSERT INTO `homes` (title, description, image, button_text, button_link, user_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssi', $title, $description, $image, $buttonText, $buttonLink, $userId);
        $ok = $stmt->execute();
        if ($ok) {
            $this->log($userId, 'home_create', 'Created home: ' . $title);
        }
        $stmt->close();
        return (bool) $ok;
    }

    public function update(int $id, string $title, string $description, string $image, ?string $buttonText, ?string $buttonLink, ?int $userId): bool
    {
        $stmt = $this->db->prepare("UPDATE `homes` SET title = ?, description = ?, image = ?, button_text = ?, button_link = ?, user_id = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param('sssssii', $title, $description, $image, $buttonText, $buttonLink, $userId, $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'home_update', 'Updated home id ' . $id);
        }
        return (bool) $ok;
    }

    public function delete(int $id, ?int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `homes` WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'home_delete', 'Deleted home id ' . $id);
        }
        return (bool) $ok;
    }
}
