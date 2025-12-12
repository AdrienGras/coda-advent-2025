<?php

namespace Gift;

use Gift\Impl\ErrorHandlerInterface;
use Gift\Impl\LoggerInterface;

class ErrorHandler implements ErrorHandlerInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(string $message): void
    {
        $this->logger->log("🚨 ERREUR CRITIQUE 🚨");
        $this->logger->log("❌ $message");
        $this->logger->log("🔴 Merci de respecter les principes SOLID");
    }
}