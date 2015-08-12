<?php
namespace Caffeinated\Beverage\Traits;

/**
 * Dot Array Access Trait
 *
 * @author    Caffeinated Dev Team
 * @copyright Copyright (c) 2015, Caffeinated
 * @license   https://tldrlegal.com/license/mit-license MIT License
 * @package   Caffeinated\Beverage
 */
trait DotArrayAccess
{
    /**
     * Get array accessor.
     *
     * @return mixed
     */
    abstract protected function getArrayAccessor();

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_has($this{$this->getArrayAccessor()}, $key);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return array_get($this->{$this->getArrayAccessor()}, $key);
    }

    /**
     * Set the item at a given offset.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return $this
     */
    public function offsetSet($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $innerKey => $innerValue) {
                array_set($this->{$this->getArrayAccessor()}, $innerKey, $innerValue);
            }
        } else {
            array_set($this->{$this->getArrayAccessor()}, $key, $value);
        }

        return $this;
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  string  $key
     * @return $this
     */
    public function offsetUnset($key)
    {
        array_set($this->{$this->getArrayAccessor()}, $key, null);

        return $this;
    }
}
