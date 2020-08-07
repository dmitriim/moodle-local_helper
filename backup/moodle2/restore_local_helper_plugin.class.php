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
 * Restore stuff.
 *
 * @package   local_helper
 * @author    Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

/**
 * Class restore_local_helper_plugin.
 */
class restore_local_helper_plugin extends restore_local_plugin {

    /**
     * Creates a path element in order to be able to execute code after restore
     *
     * @return restore_path_element[]
     */
    public function define_grade_item_plugin_structure() {
        $paths = [];

        $elename = 'local_helper';
        $elepath = $this->get_pathfor('/local_helpers/local_helper');
        $paths[] = new restore_path_element($elename, $elepath);

        return $paths;
    }

    /**
     * Process the custom field element.
     *
     * @param array $data Custom field data from backup.
     */
    public function process_local_helper($data) {
        $this->step->log("Yay, restoring data for grade item: {$data['id']} - {$data['helper']}", backup::LOG_INFO);
    }
}
