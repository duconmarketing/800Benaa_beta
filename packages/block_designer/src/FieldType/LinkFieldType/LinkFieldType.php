<?php  namespace RamonLeenders\BlockDesigner\FieldType\LinkFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use RamonLeenders\BlockDesigner\FieldType\FieldType;

class LinkFieldType extends FieldType
{
    protected $ftHandle = 'link';
    protected $dbType = 'I';
    protected $uses = array('Page');
    protected $canRepeat = true;

    public function getFieldDescription()
    {
        return t("A page selector");
    }

    public function getViewContents()
    {
        $repeating = $this->getRepeating();
        $slug = $repeating ? $this->data['parent']['slug'] . '_item["' . $this->data['slug'] . '"]' : $this->data['slug'];
        $slugText = $repeating ? $this->data['parent']['slug'] . '_item["' . $this->data['slug'] . '_text"]' : $this->data['slug'] . '_text';
        $slugC = $repeating ? $this->data['parent']['slug'] . '_item["' . $this->data['slug'] . '_c"]' : $this->data['slug'] . '_c';
        return '<?php  if (!empty($' . $slug . ') && ($' . $slugC . ' = Page::getByID($' . $slug . ')) && (!empty($' . $slugC . ') || !$' . $slugC . '->error)) {
    ?>' . $this->data['prefix'] . '<?php  echo \'<a href="\' . $' . $slugC . '->getCollectionLink() . \'"' . (isset($this->data['class']) && is_string($this->data['class']) && trim($this->data['class']) != '' ? ' class="' . h($this->data['class']) . '"' : null) . '>\' . (isset($' . $slugText . ') && trim($' . $slugText . ') != "" ? $' . $slugText . ' : $' . $slugC . '->getCollectionName()) . \'</a>\';
?>' . $this->data['suffix'] . '<?php  } ?>';
    }

    public function getValidateFunctionContents()
    {
        if ($this->getRepeating()) {
            $btFieldsRequired = '$this->btFieldsRequired[\'' . $this->data['parent']['slug'] . '\']';
            $slug = '$' . $this->data['parent']['slug'] . '_v[\'' . $this->data['slug'] . '\']';
            return 'if ((in_array("' . $this->data['slug'] . '", ' . $btFieldsRequired . ') || (isset(' . $slug . ') && trim(' . $slug . ') != \'0\')) && (trim(' . $slug . ') == "" || (($page = Page::getByID(' . $slug . ')) && $page->error !== false))) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("' . h($this->data['label']) . '"), t("' . h($this->data['parent']['label']) . '"), $' . $this->data['parent']['slug'] . '_k));
                        }';
        } else {
            return 'if (in_array("' . $this->data['slug'] . '", $this->btFieldsRequired) && (trim($args["' . $this->data['slug'] . '"]) == "" || $args["' . $this->data['slug'] . '"] == "0" || (($page = Page::getByID($args["' . $this->data['slug'] . '"])) && $page->error !== false))) {
            $e->add(t("The %s field is required.", t("' . h($this->data['label']) . '")));
        }';
        }
    }

    public function getSaveFunctionContents()
    {
        if ($this->getRepeating()) {
            $return = 'if (isset($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '\']) && trim($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '\']) != \'\' && (($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '_c\'] = Page::getByID($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '\'])) && !$' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '_c\']->error)) {
                    $data[\'' . $this->data['slug'] . '\'] = trim($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '\']);
                } else {
                    $data[\'' . $this->data['slug'] . '\'] = null;
                }';
            if (!isset($this->data['hide_title']) || $this->data['hide_title'] != '1') {
                $return .= '
                if (isset($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '_text\']) && trim($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '_text\']) != \'\') {
                    $data[\'' . $this->data['slug'] . '_text\'] = trim($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '_text\']);
                } else {
                    $data[\'' . $this->data['slug'] . '_text\'] = null;
                }';
            }
            return $return;
        }
    }

    public function getFormContents()
    {
        $repeating = $this->getRepeating();
        $btFieldsRequired = $repeating ? '$btFieldsRequired[\'' . $this->data['parent']['slug'] . '\']' : '$btFieldsRequired';
        $html = '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null, 'label' => $this->data['label'], 'description' => $this->data['description']), $repeating) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null, 'array' => $btFieldsRequired), $repeating) . '
    ' . parent::generateFormContent('page_selector', array('slug' => $this->data['slug'], 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null, 'attributes' => array('class' => 'link-ft')), $repeating);
        if (!isset($this->data['hide_title']) || $this->data['hide_title'] != '1') {
            $html .= '    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'] . '_text', 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null, 'label' => $this->data['label'], 'suffix' => ' . " " . t("Text")'), $repeating) . '
    ' . parent::generateFormContent('text', array('slug' => $this->data['slug'] . '_text', 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null), $repeating);
        }
        $html .= '</div>';
        return $html;
    }

    public function getRepeatableUpdateItemJS()
    {
        return 'var pageSelector = $(newField).find(\'.link-ft\');
        if ($(pageSelector).length > 0) {
            $(pageSelector).concretePageSelector({inputName: $(pageSelector).attr(\'data-input-name\'), cID : $(pageSelector).attr(\'data-cID\')});
        }';
    }

    public function getFieldOptions()
    {
        return parent::view('field_options.php');
    }

    public function getDbFields()
    {
        $fields = array(
            0 => array(
                'name' => $this->data['slug'],
                'type' => $this->getDbType(),
            ),
            1 => array(
                'name' => $this->data['slug'] . '_text',
                'type' => 'C',
            ),
        );
        if (isset($this->data['hide_title']) && $this->data['hide_title'] == '1') {
            unset($fields[1]);
        }
        return $fields;
    }
}