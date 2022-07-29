<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Course info block
 *
 * @package   block_courseinfo
 * @copyright 2021 Catalyst IT LTD
 * @author    Francis Devine <francis@catalyst.net.nz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->dirroot . '/mod/facetoface/lib.php');

class block_courseinfo extends block_list {

    function init() {
        $this->title = get_string('pluginname', 'block_courseinfo');
    }

    function has_config() {
        return true;
    }

 
    // function instance_config_save($data, $nolongerused = false) {
    //     var_dump($data);
    //     die;
    // }

    function applicable_formats() {
        return array(
            'course-view' => true,
        );
    }

    function specialization() {
        $this->title = get_string('blocktitle', 'block_courseinfo');
    }

    function instance_allow_multiple() {
        return true;
    }

    function get_content() {
        global $CFG, $DB, $PAGE, $COURSE;
        if(!isset($COURSE->id)) {
            return null;
        }
        $tableprefix = 'course';
        $prefix = 'course';
        $params = array();
        $fields = customfield_get_fields_definition($tableprefix, $params);

        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->items = array();
        $this->content->icons = array();
        if(!isset($this->config)) {
            $this->config = new stdClass;
        }
        $out = array();

        $attributes = array(
            'class' => 'info-fieldname',
        );
        if(!isset($this->config->displayfields)) {
            $default = get_config('block_courseinfo', 'defaultfields');
            $final = explode(',', $default);
            $this->config->displayfields = $final;
        }
        foreach ($fields as $field) {
            $formfield = customfield_get_field_instance($COURSE, $field, $tableprefix, $prefix);
            if (!$formfield->is_hidden() && !$formfield->is_empty() && in_array($formfield->field->shortname, $this->config->displayfields)) {
                $attributes['data-shortname'] = $formfield->field->shortname;
                $attributes['data-fieldid'] = $formfield->field->id;
                $name = format_string($formfield->field->fullname);
                $this->content->items[] = html_writer::tag('span', $name, $attributes) . ': ' . $formfield->display_data();
            }   
        }
        return $this->content;
    }
}
