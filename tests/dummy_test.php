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
 * Dummy testing test class
 *
 * @package   local_helper
 * @author     Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_helper\tests;

defined('MOODLE_INTERNAL') || die();

class dummy_test extends \advanced_testcase {

    /**
     * Dummy test for session termination.
     */
    public function test_session_termination() {
        global $SESSION, $USER;

        $this->resetAfterTest();
        $user = $this->getDataGenerator()->create_user();
        $this->setUser($user);

        $this->assertEquals($user->id, $USER->id);
        $this->assertEquals($user->id, $_SESSION['USER']->id);

        \core\session\manager::terminate_current();

        $this->assertEquals(0, $USER->id);
        $this->assertEmpty((array) $SESSION);
        $this->assertEquals(0, $_SESSION['USER']->id);
    }

}
