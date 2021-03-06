<?php

namespace app\core\form;

use app\core\Model;

class Form
{
    public static function begin($action, $method)
    {
        echo sprintf('<form class="ui form" autocomplete="off" action="%s" method="%s">', $action, $method);
        return new Form();
    }
    public static function end()
    {
        return '</form';
    }
    public function field(Model $model, $attribute, $type, $styleClass)
    {
        return new InputField($model, $attribute, $type, $styleClass);
    }
    public function hiddenField(Model $model, $attribute)
    {
        return new HiddenField($model, $attribute);
    }
    public function toggleField(Model $model, $attribute)
    {
        return new ToggleField($model, $attribute);
    }
    public function dropdownField(Model $model, $attribute, $items)
    {
        return new DropdownField($model, $attribute, $items);
    }
}
