<?php  namespace RamonLeenders\BlockDesigner\FieldType\NumberFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use RamonLeenders\BlockDesigner\FieldType\FieldType;

class NumberFieldType extends FieldType
{
    protected $ftHandle = 'number';
    public $dbType = 'N';
    protected $canRepeat = true;

    public function getFieldDescription()
    {
        return t("A number field");
    }

    public function getSearchableContent()
    {
        if ($this->getRepeating()) {
            $slug = '$' . $this->data['parent']['slug'] . '_item_v["' . $this->data['slug'] . '"]';
            return 'if (isset(' . $slug . ') && trim(' . $slug . ') != "") {
                $content[] = ' . $slug . ';
            }';
        } else {
            return '$content[] = $this->' . $this->data['slug'] . ';';
        }
    }

    public function getViewContents()
    {
        $slug = $this->getRepeating() ? $this->data['parent']['slug'] . '_item["' . $this->data['slug'] . '"]' : $this->data['slug'];
        $variable = '$' . $slug;
        if (isset($this->data['number_format']) && $this->data['number_format'] == '1') {
            $thousands_sep = isset($this->data['number_format_thousand_sep']) && trim($this->data['number_format_thousand_sep']) != '' ? $this->data['number_format_thousand_sep'] : ',';
            $decimal_point = isset($this->data['number_format_decimal_point']) && trim($this->data['number_format_decimal_point']) != '' ? $this->data['number_format_decimal_point'] : '.';
            $decimals = (int)$this->data['number_format_decimals'] >= 0 ? (int)$this->data['number_format_decimals'] : 0;
            $variable = 'number_format($' . $slug . ', ' . $decimals . ', ' . var_export($decimal_point, true) . ', ' . var_export($thousands_sep, true) . ')';
        }
        return '<?php  if (isset($' . $slug . ') && trim($' . $slug . ') != "") { ?>' . $this->data['prefix'] . '<?php  echo ' . $variable . '; ?>' . $this->data['suffix'] . '<?php  } ?>';
    }

    public function getValidateFunctionContents()
    {
        $return = '';
        $repeating = $this->getRepeating();
        if ($repeating) {
            $messages = array(
                'required'       => 't("The %s field is required (%s, row #%s).", t("' . h($this->data['label']) . '"), t("' . h($this->data['parent']['label']) . '"), $' . $this->data['parent']['slug'] . '_k)',
                'disallow_float' => 't("The %s field has to be an integer (float number disallowed) (%s, row #%s).", t("' . h($this->data['label']) . '"), t("' . h($this->data['parent']['label']) . '"), $' . $this->data['parent']['slug'] . '_k)',
                'min_number'     => 't("The %s field needs a minimum of %s (%s, row #%s).", t("' . h($this->data['label']) . '"), ' . $this->data['min_number'] . ', t("' . h($this->data['parent']['label']) . '"), $' . $this->data['parent']['slug'] . '_k)',
                'max_number'     => 't("The %s field needs a maximum of %s (%s, row #%s).", t("' . h($this->data['label']) . '"), ' . $this->data['max_number'] . ', t("' . h($this->data['parent']['label']) . '"), $' . $this->data['parent']['slug'] . '_k)',
            );
        } else {
            $messages = array(
                'required'       => 't("The %s field is required.", t("' . h($this->data['label']) . '"))',
                'disallow_float' => 't("The %s field has to be an integer (float number disallowed).", t("' . h($this->data['label']) . '"))',
                'min_number'     => 't("The %s field needs a minimum of %s.", t("' . h($this->data['label']) . '"), ' . $this->data['min_number'] . ')',
                'max_number'     => 't("The %s field needs a maximum of %s.", t("' . h($this->data['label']) . '"), ' . $this->data['max_number'] . ')',
            );
        }
        $slug = $repeating ? '$' . $this->data['parent']['slug'] . '_v[\'' . $this->data['slug'] . '\']' : '$args[\'' . $this->data['slug'] . '\']';
        $btFieldsRequired = $repeating ? '$this->btFieldsRequired[\'' . $this->data['parent']['slug'] . '\']' : '$this->btFieldsRequired';
        $statements = array();
        if (isset($this->data['disallow_float']) && $this->data['disallow_float'] == '1') {
            $statements[] = array(
                'if'   => '!ctype_digit(' . $slug . ')',
                'then' => '$e->add(' . $messages['disallow_float'] . ')'
            );
        }
        if (isset($this->data['min_number']) && trim($this->data['min_number']) != '') {
            $statements[] = array(
                'if'   => $slug . ' < ' . $this->data['min_number'],
                'then' => '$e->add(' . $messages['min_number'] . ')'
            );
        }
        if (isset($this->data['max_number']) && trim($this->data['max_number']) != '') {
            $statements[] = array(
                'if'   => $slug . ' > ' . $this->data['max_number'],
                'then' => '$e->add(' . $messages['max_number'] . ')'
            );
        }
        foreach ($statements as $k => $statement) {
            $type = $k == 0 ? 'if' : ' elseif';
            $return .= PHP_EOL . $type . ' (' . $statement['if'] . ') {
                ' . ($repeating ? '                ' : null) . $statement['then'] . ';
            }';
        }
        return 'if (trim(' . $slug . ') != "") {
            ' . ($repeating ? '                ' : null) . $slug . ' = str_replace(\',\', \'.\', ' . $slug . ');
            ' . ($repeating ? '                ' : null) . $return . '
        ' . ($repeating ? '                ' : null) . '} elseif (in_array("' . $this->data['slug'] . '", ' . $btFieldsRequired . ')) {
            ' . ($repeating ? '                ' : null) . '$e->add(' . $messages['required'] . ');
        ' . ($repeating ? '                ' : null) . '}';
    }

    public function getSaveFunctionContents()
    {
        if ($this->getRepeating()) {
            return 'if (isset($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '\']) && trim($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '\']) != \'\') {
                    $data[\'' . $this->data['slug'] . '\'] = str_replace(\',\', \'.\', $' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '\']);
                } else {
                    $data[\'' . $this->data['slug'] . '\'] = null;
                }';
        } else {
            return '$args[\'' . $this->data['slug'] . '\'] = str_replace(\',\', \'.\', $args[\'' . $this->data['slug'] . '\']);';
        }
    }

    public function getFormContents()
    {
        $repeating = $this->getRepeating();
        $btFieldsRequired = $repeating ? '$btFieldsRequired[\'' . $this->data['parent']['slug'] . '\']' : '$btFieldsRequired';
        return '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null, 'label' => $this->data['label'], 'description' => $this->data['description']), $repeating) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null, 'array' => $btFieldsRequired)) . '
    ' . parent::generateFormContent('text', array('slug' => $this->data['slug'], 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null), $repeating) . '
</div>';
    }

    public function getFieldOptions()
    {
        return parent::view('field_options.php');
    }

    public function getDbFields()
    {
        $length = isset($this->data['database_length']) && (int)$this->data['database_length'] >= -1 && (int)$this->data['database_length'] <= 10485760 ? (int)$this->data['database_length'] : 10;
        $decimals = isset($this->data['database_decimals']) && (int)$this->data['database_decimals'] >= -53 && (int)$this->data['database_decimals'] <= 53 ? (int)$this->data['database_decimals'] : 2;
        return array(
            array(
                'name' => $this->data['slug'],
                'type' => $this->getDbType(),
                'size' => ($length + $decimals) . '.' . $decimals
            )
        );
    }
}