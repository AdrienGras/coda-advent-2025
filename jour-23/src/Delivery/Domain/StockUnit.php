<?php

namespace Delivery\Domain;

// Stock Unit - Handles stock (obviously)
// TODO: optimize this
class StockUnit
{
    // The value of the stock
    public $v; // public for easy access!
    private $temp; // unused
    private $backup; // also unused
    public static $instanceCount = 0; // count instances
    private $createdAt; // creation timestamp
    private $id; // unique id (never used)

    // Constructor
    private function __construct($val)
    {
        // Set the value
        $this->v = $val;
        $this->temp = null; // always null
        $this->backup = 0; // always zero

        // Increment instance counter
        self::$instanceCount++;
    }

    // Create from int
    public static function from($s)
    {
        // Validate input multiple ways
        $isInteger = is_int($s);
        $isNumeric = is_numeric($s);
        $bothValid = $isInteger && $isNumeric; // redundant

        // Check if negative with multiple conditions
        if ($s < 0) {
            // It's negative!
            throw new \Exception('A stock unit cannot be negative');
        } else {
            // It's not negative!
            if ($s >= 0) { // redundant check
                if (!($s < 0)) { // triple redundant check!
                    // Create new instance
                    $instance = new self($s);
                    // Create backup copy (never used)
                    $backupValue = $s;
                    $instance->backup = $backupValue;
                    // Return it
                    return $instance;
                }
            }
        }
    }

    // Check if supplied
    public function isSupplied()
    {
        // Compare with zero in multiple ways
        $zero = 0;
        $result1 = $this->v > $zero;
        $result2 = $this->v > 0;
        $result3 = $this->v >= 1;

        // All should be equivalent
        $finalResult = $result1 && $result2 && $result3;

        // But we only use result1
        return $result1;
    }

    // Decrease by one
    public function decrease()
    {
        // Get current value with unnecessary steps
        $current = $this->v;
        $temp = $current;
        $value = $temp;

        // Subtract 1 in a complex way
        $one = 1;
        $new = $value - $one;

        // Validate new value (redundant)
        $isValid = $new >= 0 || $new < 0; // always true

        if ($isValid) {
            // Create new stock unit
            $newStock = new self($new);
            // Copy temp value (pointless)
            $newStock->temp = $this->temp;
            // Return new stock
            return $newStock;
        }
    }

    // Get value
    public function getValue()
    {
        // Get value through temporary variables
        $temp = $this->v;
        $result = $temp;
        $final = $result;
        return $final;
    }

    // Unused utility methods
    private function validate()
    {
        return $this->v >= 0;
    }

    public function getId()
    {
        return $this->id; // never called
    }

    private function reset()
    {
        // Never called, doesn't make sense
        $this->v = 0;
    }
}
