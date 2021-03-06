<?php

namespace app\core\form;

use app\core\form;
use app\core\Model;
use app\core\form\BaseField;

class InputField extends BaseField
{
    public Model $model;
    public string $attribute;
    public string $type;
    public string $classStyle = '';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_HIDDEN = 'hidden';
    public const TYPE_TEXT = 'text';
    public const TYPE_NUMBER = 'number';
    public const TYPE_EMAIL = 'email';
    public function __construct(Model $model, string $attribute, string $type, string $classStyle)
    {
        parent::__construct($model, $attribute);
        $this->type = $type;
        $this->classStyle = $classStyle;
    }
    public function renderInput(): string
    {
        return sprintf(
            '<input type="%s" name="%s" id="%s" value="%s" placeholder="%s" class="ui input %s" required></input>',
            $this->type,
            $this->attribute,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->getLabel($this->attribute),
            $this->classStyle
        );
    }
    public function emailField()
    {
        $this->type = self::TYPE_EMAIL;
        return $this;
    }
    public function hiddenField()
    {
        $this->type = self::TYPE_HIDDEN;
        return $this;
    }
    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }
}
