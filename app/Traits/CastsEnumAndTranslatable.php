<?php

namespace App\Traits;

use Astrotomic\Translatable\Translatable;
use BenSampo\Enum\Traits\CastsEnums;

trait CastsEnumAndTranslatable
{
    use CastsEnums, Translatable {
        CastsEnums::setAttribute as enumSetAttribute;
        Translatable::setAttribute as translatableSetAttribute;
    }

    /**
     * Solve Conflict Between The Two Packages In Using setAttribute
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     * @author Mohannad Elemary
     */
    public function setAttribute($key, $value)
    {
        $this->enumSetAttribute($key, $value);
        $this->translatableSetAttribute($key, $value);

        return parent::setAttribute($key, $value);
    }
}
