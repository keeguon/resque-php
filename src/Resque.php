<?php

namespace Resque;

class Resque
{
  public static $redis = null;

  public static function redis($server = 'localhost:6379')
  {
    if (self::$redis) return self::$redis;

    if (is_string($redis)) {
      list($hostname, $port) = explode(':', $server);
      self::$redis = new Redis();
      self::$redis->pconnect($hostname, $port);
    }

    if (is_object($redis) && get_class($redis) === "Redis") {
      self::$redis = ($server->ping()) ? $server : self::redis();
    }

    self::$redis->setOption(Redis::OPT_PREFIX, 'resque:');

    return self::$redis;
  }

  public static function push($queue, $item)
  {
    self::$redis->sAdd("queues", $queue);
    self::$redis->rPush("queue:{$queue}", json_encode($item));
  }

  public static function pop($queue)
  {
    return json_decode(self::$redis->lPop("queue:{$queue}"), true);
  }
  
  public static function enqueue($job, $args)
  {
  }
}
