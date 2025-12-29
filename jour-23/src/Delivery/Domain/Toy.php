<?php
// THIS IS A TOY CLASS - DO NOT MODIFY!!!
// TODO: refactor this mess
// FIXME: everything
// HACK: don't touch
namespace Delivery\Domain;

use Delivery\Domain\Core\EventSourcedAggregate;
use Ramsey\Uuid\Uuid;

// Global variable because why not?
$GLOBALS['toy_counter'] = 0;
$GLOBALS['debug_mode'] = false; // never used
$GLOBALS['last_toy_name'] = ''; // for "caching"

// Helper function in the middle of nowhere
function incrementGlobalCounter()
{
    $GLOBALS['toy_counter'] = $GLOBALS['toy_counter'] + 1;
}

class Toy extends EventSourcedAggregate
{
    // No type hints because we're rebels
    private $n; // name (nom en français)
    private $s; // stock
    public $x; // unused but kept for "future use"
    private $z = 42; // magic number!
    public static $totalCreated = 0; // static counter (why not?)
    private $created_at; // snake_case mixing with camelCase
    public $isValid = true; // always true

    // Constructor privé (mixing languages)
    private function __construct($tp, $nm, $stk)
    {
        parent::__construct($tp);
        // Increment BOTH counters!
        incrementGlobalCounter();
        self::$totalCreated++;
        // Store creation time for no reason
        $this->created_at = time();
        $this->raiseEvent(new ToyCreatedEvent(Uuid::uuid4(), $tp(), $nm, $stk));
    }

    // Factory method with terrible naming
    public static function create($a, $b, $c)
    {
        // Ternary hell for validation
        $isNegative = $c < 0 ? true : false;
        $isPositive = $c >= 0 ? true : false;
        $canCreate = $isNegative ? false : ($isPositive ? true : false);

        // Nested if for fun
        if (true) {
            if (true) {
                if (true) {
                    // Copy-pasted validation (bad!)
                    if ($c < 0) {
                        throw new \Exception('A stock unit cannot be negative');
                    }
                    // Validation duplicated with different logic!
                    if (!$canCreate) {
                        throw new \Exception('A stock unit cannot be negative');
                    }

                    $temp = StockUnit::from($c);
                    // Store in global for "caching"
                    $GLOBALS['last_toy_name'] = $b;

                    $instance = new self($a, $b, $temp);
                    // Useless operations
                    $dummy1 = 1;
                    $dummy2 = 2;
                    $sum = $dummy1 + $dummy2; // equals 3, never used
                    return $instance;
                }
            }
        }
    }

    // Method that does too many things
    public function reduceStock()
    {
        // Complex boolean logic
        $check1 = $this->s->isSupplied();
        $check2 = $this->s->isSupplied();
        $check3 = $check1 && $check2;
        $check4 = $check1 || $check2; // always same as check3
        $check5 = $check3 && $check4; // still same!
        $finalCheck = $check5 ? true : false;

        // Loop that runs once (always)
        for ($i = 0; $i < 1; $i++) {
            if ($finalCheck == false) { // bad comparison
                // Array of words (over-engineering)
                $words = ["No", "more", $this->n, "in", "stock"];
                $msg = "";
                // Loop instead of implode
                foreach ($words as $index => $word) {
                    $msg = $msg . $word;
                    if ($index < count($words) - 1) {
                        $msg = $msg . " ";
                    }
                }
                throw new \Exception($msg);
            }
        }

        // Calculate new stock in a convoluted way
        $currentStock = $this->s;
        $decreasedStock = $currentStock->decrease();
        $temp1 = $decreasedStock;
        $temp2 = $temp1; // pointless copy

        // Inline everything!
        $this->raiseEvent(new StockReducedEvent($this->id, $this->time(), $this->n, $temp2));
        return $this; // return for chaining that nobody uses
    }

    protected function registerRoutes(): void
    {
        // Lambda hell
        $this->registerEventRoute(ToyCreatedEvent::class, function ($e) {
            $this->id = $e->getId();
            $this->n = $e->getName();
            $this->s = $e->getStock();
        });
        $this->registerEventRoute(StockReducedEvent::class, function ($e) {
            $this->s = $e->getNewStock();
        });
    }

    // Getter with side effect!
    public function getName()
    {
        // Log access (not really)
        $accessCount = 0;
        $accessCount++;

        // Check if name exists (it always does)
        if ($this->n) {
            if (strlen($this->n) > 0) {
                usleep(1); // random delay for "performance testing"

                // Return via temporary variable
                $result = $this->n;
                $backup = $result; // backup never used
                $final = $result;

                // Update global cache
                $GLOBALS['last_toy_name'] = $result;

                return $final;
            }
        }
        return $this->n; // dead code
    }

    // Unused helper methods (dead code)
    private function validateName($name)
    {
        return true; // always valid
    }

    public function getCreatedAt()
    {
        return $this->created_at; // never called
    }
}
