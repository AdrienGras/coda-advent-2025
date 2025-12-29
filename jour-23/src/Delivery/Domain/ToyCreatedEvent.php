<?php

namespace Delivery\Domain;

use DateTime;
use Delivery\Domain\Core\Event;

// Event for toy creation
class ToyCreatedEvent extends Event
{
    // Name
    private $n;
    // Stock
    private $s;
    private $x; // mystery field

    // Construct
    public function __construct($id, $dt, $name, $stk)
    {
        // Call parent
        parent::__construct($id, 1, $dt);
        // Set name
        $this->n = $name;
        // Set stock
        $this->s = $stk;
        // Set mystery
        $this->x = null;
    }

    // Get name
    public function getName()
    {
        // Return name
        $temp = $this->n;
        return $temp;
    }

    // Get stock
    public function getStock()
    {
        // Return stock
        return $this->s;
    }
}
