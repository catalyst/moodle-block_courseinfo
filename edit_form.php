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
 * Form for editing Course info block
 *
 * @copyright 2021 Catalyst IT Ltd
 * @author    Francis Devine <francis@catalyst.net.nz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_courseinfo_edit_form extends block_edit_form {

    /**
     * Enable general settings
     *
     * @return bool
     */
    protected function has_general_settings() {
        return true;
    }

    protected function specific_definition($mform) {
        global $CFG, $DB, $COURSE;
        parent::specific_definition($mform);

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('customblocksettings', 'block'));

        $tableprefix = 'course';
        $prefix = 'course';
        $params = array();
        $fields = customfield_get_fields_definition($tableprefix, $params);
        $options = array();
        foreach ($fields as $field) {
            $formfield = customfield_get_field_instance($COURSE, $field, $tableprefix, $prefix);
            if (!$formfield->is_hidden() && !$formfield->is_empty()) {
                $options[$formfield->field->shortname] = format_string($formfield->field->fullname);
            }
            
        }
        $default = get_config('block_courseinfo', 'defaultfields');
        $final = explode(',', $default);
        $element = $mform->addElement('select', 'config_displayfields', get_string('config_displayfields', 'block_courseinfo'), $options);
        $element->setMultiple(true);
        $mform->setDefault('config_displayfields', $final);
        $mform->addRule('config_displayfields', null, 'required', null, 'client');
        $mform->addRule('config_displayfields', null, 'required', null);
    }
}
