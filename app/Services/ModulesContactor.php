<?php

namespace App\Services;

class ModulesContactor
{
    /**
     * Contact a Function From Another Service In Specific Modules
     *
     * @param string $service
     * @param string $method
     * @param mixed ...$methodArguments
     * @return mixed
     * @author Mohannad Elemary
     */
    public function contact($service, $method, ...$methodArguments)
    {
        if (!class_exists($service)) {
            return null;
        }

        $result = app($service)->{$method}(...$methodArguments);

        return $result ?? null;
    }

    /**
     * Contact a static Function From Another Service In Specific Modules
     *
     * @param string $service
     * @param string $method
     * @param mixed ...$methodArguments
     * @return mixed
     * @author Mohannad Elemary
     */
    public function contactStatic($service, $method, ...$methodArguments)
    {
        if (!class_exists($service)) {
            return null;
        }

        $result = $service::{$method}(...$methodArguments);

        return $result ?? null;
    }
}
