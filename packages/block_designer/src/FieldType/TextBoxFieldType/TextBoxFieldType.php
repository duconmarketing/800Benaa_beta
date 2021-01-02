<?php  namespace RamonLeenders\BlockDesigner\FieldType\TextBoxFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use RamonLeenders\BlockDesigner\FieldType\FieldType;

class TextBoxFieldType extends FieldType
{
    protected $ftHandle = 'text_box';
    protected $dbType = 'C';
    protected $canRepeat = true;

    public function getFieldDescription()
    {
        return t("A text input field");
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
        return '<?php  if (isset($' . $slug . ') && trim($' . $slug . ') != "") { ?>' . $this->data['prefix'] . '<?php  echo h($' . $slug . '); ?>' . $this->data['suffix'] . '<?php  } ?>';
    }

    public function getViewFunctionContents()
    {
        if ($this->getRepeating()) {
            if (isset($this->data['fallback_value']) && trim($this->data['fallback_value']) != '') {
                return 'if (!isset($' . $this->data['parent']['slug'] . '_item_v["' . $this->data['slug'] . '"]) || trim($' . $this->data['parent']['slug'] . '_item_v["' . $this->data['slug'] . '"]) == "") {
                $' . $this->data['parent']['slug'] . '_item_v["' . $this->data['slug'] . '"] = \'' . h($this->data['fallback_value']) . '\';
            }';
            }
        } else {
            if (isset($this->data['fallback_value']) && trim($this->data['fallback_value']) != '') {
                return 'if (trim($this->' . $this->data['slug'] . ') == "") {
            $this->set("' . $this->data['slug'] . '", \'' . h($this->data['fallback_value']) . '\');
        }';
            }
        }
        return;
    }

    public function getValidateFunctionContents()
    {
        if ($this->getRepeating()) {
            return 'if (in_array("' . $this->data['slug'] . '", $this->btFieldsRequired[\'' . $this->data['parent']['slug'] . '\']) && (!isset($' . $this->data['parent']['slug'] . '_v[\'' . $this->data['slug'] . '\']) || trim($' . $this->data['parent']['slug'] . '_v[\'' . $this->data['slug'] . '\']) == "")) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("' . h($this->data['label']) . '"), t("' . h($this->data['parent']['label']) . '"), $' . $this->data['parent']['slug'] . '_k));
                        }';
        } else {
            return 'if (in_array("' . $this->data['slug'] . '", $this->btFieldsRequired) && (trim($args["' . $this->data['slug'] . '"]) == "")) {
            $e->add(t("The %s field is required.", t("' . h($this->data['label']) . '")));
        }';
        }
    }

    public function getSaveFunctionContents()
    {
        if ($this->getRepeating()) {
            return 'if (isset($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '\']) && trim($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '\']) != \'\') {
                    $data[\'' . $this->data['slug'] . '\'] = trim($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '\']);
                } else {
                    $data[\'' . $this->data['slug'] . '\'] = null;
                }';
        }
    }

    public function getFormContents()
    {
        $maxLength = $this->maxLength($this->data);
        $placeholder = isset($this->data['placeholder']) && trim($this->data['placeholder']) != '' ? h($this->data['placeholder']) : null;
        $fieldAttributes = array(
            'maxlength' => $maxLength,
        );
        if ($placeholder) {
            $fieldAttributes['placeholder'] = $placeholder;
        }
        $repeating = $this->getRepeating();
        $btFieldsRequired = $repeating ? '$btFieldsRequired[\'' . $this->data['parent']['slug'] . '\']' : '$btFieldsRequired';
        return '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null, 'label' => $this->data['label'], 'description' => $this->data['description']), $repeating) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null, 'array' => $btFieldsRequired)) . '
    ' . parent::generateFormContent('text', array('slug' => $this->data['slug'], 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null, 'attributes' => $fieldAttributes), $repeating) . '
</div>';
    }

    public function getFieldOptions()
    {
        return parent::view('field_options.php');
    }

    public function getDbFields()
    {
        return array(
            array(
                'name' => $this->data['slug'],
                'type' => $this->getDbType(),
                'size' => $this->maxLength($this->data),
            ),
        );
    }

    private function maxLength()
    {
        return isset($this->data['max_length']) && is_numeric($this->data['max_length']) && $this->data['max_length'] >= 1 && $this->data['max_length'] <= 255 ? (int)$this->data['max_length'] : 255;
    }
} 