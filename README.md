# Resque PHP

Resque (pronounced like "rescue") is a Redis-backed library for creating background jobs, placing those jobs on multiple queues, and processing them later.

This is port of the existing Ruby library and still in the early stages, the code is not documented yet and you can't do much except enqueue jobs. If you want to dequeue them you'll have to use the Ruby library for now and write your jobs in Ruby.

You're welcome to contribute if you have a little bit of time because I basically did what I really needed at my job and I will continue to port the Ruby package but don't expect a full port tomorrow since most of the time I'm pretty busy...

## Requirements

* Redis (obviously)
* [phpredis](https://github.com/nicolasff/phpredis)
