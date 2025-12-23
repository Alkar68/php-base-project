<?php

namespace app\Abstract;

use app\Core\Logger;
use DateMalformedStringException;
use DateTime;
use RuntimeException;

abstract class AbstractEntity
{
    protected ?int $id = null;
    protected ?DateTime $created_at = null;
    protected ?DateTime $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }

    public function setCreatedAt(?DateTime $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function setUpdatedAt(?DateTime $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function hydrate(array $data): self
    {
        foreach ($data as $key => $value) {
            $method = 'set' . str_replace('_', '', ucwords($key, '_'));

            if (method_exists($this, $method)) {
                if (in_array($key, ['created_at', 'updated_at']) && is_string($value)) {
                    try {
                        $value = new DateTime($value);
                    } catch (DateMalformedStringException $e) {
                        Logger::getInstance()->error('Invalid hydrate date', [
                            'exception' => $e->getMessage(),
                            'config_value' => $_ENV['TOKEN_NEW_PASSWORD_TIMEOUT']
                        ]);
                        throw new RuntimeException('Invalid hydrate date: ' . $e->getMessage(), 0, $e);
                    }
                }
                $this->$method($value);
            }
        }

        return $this;
    }
}
