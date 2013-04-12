<?php

namespace WeLovePhp\Items;

class ClickCounter
{
    protected $redis;

    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    public function countClick($id)
    {
        $this->redis->incr('items:clicks:' . $id);
    }

    public function getClicks($id)
    {
        $this->redis->get('items:clicks:' . $id);
    }


}