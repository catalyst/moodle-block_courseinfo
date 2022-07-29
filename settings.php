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
 * Global Settings for the Course info block
 *
 * @copyright 2021 Catalyst IT Ltd
 * @author    Francis Devine <francis@catalyst.net.nz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package   block_html
 */

defined('MOODLE_INTERNAL') || die;
global $COURSE;
if ($ADMIN->fulltree) {
    $tableprefix = 'course';
    $prefix = 'course';
    $params = array();
    $fields = customfield_get_fields_definition($tableprefix, $params);
    $options = array();
    foreach ($fields as $field) {
        $formfield = customfield_get_field_instance($COURSE, $field, $tableprefix, $prefix);
        $options[$formfield->field->shortname] = format_string($formfield->field->fullname);
        
    }
    $settings->add(
        new admin_setting_configmultiselect(
            'block_courseinfo/defaultfields',
            get_string('config_displayfields', 'block_courseinfo'),
            get_string('config_displayfields_desc', 'block_courseinfo'),
            array_keys($options),
            $options
        )
    );
    
    /*$settings->add(new admin_setting_configcheckbox('block_html_allowcssclasses', get_string('allowadditionalcssclasses', 'block_html'),
                       get_string('configallowadditionalcssclasses', 'block_html'), 0));*/
}


