<?php

namespace Eloquent\Attributes;

use Eloquent\Attributes\Exceptions\ImmutableFieldViolationException;

trait Immutability
{
    /**
     * Each model should populate this array with their specific immutable fields.
     * @var array
     */
    protected $immutable = []; 

    /**
     * Registers a saving event, that will fire an exception if an immutable field got past, and is trying to save when dirty.
     * @return null
     */
    protected static function bootImmutability()
    {
        static::saving(function ($model) {
            $primaryKey      = $model->primaryKey;
            $primaryKeyValue = @$model->attributes[$primaryKey];

            // Only check, if we have a primary key value set
            if( !empty($primaryKeyValue) ) {
                foreach( $model->immutable as $attribute ) {
                    if( $model->isDirty($attribute) ) {
                        throw new ImmutableFieldViolationException("Immutable attribute `$attribute` may not be changed.");
                    }
                }
            }
        });
    }

    /**
     * Catch all before setters are called, to ensure we aren't setting the value for an immutable attribute.
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        $primaryKey      = $this->primaryKey;
        $primaryKeyValue = @$this->attributes[$primaryKey];

        if( !empty($primaryKeyValue) && in_array($key, $this->immutable) ) {
            throw new ImmutableFieldViolationException("Immutable attribute `$key` may not be changed.");
        }

        return parent::setAttribute($key, $value);
    }
}

