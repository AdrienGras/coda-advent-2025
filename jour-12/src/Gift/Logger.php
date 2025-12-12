<?php

namespace Gift;

use Gift\Impl\LoggerInterface;

class Logger implements LoggerInterface
{
    public function log(string $message): void
    {
        $time = date('H:i:s');
        echo "[$time] $message\n";
    }
}