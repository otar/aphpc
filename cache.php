<?php

/*
 * The Steel Cache
 * APC cache wrapper for PHP
 *
 * Copyright (c) 2011 Otar Chekurishvili
 * http://twitter.com/ochekurishvili
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */

class Cache_Exception extends Exception
{

}

/**
 * APC cache wrapper for PHP
 * @author Otar Chekurishvili <otar@chekurishvili.com>
 * @link http://twitter.com/ochekurishvili Follow author on Twitter
 * @license http://www.opensource.org/licenses/mit-license The MIT License
 * @version 1.0
 */
class Cache
{
    const EXPIRE = 3600;

    private static $instance = NULL;

    /**
     * Get instance of the Cache class.
     * @return object
     */
    public static function instance()
    {
        NULL === self::$instance AND self::_initialize();
        return self::$instance;
    }

    /**
     * Get item from the cache.
     * @param string $key Unique item ID.
     * @param mixed $default Value to be returned in case of failure, default is boolean FALSE.
     * @return mixed
     */
    public function get($key, $default = FALSE)
    {
        $cache = apc_fetch($this->_sanitize($key), $success);
        return $success ? $cache : $default;
    }

    /**
     * Quickly retrieve item from the cache.
     * @param string $key Unique item ID.
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Set item in the cache for the limited time.
     * @param string $key Unique item ID.
     * @param mixed $value Value to be stored in the cache.
     * @param integer $expire Cache lifetime in miliseconds, default is 3600 (1 hour).
     * @return boolean
     */
    public function set($key, $value, $expire = NULL)
    {
        NULL === $expire AND $expire = self::EXPIRE;
        return apc_store($this->_sanitize($key), $value, $expire);
    }

    /**
     * Quickly set item in the cache for the default lifetime (1 hour).
     * @param string $key Unique item ID.
     * @param mixed $value Value to be stored in the cache.
     * @return boolean
     */
    public function __set($key, $value)
    {
        return $this->set($key, $value);
    }

    /**
     * Delete item from the cache.
     * @param string $key Unique item ID.
     * @return boolean
     */
    public function delete($key)
    {
        return apc_delete($this->_sanitize($key));
    }

    /**
     * Quickly delete item from the cache using PHP's unset() function.
     * @param string $key Unique item ID.
     * @return boolean
     */
    public function __unset($key)
    {
        return $this->delete($key);
    }

    /**
     * Empty all saved data from the cache.
     * @return boolean
     */
    public function clean()
    {
        return apc_clear_cache('user');
    }

    /**
     * Check for APC extension availability and create an instance.
     */
    protected static function _initialize()
    {
        if (!extension_loaded('apc') OR !function_exists('apc_store'))
            throw new Cache_Exception('Cache class requires PHP\'s native `APC` extension which is not installed or loaded.');
        self::$instance = new self;
    }

    /**
     * Replace slashes and spaces with underscores for creating more stable cache item ID.
     * @param string $key
     * @return string
     */
    protected function _sanitize($key)
    {
        if (!is_string($key))
            throw new Cache_Exception('Cache key should be an unique string, no other types allowed.');
        return str_replace(array('/', '\\', ' '), '_', trim($key));
    }

    private function __construct()
    {
        throw new Cache_Exception('Directly creating the Cache object is not allowed, try Cache::instance() instead.');
    }

    private function __clone()
    {
        throw new Cache_Exception('Cloning the Cache objectis not allowed.');
    }

}
