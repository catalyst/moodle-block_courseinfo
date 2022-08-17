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

    /**
     * Block initialization
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_courseinfo');
    }

    /**
     * Allow the block to have a configuration page
     *
     * @return boolean
     */
    public function has_config() {
        return true;
    }

    /**
     * Locations where block can be displayed
     *
     * @return array
     */
    public function applicable_formats() {
        return array(
            'course-view' => true,
        );
    }

    /**
     * Customisze block title
     */
    public function specialization() {
        $this->title = get_string('blocktitle', 'block_courseinfo');
    }

    /**
     * Allows multiple instances of this block to a page
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Return contents of courseinfo block
     *
     * @return stdClass contents of block
     */
    public function get_content() {
        global $COURSE;
        if (!isset($COURSE->id)) {
            return null;
        }
        if ($this->content !== null) {
            return $this->content;
        }
        $tableprefix = 'course';
        $prefix = 'course';
        $params = array();
        $fields = customfield_get_fields_definition($tableprefix, $params);

        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->items = array();
        $this->content->icons = array();
        if (!isset($this->config)) {
            $this->config = new stdClass;
        }
        $out = array();

        $attributes = array(
            'class' => 'info-fieldname',
        );
        if (!isset($this->config->displayfields)) {
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
