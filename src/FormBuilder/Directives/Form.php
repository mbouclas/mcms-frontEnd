<?php

namespace Mcms\FrontEnd\FormBuilder\Directives;

use App;
use Blade;
use Mcms\Core\Widgets\Widget;

class Form extends Widget
{
    public static function registerDirective()
    {
        Blade::directive('Form', function($expression) {
            if ( ! is_string($expression)){
                return '';
            }

            $re = '/[\'"]([^"\']*)["\']\s?(?:[,?\s](.*))?/';
            preg_match($re, $expression, $matches);
            $formName = $matches[1];
            //find the form
            $Form = new \Mcms\FrontEnd\FormBuilder\FormBuilderService();
            $form = $Form->bySlug($formName);
            $args = [
                'Form' => $form->toArray(),
                'actionUrl' => $form->getSlug(),
                'locale' => App::getLocale()
            ];
            $evaled = [];

            if (isset($matches[2])){
                eval("\$evaled = $matches[2];");

                $args = array_merge($args, $evaled);
            }

            $args = var_export($args, true);


            $view = (isset($evaled['view']))  ? $evaled['view'] : 'vendor.frontEnd.forms.widget';
            return "<?php echo \$__env->make('{$view}', 
            array_except(get_defined_vars(), ['__data', '__path']))->with({$args})->render(); ?>";


        });
    }
}