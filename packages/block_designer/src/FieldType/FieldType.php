<?php  namespace RamonLeenders\BlockDesigner\FieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

class FieldType
{
    protected $ftHandle;
    protected $ftDirectory;

    protected $dbType = false;
    protected $canRepeat = false;
    protected $repeating = false;
    protected $btExportFileColumn = false;
    protected $pkgVersionRequired = false;
    protected $appVersionRequired = false;
    protected $requiredSlug = true;
    protected $useBaseFields = true;

    protected $data = array();
    protected $postData = array();
    protected $uses = array();
    protected $helpers = array();

    public function __construct($fieldTypeDirectory, $pkgHandle, $pkgDirectory, $className)
    {
        $this->ftDirectory = $fieldTypeDirectory . DIRECTORY_SEPARATOR;
        $this->pkgHandle = $pkgHandle;
        $this->pkgDirectory = $pkgDirectory;
        $this->className = $className;
    }

    public function getFieldName()
    {
        return t(ucwords(implode(' ', explode('_', $this->ftHandle))));
    }

    public function getFieldDescription()
    {
        return '';
    }

    public function inc($file, $variables = array())
    {
        if (file_exists($file)) {
            ob_start();
            if (is_array($variables) && !empty($variables)) {
                foreach ($variables as $k => $v) {
                    ${$k} = $v;
                }
            }
            include($file);
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
        return;
    }

    public function getTabKey()
    {
        return isset($this->tab) ? $this->tab : false;
    }

    public function view($view, $pkgHandle = 'block_designer', $variables = array())
    {
        return $this->inc('packages' . DIRECTORY_SEPARATOR . $pkgHandle . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'FieldType' . DIRECTORY_SEPARATOR . $this->className . DIRECTORY_SEPARATOR . $view, $variables);
    }

    public function getPkgVersionRequired()
    {
        return isset($this->pkgVersionRequired) && $this->pkgVersionRequired !== false ? $this->pkgVersionRequired : false;
    }

    public function getAppVersionRequired()
    {
        return isset($this->appVersionRequired) && $this->appVersionRequired !== false ? $this->appVersionRequired : false;
    }

    public function getDbType()
    {
        return $this->dbType;
    }

    public function getHandle()
    {
        return $this->ftHandle;
    }

    public function getRequiredSlug()
    {
        return $this->requiredSlug;
    }

    public function getBtExportFileColumn()
    {
        return $this->btExportFileColumn;
    }

    public function getHelpers()
    {
        return $this->helpers;
    }

    public function getUses()
    {
        return $this->uses;
    }

    public function getCanRepeat()
    {
        return $this->canRepeat;
    }

    public function setRepeating($bool)
    {
        $this->repeating = (bool)$bool;
    }

    public function getRepeating()
    {
        return $this->repeating;
    }

    public function getUseBaseFields()
    {
        return $this->useBaseFields;
    }

    public function setPostData($postData = array())
    {
        $this->postData = $postData;
    }

    public function setData($data = array())
    {
        $this->data = $data;
    }

    public function getFieldTypeJavascript()
    {
        return file_exists($this->ftDirectory . 'elements' . DIRECTORY_SEPARATOR . 'field_javascript.js') ? 'src/FieldType/' . $this->className . '/elements/field_javascript.js' : false;
    }

    public function getFieldTypeCss()
    {
        return file_exists($this->ftDirectory . 'elements' . DIRECTORY_SEPARATOR . 'field_css.css') ? 'src/FieldType/' . $this->className . '/elements/field_css.css' : false;
    }

    public function generateFormContent($type, $values = array(), $repeatable = false)
    {
        switch ($type) {
            case 'label':
                $labelInner = 't("' . h($values['label']) . '")' . (isset($values['suffix']) ? $values['suffix'] : null);
                $labelInner .= isset($values['description']) && trim($values['description']) != '' ? " . ' " . sprintf('<i class="fa fa-question-circle launch-tooltip" data-original-title="%s"></i>' . "'", '\' . t("' . h($values['description']) . '") . \'') : null;
                if ($repeatable) {
                    return '<label for="<?php  echo $view->field(\'' . $values['parent']['slug'] . '\'); ?>[{{id}}][' . $values['slug'] . ']"><?php  echo ' . $labelInner . '; ?></label>';
                } else {
                    return '<?php  echo $form->label(\'' . $values['slug'] . '\', ' . $labelInner . '); ?>';
                }
                break;
            case 'required':
                if (isset($values['array'])) {
                    return '<?php  echo isset(' . $values['array'] . ') && in_array(\'' . $values['slug'] . '\', ' . $values['array'] . ') ? \'<small class="required">\' . t(\'Required\') . \'</small>\' : null; ?>';
                } else {
                    return '<?php  echo \'<small class="required">\' . t(\'Required\') . \'</small>\'; ?>';
                }
                break;
            case 'file':
                if ($repeatable) {
                    $baseAttributes = array(
                        'data-file-selector-input-name' => '<?php  echo $view->field(\'' . $values['parent']['slug'] . '\'); ?>[{{id}}][' . $values['slug'] . ']',
                        'class'                         => 'ccm-file-selector',
                        'data-file-selector-f-id'       => '{{ ' . $values['slug'] . ' }}'
                    );
                    $attributes = isset($values['attributes']) && is_array($values['attributes']) && !empty($values['attributes']) ? array_merge($baseAttributes, $values['attributes']) : $baseAttributes;
                    return '<div ' . join(' ', array_map(function ($key) use ($attributes) {
                        if (is_bool($attributes[$key])) {
                            return $attributes[$key] ? $key : '';
                        }
                        return $key . '="' . $attributes[$key] . '"';
                    }, array_keys($attributes))) . '></div>';
                } else {
                    return '<?php  echo Core::make("helper/concrete/asset_library")->file($view->field(\'' . $values['slug'] . '\'), "' . $values['postName'] . '", ' . (isset($values['chooseText']) && trim($values['chooseText']) != '' ? $values['chooseText'] : 't("Choose File")') . ', ' . $values['bf'] . '); ?>';
                }
                break;
            case 'image':
                if ($repeatable) {
                    $baseAttributes = array(
                        'data-file-selector-input-name' => '<?php  echo $view->field(\'' . $values['parent']['slug'] . '\'); ?>[{{id}}][' . $values['slug'] . ']',
                        'class'                         => 'ccm-file-selector',
                        'data-file-selector-f-id'       => '{{ ' . $values['slug'] . ' }}'
                    );
                    $attributes = isset($values['attributes']) && is_array($values['attributes']) && !empty($values['attributes']) ? array_merge($baseAttributes, $values['attributes']) : $baseAttributes;
                    return '<div ' . join(' ', array_map(function ($key) use ($attributes) {
                        if (is_bool($attributes[$key])) {
                            return $attributes[$key] ? $key : '';
                        }
                        return $key . '="' . $attributes[$key] . '"';
                    }, array_keys($attributes))) . '></div>';
                } else {
                    return '<?php  echo Core::make("helper/concrete/asset_library")->image(\'' . $values['slug'] . '-\' . Core::make(\'helper/validation/identifier\')->getString(18), $view->field(\'' . $values['postName'] . '\'), ' . (isset($values['chooseText']) && trim($values['chooseText']) != '' ? $values['chooseText'] : 't("Choose Image")') . ', ' . $values['bf'] . '); ?>';
                }
                break;
            case
            'editor':
                if ($repeatable) {
                    $baseAttributes = array(
                        'name'  => '<?php  echo $view->field(\'' . $values['parent']['slug'] . '\'); ?>[{{id}}][' . $values['slug'] . ']',
                        'id'    => '<?php  echo $view->field(\'' . $values['parent']['slug'] . '\'); ?>[{{id}}][' . $values['slug'] . ']',
                        'class' => 'form-control',
                    );
                    $attributes = isset($values['attributes']) && is_array($values['attributes']) && !empty($values['attributes']) ? array_merge($baseAttributes, $values['attributes']) : $baseAttributes;
                    return '<textarea ' . join(' ', array_map(function ($key) use ($attributes) {
                        if (is_bool($attributes[$key])) {
                            return $attributes[$key] ? $key : '';
                        }
                        return $key . '="' . $attributes[$key] . '"';
                    }, array_keys($attributes))) . '>{{ ' . $values['slug'] . ' }}</textarea>';
                } else {
                    return '<?php  echo Core::make(\'editor\')->outputBlockEditModeEditor($view->field(\'' . $values['slug'] . '\'), $' . $values['slug'] . '); ?>';
                }
                break;
            case 'page_selector':
                if ($repeatable) {
                    $baseAttributes = array(
                        'data-page-selector' => '{{token}}',
                        'data-input-name'    => '<?php  echo $view->field(\'' . $values['parent']['slug'] . '\'); ?>[{{id}}][' . $values['slug'] . ']',
                        'data-cID'           => '{{' . $values['slug'] . '}}',
                    );
                    $attributes = isset($values['attributes']) && is_array($values['attributes']) && !empty($values['attributes']) ? array_merge($baseAttributes, $values['attributes']) : $baseAttributes;
                    return '<div ' . join(' ', array_map(function ($key) use ($attributes) {
                        if (is_bool($attributes[$key])) {
                            return $attributes[$key] ? $key : '';
                        }
                        return $key . '="' . $attributes[$key] . '"';
                    }, array_keys($attributes))) . '></div>';
                } else {
                    return '<?php  echo Core::make("helper/form/page_selector")->selectPage($view->field(\'' . $values['slug'] . '\'), $' . $values['slug'] . '); ?>';
                }
                break;
            case 'textarea':
            case 'text':
                if ($repeatable) {
                    $slug = !isset($values['escape']) || $values['escape'] === true ? '{{ ' . $values['slug'] . ' }}' : '{{{ ' . $values['slug'] . ' }}}';
                    $baseAttributes = array(
                        'name'  => '<?php  echo $view->field(\'' . $values['parent']['slug'] . '\'); ?>[{{id}}][' . $values['slug'] . ']',
                        'id'    => '<?php  echo $view->field(\'' . $values['parent']['slug'] . '\'); ?>[{{id}}][' . $values['slug'] . ']',
                        'class' => 'form-control',
                    );
                    switch ($type) {
                        case 'text':
                            $baseAttributes['type'] = 'text';
                            $baseAttributes['value'] = isset($values['value']) ? $values['value'] : $slug;
                            $attributes = isset($values['attributes']) && is_array($values['attributes']) && !empty($values['attributes']) ? array_merge($baseAttributes, $values['attributes']) : $baseAttributes;
                            return '<input ' . join(' ', array_map(function ($key) use ($attributes) {
                                if (is_bool($attributes[$key])) {
                                    return $attributes[$key] ? $key : '';
                                }
                                return $key . '="' . $attributes[$key] . '"';
                            }, array_keys($attributes))) . ' />';
                            break;
                        case 'textarea':
                            $attributes = isset($values['attributes']) && is_array($values['attributes']) && !empty($values['attributes']) ? array_merge($baseAttributes, $values['attributes']) : $baseAttributes;
                            return '<textarea ' . join(' ', array_map(function ($key) use ($attributes) {
                                if (is_bool($attributes[$key])) {
                                    return $attributes[$key] ? $key : '';
                                }
                                return $key . '="' . $attributes[$key] . '"';
                            }, array_keys($attributes))) . '>' . $slug . '</textarea>';
                            break;
                    }
                } else {
                    return '<?php  echo $form->' . $type . '($view->field(\'' . $values['slug'] . '\'), ' . (isset($values['value']) ? $values['value'] : '$' . $values['slug']) . ', ' . (isset($values['attributes']) && is_array($values['attributes']) && !empty($values['attributes']) ? var_export($values['attributes'], true) : 'array()') . '); ?>';
                }
                break;
            case 'select':
                if ($repeatable) {
                    $baseAttributes = array(
                        'name'  => '<?php  echo $view->field(\'' . $values['parent']['slug'] . '\'); ?>[{{id}}][' . $values['slug'] . ']',
                        'id'    => '<?php  echo $view->field(\'' . $values['parent']['slug'] . '\'); ?>[{{id}}][' . $values['slug'] . ']',
                        'class' => 'form-control',
                    );
                    $attributes = isset($values['attributes']) && is_array($values['attributes']) && !empty($values['attributes']) ? array_merge($baseAttributes, $values['attributes']) : $baseAttributes;
                    return '<?php  $' . $values['parent']['slug'] . ucFirst($values['slug']) . '_options = ' . $values['options'] . '; ?>
                    <select ' . join(' ', array_map(function ($key) use ($attributes) {
                        if (is_bool($attributes[$key])) {
                            return $attributes[$key] ? $key : '';
                        }
                        return $key . '="' . $attributes[$key] . '"';
                    }, array_keys($attributes))) . '>{{#select ' . $values['slug'] . '}}<?php  foreach ($' . $values['parent']['slug'] . ucFirst($values['slug']) . '_options as $k => $v) {
                        echo "<option value=\'" . $k . "\'>" . $v . "</option>";
                     } ?>{{/select}}</select>';
                } else {
                    return '<?php  echo $form->' . $type . '($view->field(\'' . $values['slug'] . '\'), ' . $values['options'] . ', $' . $values['slug'] . ', ' . (isset($values['attributes']) && is_array($values['attributes']) && !empty($values['attributes']) ? var_export($values['attributes'], true) : 'array()') . '); ?>';
                }
                break;
            case 'select_multiple':
                if ($repeatable) {
                    $baseAttributes = array(
                        'name'     => '<?php  echo $view->field(\'' . $values['parent']['slug'] . '\'); ?>[{{id}}][' . $values['slug'] . '][]',
                        'id'       => '<?php  echo $view->field(\'' . $values['parent']['slug'] . '\'); ?>[{{id}}][' . $values['slug'] . ']',
                        'class'    => 'form-control',
                        'multiple' => 'multiple',
                    );
                    $attributes = isset($values['attributes']) && is_array($values['attributes']) && !empty($values['attributes']) ? array_merge($baseAttributes, $values['attributes']) : $baseAttributes;
                    return '<?php  $' . $values['parent']['slug'] . ucFirst($values['slug']) . '_options = ' . $values['options'] . '; ?>
                    <select ' . join(' ', array_map(function ($key) use ($attributes) {
                        if (is_bool($attributes[$key])) {
                            return $attributes[$key] ? $key : '';
                        }
                        return $key . '="' . $attributes[$key] . '"';
                    }, array_keys($attributes))) . '>{{#select ' . $values['slug'] . '}}<?php  foreach ($' . $values['parent']['slug'] . ucFirst($values['slug']) . '_options as $k => $v){
                        echo "<option value=\'" . $k . "\'>" . $v . "</option>";
                     } ?>{{/select}}</select>';
                } else {
                    return '<?php  echo $form->selectMultiple($view->field(\'' . $values['slug'] . '\'), ' . $values['options'] . ', ' . (isset($values['defaultValues']) ? $values['defaultValues'] : false) . ', ' . (isset($values['attributes']) && is_array($values['attributes']) && !empty($values['attributes']) ? var_export($values['attributes'], true) : 'array()') . '); ?>';
                }
                break;
        }
    }
} 