<?php

namespace Resque;

use Resque\Job;

class Resque
{
  public static $redis = null;

  public static function redis($server = 'localhost:6379')
  {
    if (self::$redis) return self::$redis;

    if (is_string($server)) {
      list($hostname, $port) = explode(':', $server);
      self::$redis = new \Redis();
      self::$redis->pconnect($hostname, $port);
    }

    if (is_object($server) && get_class($server) === "Redis") {
      self::$redis = ($server->ping()) ? $server : self::redis();
    }

    self::$redis->setOption(\Redis::OPT_PREFIX, 'resque:');

    return self::$redis;
  }

  public static function push($queue, $item)
  {
    self::watch_queue($queue);
    self::$redis->rPush("queue:{$queue}", json_encode($item));
  }

  public static function pop($queue)
  {
    return json_decode(self::$redis->lPop("queue:{$queue}"), true);
  }

  public static function size($queue)
  {
    return (int) self::$redis->lSize($queue);
  }

  public static function queues()
  {
    return self::$redis->sMembers('queues');
  }

  public static function remove_queue($queue)
  {
    self::$redis->sRem("queues", $queue);
    self::$redis->del("queue:{$queue}");
  }

  public static function watch_queue($queue)
  {
    self::$redis->sAdd("queues", $queue);
  }
  
  public static function enqueue($klass, $args)
  {
    Job::create(self::queue_from_class($klass), $klass, $args);
  }

  public static function dequeue($klass, $args)
  {
    Job::destroy(self::queue_from_class($klass), $klass, $args);
  }

  public static function queue_from_class($klass)
  {
    $vars    = get_class_vars($klass);
    $methods = get_class_methods($klass);
    
    if (array_key_exists('queue', $vars)) {
      return $vars['queue'];
    }

    if (in_array('queue', $methods)) {
      return call_user_func(array($klass, 'queue'));
    }
  }

  public static function reserve($queue)
  {
    Job::reserve($queue);
  }

  public static function keys()
  {
    $keys = array();
    foreach (self::$redis->keys("*") as $key) {
      $keys[] = strreplace(self::$redis->getOption(\Redis::OPT_PREFIX), '', $key);
    }
    return $keys;
  }
}
