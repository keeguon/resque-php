<?php

namespace Demo;

class Job
{
  public static function perform($args)
  {
    sleep(1);
    print "Processed a job!";
  }

  public static function queue()
  {
    return "default";
  }
}

class FailingJob
{
  public $queue = "failing";

  public static function perform($args)
  {
    sleep(1);
    throw new Exception('not processable!');
    print "Processed a job!";
  }
}
