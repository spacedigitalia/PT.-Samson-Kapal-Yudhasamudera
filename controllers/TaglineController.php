<?php

class TaglineController
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
        $stmt = $this->db->prepare("SELECT id, title_moto, quete_moto, description_moto, title_vission, quete_vission, description_vission, user_id, created_at, updated_at FROM `taglines` ORDER BY id DESC");
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
        $stmt = $this->db->prepare("SELECT t.id, t.title_moto, t.quete_moto, t.description_moto, t.title_vission, t.quete_vission, t.description_vission, t.user_id, t.created_at, t.updated_at, a.fullname FROM `taglines` t LEFT JOIN `accounts` a ON t.user_id = a.id WHERE t.id = ? LIMIT 1");
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
        $stmt = $this->db->prepare("SELECT t.id, t.title_moto, t.quete_moto, t.description_moto, t.title_vission, t.quete_vission, t.description_vission, t.user_id, t.created_at, t.updated_at, a.fullname FROM `taglines` t LEFT JOIN `accounts` a ON t.user_id = a.id ORDER BY t.id DESC LIMIT 1");
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function create(string $titleMoto, string $queteMoto, string $descriptionMoto, ?string $titleVission, string $queteVission, string $descriptionVission, ?int $userId): bool
    {
        $stmt = $this->db->prepare("INSERT INTO `taglines` (title_moto, quete_moto, description_moto, title_vission, quete_vission, description_vission, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssi', $titleMoto, $queteMoto, $descriptionMoto, $titleVission, $queteVission, $descriptionVission, $userId);
        $ok = $stmt->execute();
        if ($ok) {
            $this->log($userId, 'tagline_create', 'Created tagline: ' . $titleMoto);
        }
        $stmt->close();
        return (bool) $ok;
    }

    public function update(int $id, string $titleMoto, string $queteMoto, string $descriptionMoto, ?string $titleVission, string $queteVission, string $descriptionVission, ?int $userId): bool
    {
        $stmt = $this->db->prepare("UPDATE `taglines` SET title_moto = ?, quete_moto = ?, description_moto = ?, title_vission = ?, quete_vission = ?, description_vission = ?, user_id = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param('ssssssii', $titleMoto, $queteMoto, $descriptionMoto, $titleVission, $queteVission, $descriptionVission, $userId, $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'tagline_update', 'Updated tagline id ' . $id);
        }
        return (bool) $ok;
    }

    public function delete(int $id, ?int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `taglines` WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'tagline_delete', 'Deleted tagline id ' . $id);
        }
        return (bool) $ok;
    }
}
