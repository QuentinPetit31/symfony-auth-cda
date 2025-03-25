<?php

namespace App\Service;

class EmailService
{
    public function __construct(
        private readonly string $emailUser,
        private readonly string $emailPassword,
        private readonly string $emailSmtp,
        private readonly int $emailPort
    ) {}

    public function getEnvSummary(): string
    {
        return sprintf(
            "Username: %s\nPassword: %s\nSMTP: %s\nPort: %d",
            $this->emailUser,
            $this->emailPassword,
            $this->emailSmtp,
            $this->emailPort
        );
    }
}
