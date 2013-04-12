<?php

require_once 'vendor/autoload.php';

use Predis\Command\ScriptedCommand;

class GetFavoriteItemsWithRecentVisitors extends ScriptedCommand
{
    public function getKeysCount()
    {
        return 2;
    }

    public function getScript()
    {
        return
            <<<LUA
local league
league = redis.call('HGET', KEYS[1], ARGV[1])
if not league then
    league = ARGV[2]
    redis.call('HSET', KEYS[1], ARGV[1], league)
end
redis.call('ZINCRBY', KEYS[2]..league, 1, ARGV[1])
LUA;
    }
}