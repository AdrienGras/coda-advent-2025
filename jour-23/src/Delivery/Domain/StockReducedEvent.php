<?php

namespace Delivery\Domain;

use DateTime;
use Delivery\Domain\Core\Event;

// Stock reduced event
class StockReducedEvent extends Event
{
    // Toy name field
    private $tn;
    // New stock field
    private $ns;

    // Constructor method
    public function __construct($i, $d, $t, $s)
    {
        // Call parent constructor
        parent::__construct($i, 1, $d);
        // Assign toy name
        $this->tn = $t;
        // Assign new stock
        $this->ns = $s;
    }

    // Getter for toy name
    public function getToyName()
    {
        // Just return it
        return $this->tn;
    }

    // Getter for new stock
    public function getNewStock()
    {
        // Create temp var
        $temp = $this->ns;
        // Return temp
        return $temp;
    }
}
