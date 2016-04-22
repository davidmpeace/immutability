<?php

namespace Eloquent\Attributes;

use Eloquent\Attributes\Exceptions\ImmutableFieldViolationException;

trait Immutability
{
    /**
     * Registers a saving event, that will fire an exception if an immutable field got past our setter validation, 
     * and is trying to save the changed value.
     * 
     * @return null
     */
    protected static function bootImmutability()
    {
        static::saving(function ($model) {
            $immutable = $model->getImmutableAttributes();

            foreach( $immutable as $attribute ) {
                if( $model->violatesImmutability($attribute) ) {
                    throw new ImmutableFieldViolationException("`".get_class($model)."` Save Exception: Immutable attribute `$attribute` may not be changed.");
                }
            }
        });
    }

    /**
     * This catch all will be called before any other setters are called, to ensure we aren't setting the value for 
     * an immutable attribute.
     * 
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        if( $this->violatesImmutability($key, $value) ) {
            throw new ImmutableFieldViolationException("Immutable attribute `$key` may not be changed.");
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Checks the model for $immutable attributes array, and returns an array of immutable attribute names.
     * 
     * @return array
     */
    protected function getImmutableAttributes()
    {
        $immutable = (isset($this->immutable)) ? $this->immutable : [];
        return $immutable;
    }

    /**
     * Returns true if an attribute is immutable.
     * 
     * @param  string  $attribute
     * @return boolean
     */
    protected function isImmutable( $attribute )
    {
        $immutable   = $this->getImmutableAttributes();
        $isImmutable = in_array($attribute, $immutable);
        return $isImmutable;
    }

    /**
     * Returns true if an attribute has been set erroneously, and violated immutability.
     * 
     * @param  string $attribute
     * @param  mixed  $newValue     This is only passed, if the value has not been set on the model yet.
     * @return boolean
     */
    protected function violatesImmutability( $attribute, $newValue = null )
    {
        $violated = false;

        if( $this->exists && $this->isImmutable($attribute) && !$this->bypassImmutabilityCheck($attribute) ) {
            if( func_num_args() == 2 ) {
                if( array_key_exists($attribute, $this->attributes) ) {
                    $currentValue = array_get($this->attributes, $attribute);
                    $violated     = ($newValue != $currentValue);
                }
            } else {
                $violated = $this->isDirty($attribute);
            }
        }

        return $violated;
    }

    /**
     * This method can be used, to selectively bypass immutability checks, on a per attribute basis.  By default,
     * it just returns false.  If it returns true, we will allow the attribute to be set.
     * 
     * @param  string $attribute
     * @return boolean
     */
    protected function bypassImmutabilityCheck( $attribute )
    {
        return false;
    }
}