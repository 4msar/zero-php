<?php

namespace App\Core;

/**
 * Validation
 */
class Validation
{
    protected $errors = [];
    protected $validated = false;
    protected $rules = [];
    protected $messages = [];
    protected $data = [];

    public $patterns = array(
        'uri'           => '[A-Za-z0-9-\/_?&=]+',
        'url'           => '[A-Za-z0-9-:.\/_?&=#]+',
        'alpha'         => '[\p{L}]+',
        'words'         => '[\p{L}\s]+',
        'alphanum'      => '[\p{L}0-9]+',
        'int'           => '[0-9]+',
        'float'         => '[0-9\.,]+',
        'tel'           => '[0-9+\s()-]+',
        'text'          => '[\p{L}0-9\s-.,;:!"%&()?+\'°#\/@]+',
        'file'          => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+\.[A-Za-z0-9]{2,4}',
        'folder'        => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+',
        'address'       => '[\p{L}0-9\s.,()°-]+',
        'date_dmy'      => '[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}',
        'date_ymd'      => '[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}',
        'email'         => '[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+[.]+[a-z-A-Z]'
    );

    function __construct($rules = [], $data = [])
    {
        $this->rules = $rules;
        $this->data = $data;
    }

    public function validate($messages = [])
    {
        if ($messages) {
            $this->messages = $messages;
        }

        foreach ($this->rules as $name => $textRule) {
            $singlerules = $this->getRulesOf($name);
            foreach ($singlerules as $rule) {
                $this->checkSingleRules($name, $rule);
            }
        }
        $this->validated = true;
        return $this;
    }

    public function validated()
    {
        if (!$this->validated) {
            $this->validate();
        }
        return count($this->errors) <= 0;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setErrorMessage($name, $message)
    {
        if (isset($this->errors[$name])) {
            $this->errors[$name][] = $message;
        } else {
            $this->errors[$name] = [$message];
        }
    }

    public function checkSingleRules($name, $rule)
    {
        $defaults = ['', ''];
        list($ruleName, $ruleParams) = explode(":", $rule) + $defaults;
        if (method_exists($this, "rule_{$ruleName}")) {
            $method = "rule_{$ruleName}";
            $params = explode(',', $ruleParams);
            if (!$this->$method($name, ...$params)) {
                return false;
            }
        }
        return true;
    }

    public function getRulesOf($name)
    {
        if (!isset($this->rules[$name])) {
            return [];
        }
        return explode('|', $this->rules[$name]);
    }

    public function getMessageKey($name, $key)
    {
        return "{$name}.{$key}";
    }

    public function getMessage($key, $defaultMsg = "")
    {
        if (!isset($this->messages[$key])) {
            return $defaultMsg;
        }
        return $this->messages[$key];
    }

    /**
     * ========================= START THE RULES SECTION =========================
     */

    public function rule_required($name)
    {
        if (!isset($this->data[$name]) || empty($this->data[$name])) {
            $this->setErrorMessage(
                $name,
                $this->getMessage(
                    $this->getMessageKey($name, 'required'),
                    "{$name} is required"
                )
            );
            return false;
        }

        return empty($this->data[$name]);
    }

    public function rule_email($name)
    {
        if (!isset($this->data[$name])) {
            return false;
        }
        if (!filter_var($this->data[$name], FILTER_VALIDATE_EMAIL)) {
            $this->setErrorMessage(
                $name,
                $this->getMessage(
                    $this->getMessageKey($name, 'email'),
                    "{$name} is must be an email"
                )
            );
            return false;
        }
        return true;
    }
    public function rule_integer($name)
    {
        if (!isset($this->data[$name])) {
            return false;
        }
        if (!filter_var($this->data[$name], FILTER_VALIDATE_INT)) {
            $this->setErrorMessage(
                $name,
                $this->getMessage(
                    $this->getMessageKey($name, 'integer'),
                    "{$name} is must be an integer"
                )
            );
            return false;
        }
        return true;
    }

    public function rule_min($name, $minValue)
    {
        if (!isset($this->data[$name])) {
            return false;
        }
        if (!($this->data[$name] >= (int) $minValue)) {
            $this->setErrorMessage(
                $name,
                $this->getMessage(
                    $this->getMessageKey($name, 'min'),
                    "{$name} must be greater than {$minValue}"
                )
            );
            return false;
        }
        return true;
    }

    public function rule_date($name)
    {
        if (!isset($this->data[$name])) {
            return false;
        }
        if (!strtotime($this->data[$name])) {
            $this->setErrorMessage(
                $name,
                $this->getMessage(
                    $this->getMessageKey($name, 'date'),
                    "{$name} must be a date"
                )
            );
            return false;
        }
        return true;
    }

    /**
     * ========================= END THE RULES SECTION =========================
     */


    /**
     * Custom
     * https://github.com/davidecesarano/Validation/blob/master/Validation.php
     */
    public function pattern($name)
    {
        $regex = '/^(' . $this->patterns[$name] . ')$/u';
        if (!isset($this->data[$name])) {
            return false;
        }
        if (!preg_match($regex, $this->data[$name])) {
            $this->setErrorMessage(
                $name,
                $this->getMessage(
                    $this->getMessageKey($name, $name),
                    "{$name} must be a {$name}"
                )
            );
            return false;
        }
    }

    public static function is_int($value)
    {
        if (filter_var($value, FILTER_VALIDATE_INT)) return true;
    }

    public static function is_float($value)
    {
        if (filter_var($value, FILTER_VALIDATE_FLOAT)) return true;
    }

    public static function is_alpha($value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z]+$/")))) return true;
    }

    public static function is_alphanum($value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")))) return true;
    }

    public static function is_url($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) return true;
    }

    public static function is_uri($value)
    {
        if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[A-Za-z0-9-\/_]+$/")))) return true;
    }

    public static function is_bool($value)
    {
        if (is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) return true;
    }

    public static function is_email($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) return true;
    }
}
