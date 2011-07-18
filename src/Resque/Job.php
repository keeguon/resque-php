<?php

namespace Resque;

class Job
{
  public $queue;
  public $payload;

  public function __construct($queue, $payload)
  {
    $this->queue   = $queue;
    $this->payload = $payload;
  }

  public static function create($queue, $class, $args = null)
  {
    Resque::push($queue, array('class' => $class, 'args' => $args));
  }
}
