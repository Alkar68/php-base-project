<?php

namespace app\Entities;

use app\Abstract\AbstractEntity;
use DateTime;

class User extends AbstractEntity
{
    private ?string $email = null;
    private ?string $password = null;
    private ?string $firstname = null;
    private ?string $lastname = null;
    private ?int $role_id = null;
    private bool $is_active = true;
    private ?DateTime $email_verified_at = null;
    private ?string $password_reset_token = null;
    private ?DateTime $password_reset_expires_at = null;
    private ?DateTime $last_login_at = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getFullName(): string
    {
        return trim($this->firstname . ' ' . $this->lastname);
    }

    public function getRoleId(): ?int
    {
        return $this->role_id;
    }

    public function setRoleId(?int $role_id): self
    {
        $this->role_id = $role_id;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;
        return $this;
    }

    public function getEmailVerifiedAt(): ?DateTime
    {
        return $this->email_verified_at;
    }

    public function setEmailVerifiedAt(?DateTime $email_verified_at): self
    {
        $this->email_verified_at = $email_verified_at;
        return $this;
    }

    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function getPasswordResetToken(): ?string
    {
        return $this->password_reset_token;
    }

    public function setPasswordResetToken(?string $password_reset_token): self
    {
        $this->password_reset_token = $password_reset_token;
        return $this;
    }

    public function getPasswordResetExpiresAt(): ?DateTime
    {
        return $this->password_reset_expires_at;
    }

    public function setPasswordResetExpiresAt(?DateTime $password_reset_expires_at): self
    {
        $this->password_reset_expires_at = $password_reset_expires_at;
        return $this;
    }

    public function getLastLoginAt(): ?DateTime
    {
        return $this->last_login_at;
    }

    public function setLastLoginAt(?DateTime $last_login_at): self
    {
        $this->last_login_at = $last_login_at;
        return $this;
    }
}
