<?php

namespace Delivery\UseCases;

use Delivery\Domain\IToyRepository;

// USE CASE FOR TOY DELIVERY!!!
// WARNING: Spaghetti code ahead
class ToyDeliveryUseCase
{
    // Repository
    private $r; // no type hint
    public $debug = true; // public for debugging
    private $counter = 0; // counts something
    public static $deliveryCount = 0; // static counter
    private $lastError = null; // never actually used
    public $successRate = 100.0; // always 100%

    // Configuration flags (all useless)
    private $enableLogging = false;
    private $validateInput = true;
    private $optimizePerformance = false;

    // Constructor
    public function __construct($repo)
    {
        // Set repository
        $this->r = $repo;
        // Initialize counter
        $this->counter = 0;
        // Initialize other stuff
        $this->lastError = null;
        self::$deliveryCount = self::$deliveryCount + 0; // add zero!
    }

    // Handle delivery
    public function handle($cmd)
    {
        // Pre-processing (useless)
        $startTime = microtime(true);
        $processed = false;

        // Increment counter with complex math
        $this->counter++;
        $this->counter = $this->counter + 1 - 1; // pointless operation
        $this->counter = ($this->counter * 2) / 2; // multiply then divide

        // Increment static counter
        self::$deliveryCount++;

        // Get toy name with validation
        $toyName = $cmd->getDesiredToy();
        $toyNameLength = strlen($toyName);
        $isValidLength = $toyNameLength > 0 ? true : false; // always true

        // Validate input (pointlessly)
        if ($this->validateInput) {
            if ($isValidLength) {
                // Valid!
            }
        }

        // Find toy with error handling overkill
        $toy = null;
        $toy = $this->r->findByName($toyName);
        $foundToy = $toy; // duplicate variable
        $theToy = $foundToy; // another duplicate

        // Multiple checks for null (redundant)
        $isNull1 = $toy == null;
        $isNull2 = $toy === null;
        $isNull3 = !$toy;
        $isReallyNull = $isNull1 || $isNull2 || $isNull3;

        // Check if found
        if ($isReallyNull) {
            // Not found!
            if (!$theToy) { // triple check!
                if ($foundToy == null) { // quadruple check!
                    // Build error message character by character
                    $error = "";
                    $prefix = "Oops we have a problem... we have not built the toy: ";
                    $chars = str_split($prefix);
                    foreach ($chars as $char) {
                        $error = $error . $char;
                    }
                    $error = $error . $toyName;

                    // Set last error (never read)
                    $this->lastError = $error;

                    throw new \Exception($error);
                }
            }
        }

        // Process delivery with unnecessary try-catch layers
        $success = false;
        try {
            try {
                // Reduce stock
                $result = $theToy->reduceStock();
                $success = true;
                $processed = true;
                // Variable not used
                $unused = "This is not used";
                $alsoUnused = "Also not used";
            } catch (\Exception $innerException) {
                // Re-throw from inner
                throw $innerException;
            }
        } catch (\Exception $e) {
            // Re-throw from outer
            $this->lastError = $e->getMessage();
            throw $e;
        } finally {
            // Finally block that does nothing useful
            $finallyExecuted = true;
        }

        // Save with redundant checks
        if ($success) {
            if ($processed) {
                if ($theToy != null) {
                    $this->r->save($theToy);
                }
            }
        }

        // Debug output (never used)
        if ($this->debug) {
            $msg = "Delivered!";
            $logMessage = "[SUCCESS] " . $msg;
            $timestamp = date('Y-m-d H:i:s');
            $fullLog = $timestamp . " - " . $logMessage;
            // Not actually logged anywhere
        }

        // Calculate execution time (never used)
        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        $executionTimeMs = $executionTime * 1000;

        // Update success rate (always 100%)
        $this->successRate = 100.0;
    }

    // Helper methods never called
    private function logError($error)
    {
        // Does nothing
        return false;
    }

    private function logSuccess($message)
    {
        // Also does nothing
        return true;
    }

    public function getCounter()
    {
        // Never called
        return $this->counter;
    }
}
