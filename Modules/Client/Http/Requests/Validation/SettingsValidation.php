<?php

namespace Modules\Client\Http\Requests\Validation;

use Astrotomic\Translatable\Locales;
use BenSampo\Enum\Rules\EnumValue;
use Modules\Client\Enums\SettingsKeys;

class SettingsValidation
{
    private $settings;
    const SETTINGS_PROPERTY_NAME = 'settings';
    const KEY_PROPERTY_NAME = 'key';
    const VALUE_PROPERTY_NAME = 'value';

    public function __construct($settings = [])
    {
        $this->settings = $settings;
    }

    public function getRules()
    {
        return   array_merge([
            self::SETTINGS_PROPERTY_NAME => $this->getGeneralSettingsRules(),
            self::SETTINGS_PROPERTY_NAME . '.*.' . self::KEY_PROPERTY_NAME => $this->getKeysRules(),
        ], $this->getValuesRules() ?? []);
    }

    private function getGeneralSettingsRules()
    {
        return ['required', 'array', 'min:1'];
    }

    private  function getKeysRules()
    {
        return ['required', new EnumValue(SettingsKeys::class), 'distinct'];
    }

    private  function getValuesRules()
    {
        $valueRules = [];
        if (!is_array($this->settings)) {
            return $valueRules;
        }
        foreach ($this->settings as $i => $value) {
            if (!(is_array($value) && array_key_exists(self::KEY_PROPERTY_NAME, $value) &&  SettingsKeys::hasValue($value['key']))) {
                continue;
            }
            $rules = $this->getValueRulesByKey($value[self::KEY_PROPERTY_NAME]);
            if (is_array($rules)) {
                $valueRules[self::SETTINGS_PROPERTY_NAME . ".$i." . self::VALUE_PROPERTY_NAME] =  $rules;
            }
        }
        return $valueRules;
    }

    private  function getValueRulesByKey($key)
    {
        $methodName = $this->getSettingsValueRulesMethodName($key);
        if (method_exists($this, $methodName)) {
            return $this->$methodName() ?? null;
        }
        return null;
    }


    private  function getSettingsValueRulesMethodName($key)
    {
        // convert snake case to camel case. ex: replace time_zone to timeZone
        $camelCaseKey =  preg_replace_callback('/_([a-z]?)/', function ($match) {
            return strtoupper($match[1]);
        }, $key);
        return 'get' . ucfirst($camelCaseKey) . 'ValueRules';
    }

    private  function getLocaleValueRules()
    {
        $available_locales = app()->get(Locales::class)->all() ?? [];
        $available_locales_string = implode(',', $available_locales) ?? '';
        return ['required', 'max:255', 'in:' . $available_locales_string];
    }

    private  function getTimeZoneValueRules()
    {
        return ['required', 'max:255'];
    }

    private  function getIsSetupWizardFinishedValueRules()
    {
        return ['nullable', 'max:255', 'boolean'];
    }
}
