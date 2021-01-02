<?php  namespace RamonLeenders\BlockDesigner\FieldType\DateTimeFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use RamonLeenders\BlockDesigner\FieldType\FieldType;

class DateTimeFieldType extends FieldType
{
    protected $ftHandle = 'date_time';
    protected $dbType = 'I';
    protected $canRepeat = true;

    public function getFieldDescription()
    {
        return t("A date + time, date or time field");
    }

    public function getViewContents()
    {
        $slug = $this->getRepeating() ? $this->data['parent']['slug'] . '_item["' . $this->data['slug'] . '"]' : $this->data['slug'];
        return '<?php  if (isset($' . $slug . ') && $' . $slug . ' > 0) { ?>' . $this->data['prefix'] . '<?php  echo strftime("' . $this->data['date_format'] . '",$' . $slug . '); ?>' . $this->data['suffix'] . '<?php  } ?>';
    }

    public function getValidateFunctionContents()
    {
        if ($this->getRepeating()) {
            return 'if (in_array("' . $this->data['slug'] . '", $this->btFieldsRequired[\'' . $this->data['parent']['slug'] . '\']) && (!isset($' . $this->data['parent']['slug'] . '_v[\'' . $this->data['slug'] . '\']) || trim($' . $this->data['parent']['slug'] . '_v[\'' . $this->data['slug'] . '\']) == "")) {
                            $e->add(t("The %s field is required (%s, row #%s).", t("' . h($this->data['label']) . '"), t("' . h($this->data['parent']['label']) . '"), $' . $this->data['parent']['slug'] . '_k));
                        } elseif (isset($' . $this->data['parent']['slug'] . '_v[\'' . $this->data['slug'] . '\']) && trim($' . $this->data['parent']['slug'] . '_v[\'' . $this->data['slug'] . '\']) != "" && strtotime($' . $this->data['parent']['slug'] . '_v[\'' . $this->data['slug'] . '\']) <= 0) {
                            $e->add(t("The %s field is not a valid date (%s, row #%s).", t("' . h($this->data['label']) . '"), t("' . h($this->data['parent']['label']) . '"), $' . $this->data['parent']['slug'] . '_k));
                        }';
        } else {
            return 'if (in_array("' . $this->data['slug'] . '", $this->btFieldsRequired) && trim($args["' . $this->data['slug'] . '"]) == "") {
            $e->add(t("The %s field is required.", t("' . h($this->data['label']) . '")));
        } elseif (trim($args["' . $this->data['slug'] . '"]) != "" && strtotime($args["' . $this->data['slug'] . '"]) <= 0) {
            $e->add(t("The %s field is not a valid date.", t("' . h($this->data['label']) . '")));
        }';
        }
    }

    public function validate()
    {
        $return = true;
        if (trim($this->data['date_format']) == '') {
            $return = t("The date format field for '%s' (row #%s) is required", $this->data['label'], $this->data['row_id']);
        }
        return $return;
    }

    public function getSaveFunctionContents()
    {
        if ($this->getRepeating()) {
            return 'if (isset($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '\']) && trim($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '\']) != \'\') {
                    $data[\'' . $this->data['slug'] . '\'] = strtotime(substr($' . $this->data['parent']['slug'] . '_item[\'' . $this->data['slug'] . '\'], 0, 21));
                } else {
                    $data[\'' . $this->data['slug'] . '\'] = null;
                }';
        } else {
            return '$args[\'' . $this->data['slug'] . '\'] = strtotime(substr($args[\'' . $this->data['slug'] . '\'], 0, 21));';
        }
    }

    private function getDateConfig()
    {
        $config = array(
            'pickDate'           => isset($this->data['pick_date']) && $this->data['pick_date'] == '0' ? false : true,
            'pickTime'           => isset($this->data['pick_time']) && $this->data['pick_time'] == '0' ? false : true,
            'useMinutes'         => isset($this->data['use_minutes']) && $this->data['use_minutes'] == '0' ? false : true,
            'useSeconds'         => isset($this->data['use_seconds']) && $this->data['use_seconds'] == '0' ? false : true,
            'useCurrent'         => isset($this->data['use_current']) && $this->data['use_current'] == '0' ? false : true,
            'showToday'          => isset($this->data['show_today']) && $this->data['show_today'] == '0' ? false : true,
            'useStrict'          => isset($this->data['use_strict']) && $this->data['use_strict'] == '1' ? true : false,
            'sideBySide'         => isset($this->data['side_by_side']) && $this->data['side_by_side'] == '1' ? true : false,

            'minuteStepping'     => isset($this->data['minute_stepping']) && $this->data['minute_stepping'] <= 30 && $this->data['minute_stepping'] > 1 ? (int)$this->data['minute_stepping'] : 1,
            'minDate'            => isset($this->data['min_date']) ? $this->data['min_date'] : '1/1/1900',
            'maxDate'            => isset($this->data['max_date']) ? $this->data['max_date'] : null,
            'defaultDate'        => isset($this->data['default_date']) ? $this->data['default_date'] : "",

            // Not (yet) implemented configurations
            'icons '             => array(
                'time' => 'glyphicon glyphicon-time',
                'date' => 'glyphicon glyphicon-calendar',
                'up'   => 'glyphicon glyphicon-chevron-up',
                'down' => 'glyphicon glyphicon-chevron-down',
            ),
            'language'           => 'en',
            'disabledDates'      => array(),
            'enabledDates'       => array(),
            'daysOfWeekDisabled' => array(),
        );

        if ($config['pickDate'] !== true && $config['pickTime'] !== true) {
            $config['pickDate'] = true;
        }

        if (trim($config['maxDate']) == '') {
            unset($config['maxDate']);
        }
        if ($config['pickTime'] !== true) {
            $config['useMinutes'] = false;
            $config['useSeconds'] = false;
            $config['sideBySide'] = false;
        }
        return $config;
    }

    public function getFormContents()
    {
        $repeating = $this->getRepeating();
        $btFieldsRequired = $repeating ? '$btFieldsRequired[\'' . $this->data['parent']['slug'] . '\']' : '$btFieldsRequired';
        $fieldAttributes = array(
            'autocomplete' => 'off',
            'class'        => 'form-control ft-datetime-' . $this->data['parent']['slug'] . '-' . $this->data['slug'],
        );
        $config = $this->getDateConfig();
        if (!$repeating) {
            $assets = array();
            $assets[] = '<script type="text/javascript">
    $(function () {
        $("#' . $this->data['slug'] . '").datetimepicker(' . str_replace(array(',', '{'), array(',' . PHP_EOL, '{' . PHP_EOL), json_encode($config)) . ');
    });
</script>';
            $html = implode(PHP_EOL, $assets);
        } else {
            $html = null;
        }
        $dateFormat = $config['pickTime'] ? 'm/d/Y g:i:s A' : 'm/d/Y';
        if ($config['pickTime'] && !$config['pickDate']) {
            $dateFormat = 'g:i:s A';
        }
        $value = '$' . $this->data['slug'] . ' > 0 ? date("' . $dateFormat . '", $' . $this->data['slug'] . ') : null';
        if ($repeating) {
            $timeFormat = 'HH';
            if ($config['useMinutes']) {
                $timeFormat .= ':mm';
            }
            if ($config['useSeconds']) {
                $timeFormat .= ':ss';
            }
            if ($config['pickTime']) {
                if ($config['pickDate']) {
                    $dateFormat = 'MM/DD/YYYY ' . $timeFormat;
                } else {
                    $dateFormat = $timeFormat;
                }
            } else {
                $dateFormat = 'MM/DD/YYYY';
            }
            $value = '{{formatDate ' . $this->data['slug'] . ' \'' . $dateFormat . '\'}}';
        }
        $html .= '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null, 'label' => $this->data['label'], 'description' => $this->data['description']), $repeating) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null, 'array' => $btFieldsRequired), $repeating) . '
    ' . parent::generateFormContent('text', array('slug' => $this->data['slug'], 'parent' => isset($this->data['parent']) ? $this->data['parent'] : null, 'value' => $value, 'attributes' => $fieldAttributes), $repeating) . '
</div>';
        return $html;
    }

    public function getRepeatableUpdateItemJS()
    {
        $config = $this->getDateConfig();
        return '$(newField).find(\'input.ft-datetime-' . $this->data['parent']['slug'] . '-' . $this->data['slug'] . '\').datetimepicker(' . str_replace(array(',', '{'), array(',' . PHP_EOL, '{' . PHP_EOL), json_encode($config)) . ');';
    }

    public function copyFiles()
    {
        $files = array();
        if ($this->data['ft_count'] <= 0) {
            $files[] = array(
                'source' => $this->pkgDirectory . 'fonts',
                'target' => $this->data['btDirectory'] . 'fonts',
            );

            $files[] = array(
                'source' => $this->ftDirectory . 'css' . DIRECTORY_SEPARATOR,
                'target' => $this->data['btDirectory'] . 'css_form' . DIRECTORY_SEPARATOR,
            );

            $files[] = array(
                'source' => $this->pkgDirectory . 'css' . DIRECTORY_SEPARATOR . 'bootstrap.fonts.css',
                'target' => $this->data['btDirectory'] . 'css_form' . DIRECTORY_SEPARATOR . 'bootstrap.fonts.css',
            );

            $files[] = array(
                'source' => $this->ftDirectory . 'js' . DIRECTORY_SEPARATOR,
                'target' => $this->data['btDirectory'] . 'js_form' . DIRECTORY_SEPARATOR,
            );
        }
        return $files;
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
                'type' => $this->dbType,
            )
        );
    }

    public function getAssets()
    {
        return array(
            'addEdit' => array(
                'register' => array(
                    array(
                        'type'     => 'css',
                        'handle'   => 'datetimepicker',
                        'filename' => 'blocks/' . $this->data['block_handle'] . '/css_form/bootstrap-datetimepicker.min.css',
                    ),
                    array(
                        'type'     => 'css',
                        'handle'   => 'bootstrap_fonts',
                        'filename' => 'blocks/' . $this->data['block_handle'] . '/css_form/bootstrap.fonts.css',
                    ),
                    array(
                        'type'     => 'javascript',
                        'handle'   => 'moment',
                        'filename' => 'blocks/' . $this->data['block_handle'] . '/js_form/moment.js',
                    ),
                    array(
                        'type'     => 'javascript',
                        'handle'   => 'bootstrap',
                        'filename' => 'blocks/' . $this->data['block_handle'] . '/js_form/bootstrap.min.js',
                    ),
                    array(
                        'type'     => 'javascript',
                        'handle'   => 'datetimepicker',
                        'filename' => 'blocks/' . $this->data['block_handle'] . '/js_form/bootstrap-datetimepicker.min.js',
                    ),
                ),
                'require'  => array(
                    array(
                        'type'   => 'css',
                        'handle' => 'datetimepicker',
                    ),
                    array(
                        'type'   => 'css',
                        'handle' => 'bootstrap_fonts',
                    ),
                    array(
                        'type'   => 'javascript',
                        'handle' => 'moment',
                    ),
                    array(
                        'type'   => 'javascript',
                        'handle' => 'bootstrap',
                    ),
                    array(
                        'type'   => 'javascript',
                        'handle' => 'datetimepicker',
                    ),
                ),
            ),
        );
    }
}