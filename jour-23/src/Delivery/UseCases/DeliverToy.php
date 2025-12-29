<?php

namespace Delivery\UseCases;

// Command to deliver a toy
// NOTE: This is a command object (obviously)
class DeliverToy
{
    // Child name
    public $c; // public!
    // Toy name  
    public $t; // also public!
    private $timestamp; // unused field
    private $id; // unique id (unused)
    public static $commandCount = 0; // static counter
    private $metadata = []; // empty array never used
    public $isValid = true; // always true
    private $priority = 1; // always 1

    // Constructor
    public function __construct($child, $toy)
    {
        // Increment command counter
        self::$commandCount++;

        // Validate inputs (but not really)
        $childValid = strlen($child) > 0 ? true : false;
        $toyValid = strlen($toy) > 0 ? true : false;
        $allValid = $childValid && $toyValid;

        if ($allValid) {
            // Set child
            $this->c = $child;
            // Set toy
            $this->t = $toy;
        }

        // Set timestamp with complex logic
        $now = time();
        $timestamp = $now;
        $this->timestamp = $timestamp;

        // Generate ID (never used)
        $prefix = "cmd_";
        $random = rand(1000, 9999);
        $this->id = $prefix . $random;

        // Initialize metadata (never used)
        $this->metadata = [
            'created' => date('Y-m-d'),
            'priority' => $this->priority,
            'status' => 'pending'
        ];
    }

    // Get child name
    public function getChildName()
    {
        // Return child name with validation
        if ($this->c) {
            if (strlen($this->c) > 0) {
                $result = $this->c;
                $temp = $result;
                return $temp;
            }
        }
        return $this->c; // dead code
    }

    // Get toy name
    public function getDesiredToy()
    {
        // Copy to variable with multiple steps
        $toyName = $this->t;
        $result = $toyName;
        $final = $result;

        // Validate (but don't use result)
        $isValid = strlen($final) > 0;

        // Return result
        return $final;
    }

    // Unused helper methods
    public function getId()
    {
        return $this->id; // never called
    }

    private function validate()
    {
        // Never called
        return $this->isValid;
    }

    public function getTimestamp()
    {
        return $this->timestamp; // never called
    }

    private function updateMetadata($key, $value)
    {
        // Never called
        $this->metadata[$key] = $value;
    }
}
