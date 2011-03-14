<?php

class CacheException extends Exception
{

}

class Cache
{
    const EXPIRE = 3600;

    private static $instance = NULL;

    public static function instance()
    {
        NULL === self::$instance AND self::_initialize();
        return self::$instance;
    }

    public function get($key, $default = FALSE)
    {
        $cache = apc_fetch($this->_sanitize($key), $success);
        return $success ? $cache : $default;
    }

    public function set($key, $value, $expire = NULL)
    {
        NULL === $expire AND $expire = self::EXPIRE;
        return apc_store($this->_sanitize($key), $value, $expire);
    }

    public function delete($key)
    {
        return apc_delete($this->_sanitize($key));
    }

    public function clean()
    {
        return apc_clear_cache('user');
    }

    protected static function _initialize()
    {
        if (!extension_loaded('apc') OR !function_exists('apc_store'))
            throw new CacheException('Cache class requires PHP\'s native `APC` extension which is not installed or loaded.');
        self::$instance = new self;
    }

    protected function _sanitize($key)
    {
        return str_replace(array('/', '\\', ' '), '_', $key);
    }

}
