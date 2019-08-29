<?php

namespace App\Repositories;

class SessionRepository
{
    public function has($key)
    {
        return array_key_exists($key, $_SESSION);
    }

    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function forget($key)
    {
        unset($_SESSION[$key]);
    }
}
