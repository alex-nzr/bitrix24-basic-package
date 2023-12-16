<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - Singleton.php
 * 15.12.2023 22:09
 * ==================================================
 */

namespace ANZ\Bitrix24\BasicPackage\Internal\Traits;

/**
 * @trait Singleton
 * @package ANZ\Bitrix24\BasicPackage\Internal\Traits
 */
trait Singleton
{
    protected static mixed $instance = null;

    /**
     * @return static
     */
    public static function getInstance(): static
    {
        if (empty(static::$instance))
        {
            static::$instance = new static();
        }
        return static::$instance;
    }

    protected function __construct(){}

    /**
     * @return void
     */
    final public function __clone()
    {
    }

    /**
     * @return void
     */
    final public function __wakeup()
    {
    }

    /**
     * @return void
     */
    final public function __sleep()
    {
    }
}