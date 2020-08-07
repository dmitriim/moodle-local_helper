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
 * Backup stuff.
 *
 * @package   local_helper
 * @author    Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Class backup_local_helper_plugin.
 */
class backup_local_helper_plugin extends backup_local_plugin {

    /**
     * Returns the information to be attached to a module instance.
     *
     * @return \backup_plugin_element
     */
    protected function define_grade_item_plugin_structure() {
        $plugin = $this->get_plugin_element(null, null, null);
        $pluginwrapper = new backup_nested_element($this->get_recommended_name());
        $plugin->add_child($pluginwrapper);

        $helpers = new backup_nested_element('local_helpers');
        $helper = new backup_nested_element('local_helper', ['id'], ['helper']);

        $pluginwrapper->add_child($helpers);
        $helpers->add_child($helper);

        $this->step->log('Yay, attaching data to grade item!', backup::LOG_INFO);

        $helper->set_source_array([['id' => 1, 'helper' => 'Test helper']]);

        return $plugin;
    }

}
