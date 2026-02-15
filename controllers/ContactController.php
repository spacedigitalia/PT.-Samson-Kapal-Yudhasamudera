<?php

class ContactController
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
        $stmt = $this->db->prepare("SELECT id, company_name, building_name, floor, district, street_address, city, postal_code, country, phone, email, website, description, image_url, user_id, created_at, updated_at FROM `contact` ORDER BY id DESC");
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
        $stmt = $this->db->prepare("SELECT c.id, c.company_name, c.building_name, c.floor, c.district, c.street_address, c.city, c.postal_code, c.country, c.phone, c.email, c.website, c.description, c.image_url, c.user_id, c.created_at, c.updated_at, a.fullname FROM `contact` c LEFT JOIN `accounts` a ON c.user_id = a.id WHERE c.id = ? LIMIT 1");
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
        $stmt = $this->db->prepare("SELECT c.id, c.company_name, c.building_name, c.floor, c.district, c.street_address, c.city, c.postal_code, c.country, c.phone, c.email, c.website, c.description, c.image_url, c.user_id, c.created_at, c.updated_at, a.fullname FROM `contact` c LEFT JOIN `accounts` a ON c.user_id = a.id ORDER BY c.id DESC LIMIT 1");
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function create(
        string $companyName,
        ?string $buildingName,
        ?string $floor,
        ?string $district,
        ?string $streetAddress,
        ?string $city,
        ?string $postalCode,
        ?string $country,
        ?string $phone,
        ?string $email,
        ?string $website,
        ?string $description,
        ?string $imageUrl,
        ?int $userId
    ): bool {
        $stmt = $this->db->prepare("INSERT INTO `contact` (company_name, building_name, floor, district, street_address, city, postal_code, country, phone, email, website, description, image_url, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssssssssssi', $companyName, $buildingName, $floor, $district, $streetAddress, $city, $postalCode, $country, $phone, $email, $website, $description, $imageUrl, $userId);
        $ok = $stmt->execute();
        if ($ok) {
            $this->log($userId, 'contact_create', 'Created: ' . $companyName);
        }
        $stmt->close();
        return (bool) $ok;
    }

    public function update(
        int $id,
        string $companyName,
        ?string $buildingName,
        ?string $floor,
        ?string $district,
        ?string $streetAddress,
        ?string $city,
        ?string $postalCode,
        ?string $country,
        ?string $phone,
        ?string $email,
        ?string $website,
        ?string $description,
        ?string $imageUrl,
        ?int $userId
    ): bool {
        $stmt = $this->db->prepare("UPDATE `contact` SET company_name = ?, building_name = ?, floor = ?, district = ?, street_address = ?, city = ?, postal_code = ?, country = ?, phone = ?, email = ?, website = ?, description = ?, image_url = ?, user_id = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param('sssssssssssssii', $companyName, $buildingName, $floor, $district, $streetAddress, $city, $postalCode, $country, $phone, $email, $website, $description, $imageUrl, $userId, $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'contact_update', 'Updated id ' . $id);
        }
        return (bool) $ok;
    }

    public function delete(int $id, ?int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `contact` WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        if ($ok) {
            $this->log($userId, 'contact_delete', 'Deleted id ' . $id);
        }
        return (bool) $ok;
    }
}
