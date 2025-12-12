<?php

namespace Gift\Impl;

interface ErrorHandlerInterface
{
    public function handle(string $message): void;
}