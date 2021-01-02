<?php  namespace RamonLeenders\BlockDesigner\FieldType\WysiwygFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use RamonLeenders\BlockDesigner\FieldType\FieldType;

class WysiwygFieldType extends FieldType
{
    protected $ftHandle = 'wysiwyg';
    protected $dbType = 'X2';
    protected $uses = array('Concrete\Core\Editor\LinkAbstractor');
    protected $canRepeat = true;

    public function getFieldName()
    {
        return t("WYSIWYG");
    }

    public function getFieldDescription()
    {
        return t("A 'What-You-See-Is-What-You-Get' text area");
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

    public function getViewFunctionContents()
    {
        if ($this->getRepeating()) {
            return '$' . $this->data['parent']['slug'] . '_item_v["' . $this->data['slug'] . '"] = isset($' . $this->data['parent']['slug'] . '_item_v["' . $this->data['slug'] . '"]) ? LinkAbstractor::translateFrom($' . $this->data['parent']['slug'] . '_item_v["' . $this->data['slug'] . '"]) : null;';
        } else {
            return '$this->set(\'' . $this->data['slug'] . '\', LinkAbstractor::translateFrom($this->' . $this->data['slug'] . '));';
        }
    }

    public function getSaveFunctionContents()
    {
        if ($this->getRepeating()) {
            return '$data[\'' . $this->data['slug'] . '\'] = isset($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '\']) ? LinkAbstractor::translateTo($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '\']) : null;';
        } else {
            return '$args[\'' . $this->data['slug'] . '\'] = LinkAbstractor::translateTo($args[\'' . $this->data['slug'] . '\']);';
        }
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

    public function getEditFunctionContents()
    {
        $return = null;
        if ($this->getRepeating()) {
            $slug = '$' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '\']';
            $return .= PHP_EOL . '        foreach ($' . $this->data['parent']['slug'] . '_items as &$' . $this->data['parent']['slug'] . '_item) {
            ' . $slug . ' = isset(' . $slug . ') ? LinkAbstractor::translateFromEditMode(' . $slug . ') : null;
        }';
        } else {
            $return .= '
        $this->set(\'' . $this->data['slug'] . '\', LinkAbstractor::translateFromEditMode($this->' . $this->data['slug'] . '));';
        }
        return $return;
    }

    public function getRepeatableUpdateItemJS()
    {
        if ((!isset($this->data['ft_count_repeatable']) && $this->data['ft_count'] > 0) || (isset($this->data['ft_count_repeatable']) && $this->data['ft_count_repeatable'] > 0)) {
            return;
        }
        return '$(newField).find(\'textarea.ft-wysiwyg\').redactor({
                "plugins": ["concrete5lightbox", "undoredo", "specialcharacters", "table", "concrete5magic"],
                "minHeight": 200,
                "concrete5": {"filemanager": true, "sitemap": true}
            }).on(\'remove\', function () {
                $(this).redactor(\'core.destroy\');
            });';
    }

    public function getViewContents()
    {
        $slug = $this->getRepeating() ? $this->data['parent']['slug'] . '_item["' . $this->data['slug'] . '"]' : $this->data['slug'];
        return '<?php  if (isset($' . $slug . ') && trim($' . $slug . ') != "") { ?>' . $this->data['prefix'] . '<?php  echo $' . $slug . '; ?>' . $this->data['suffix'] . '<?php  } ?>';
    }

    public function getFormContents()
    {
        $repeating = $this->getRepeating();
        $btFieldsRequired = $repeating ? '$btFieldsRequired[\'' . $this->data['parent']['slug'] . '\']' : '$btFieldsRequired';
        $return = '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null, 'label' => $this->data['label'], 'description' => $this->data['description']), $repeating) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null, 'array' => $btFieldsRequired), $repeating) . '
    ' . parent::generateFormContent('editor', array('slug' => $this->data['slug'], 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null, 'attributes' => array('class' => 'ft-wysiwyg')), $repeating) . '
</div>';
        return $return;
    }

    public function getDbFields()
    {
        return array(
            array(
                'name' => $this->data['slug'],
                'type' => $this->getDbType(),
            )
        );
    }

    public function getAssets()
    {
        return array(
            'addEdit' => array(
                'require'  => array(
                    array(
                        'handle' => 'redactor',
                    ),
                    array(
                        'handle' => 'core/file-manager',
                    ),
                ),
            ),
        );
    }
}