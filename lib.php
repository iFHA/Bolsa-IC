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
 * Library of interface functions and constants for module invertclass
 *
 * All the core Moodle functions, neeeded to allow the module to work
 * integrated in Moodle should be placed here.
 * All the problem specific functions, needed to implement all the module
 * logic, should go to locallib.php. This will help to save some memory when
 * Moodle is performing actions across all modules.
 *
 * @package    mod_problem
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/** example constant */
//define('problem_ULTIMATE_ANSWER', 42);

////////////////////////////////////////////////////////////////////////////////
// Moodle core API                                                            //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the information on whether the module supports a feature
 *
 * @see plugin_supports() in lib/moodlelib.php
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function invertclass_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_INTRO:         return true;
        case FEATURE_SHOW_DESCRIPTION:  return true;

        default:                        return null;
    }
}

/**
 * Saves a new instance of the invertclass into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $invertclass An object from the form in mod_form.php
 * @param mod_invertclass_mod_form $mform
 * @return int The id of the newly inserted invertclass record
 */
function invertclass_add_instance(stdClass $invertclass, mod_invertclass_mod_form $mform = null) {
   global $DB;

   $invertclass->timecreated = time();

    //ADD NEW FORUM
    $forum = new stdClass();
    $forum->course = $invertclass->course;
    $forum->name = 'F&oacute;rum';
    $forum->intro = $invertclass->name;
    $forum->introformat = 1;
    $forum->forcesubscribe = 1;
    $forum->type = "general";    
    $forum->timemodified = time();
    $forum->id = $DB->insert_record("forum", $forum);

    if (! $module_forum = $DB->get_record("modules", array("name" => "forum"))) {
        echo $OUTPUT->notification("Could not find forum module!!");
        return false;
    }
    
    $mod_forum = new stdClass();
    $mod_forum->course = $invertclass->course;
    $mod_forum->module = $module_forum->id;
    $mod_forum->instance = $forum->id;
    $mod_forum->section = $invertclass->section;
    $mod_forum->groupmode = 1;
    
    if (! $mod_forum->coursemodule = add_course_module($mod_forum) ) {
        echo $OUTPUT->notification("Could not add a new course module to the course '" . $invertclass->course . "'");
        return false;
    }
    
    if (! $section_forum = add_mod_to_section($mod_forum) ) { 
        echo $OUTPUT->notification("Could not add the new course module to that section");
        return false;
    }
    
    $DB->set_field("course_modules", "section", $section_forum, array("id" => $mod_forum->coursemodule));
    
    
    
    //ADD NEW CHAT
    $chat = new stdClass();
    $chat->course = $invertclass->course;
    $chat->name = 'Chat';
    $chat->intro = $invertclass->name;
    $chat->introformat = 1;
    $chat->chattime = time();
    $chat->timemodified = time();
    $chat->id = $DB->insert_record("chat", $chat);

    if (! $module_chat = $DB->get_record("modules", array("name" => "chat"))) {
        echo $OUTPUT->notification("Could not find chat module!!");
        return false;
    }
    
    $mod_chat = new stdClass();
    $mod_chat->course = $invertclass->course;
    $mod_chat->module = $module_chat->id;
    $mod_chat->instance = $chat->id;
    $mod_chat->section = $invertclass->section;
    $mod_chat->groupmode = 1;
    
    if (! $mod_chat->coursemodule = add_course_module($mod_chat) ) {
        echo $OUTPUT->notification("Could not add a new course module to the course '" . $invertclass->course . "'");
        return false;
    }
    
    if (! $section_chat = add_mod_to_section($mod_chat) ) {
        echo $OUTPUT->notification("Could not add the new course module to that section");
        return false;
    }   
    
    $DB->set_field("course_modules", "section", $section_chat, array("id" => $mod_chat->coursemodule));
    
    
    $invertclass->forum = $mod_forum->coursemodule;
    $invertclass->chat = $mod_chat->coursemodule;
   
    return $DB->insert_record('invertclass', $invertclass);
}

/**
 * Updates an instance of the invertclass in the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $invertclass An object from the form in mod_form.php
 * @param mod_invertclass_mod_form $mform
 * @return boolean Success/Fail
 */
function invertclass_update_instance(stdClass $invertclass, mod_invertclass_mod_form $mform = null) {
    global $DB;

    $invertclass->timemodified = time();
    $invertclass->id = $invertclass->instance;

    # You may have to add extra stuff in here #

    return $DB->update_record('invertclass', $invertclass);
}

/**
 * Removes an instance of the invertclass from the database
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function invertclass_delete_instance($id) {
    global $DB;
    /*
    if (!$invertclass = $DB->get_record('invertclass', array('id' => $id))) {
        return false;
    }

    $result = true;

    # Delete any dependent records here #
    //get invertclass group
    $pgs = $DB->get_records('invertclass_group', array('invertclassid' => $id));
    foreach ($pgs as $pg) {
        if (! $DB->delete_records('invertclass_evaluation_measured', array('invertclass_group' => $pg->id)) ) {
            $result = false;
        }
        if (! $DB->delete_records('invertclass_pair_evaluation', array('invertclass_group' => $pg->id)) ) {
            $result = false;
        }
        if (! $DB->delete_records('invertclass_unknown_words', array('invertclass_group' => $pg->id)) ) {
            $result = false;
        }
        if (! $DB->delete_records('invertclass_group_session', array('invertclass_group' => $pg->id)) ) {
            $result = false;
        }
        if (! $DB->delete_records('invertclass_group', array('invertclassid' => $invertclass->id)) ) {
            $result = false;
        }
    }

    if (! $DB->delete_records('invertclass_requirements', array('invertclassid' => $invertclass->id)) ) {
        $result = false;
    }
    if (! $DB->delete_records('invertclass_goals', array('invertclassid' => $invertclass->id)) ) {
        $result = false;
    }
    if (! $DB->delete_records('invertclass', array('id' => $invertclass->id)) ) {
        $result = false;
    }
    */
    return $result;
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return stdClass|null
 */
function invertclass_user_outline($course, $user, $mod, $invertclass) {

    $return = new stdClass();
    $return->time = 0;
    $return->info = '';
    return $return;
}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @param stdClass $course the current course record
 * @param stdClass $user the record of the user we are generating report for
 * @param cm_info $mod course module info
 * @param stdClass $invertclass the module instance record
 * @return void, is supposed to echp directly
 */
function invertclass_user_complete($course, $user, $mod, $invertclass) {
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in invertclass activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @return boolean
 */
function invertclass_print_recent_activity($course, $viewfullnames, $timestart) {
    return false;  //  True if anything was printed, otherwise false
}

/**
 * Prepares the recent activity data
 *
 * This callback function is supposed to populate the passed array with
 * custom activity records. These records are then rendered into HTML via
 * {@link invertclass_print_recent_mod_activity()}.
 *
 * @param array $activities sequentially indexed array of objects with the 'cmid' property
 * @param int $index the index in the $activities to use for the next record
 * @param int $timestart append activity since this time
 * @param int $courseid the id of the course we produce the report for
 * @param int $cmid course module id
 * @param int $userid check for a particular user's activity only, defaults to 0 (all users)
 * @param int $groupid check for a particular group's activity only, defaults to 0 (all groups)
 * @return void adds items into $activities and increases $index
 */
function invertclass_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid=0, $groupid=0) {
}

/**
 * Prints single activity item prepared by {@see invertclass_get_recent_mod_activity()}

 * @return void
 */
function invertclass_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function invertclass_cron () {
    return true;
}

/**
 * Returns all other caps used in the module
 *
 * @example return array('moodle/site:accessallgroups');
 * @return array
 */
function invertclass_get_extra_capabilities() {
    return array();
}

////////////////////////////////////////////////////////////////////////////////
// Gradebook API                                                              //
////////////////////////////////////////////////////////////////////////////////

/**
 * Is a given scale used by the instance of invertclass?
 *
 * This function returns if a scale is being used by one invertclass
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $invertclassid ID of an instance of this module
 * @return bool true if the scale is used by the given invertclass instance
 */
function invertclass_scale_used($invertclassid, $scaleid) {
    global $DB;

    /** @example */
    if ($scaleid and $DB->record_exists('invertclass', array('id' => $invertclassid, 'grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if scale is being used by any instance of invertclass.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param $scaleid int
 * @return boolean true if the scale is used by any invertclass instance
 */
function invertclass_scale_used_anywhere($scaleid) {
    global $DB;

    /** @example */
    if ($scaleid and $DB->record_exists('invertclass', array('grade' => -$scaleid))) {
        return true;
    } else {
        return false;
    }
}

/**
 * Creates or updates grade item for the give invertclass instance
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $invertclass instance object with extra cmidnumber and modname property
 * @param mixed optional array/object of grade(s); 'reset' means reset grades in gradebook
 * @return void
 */
function invertclass_grade_item_update(stdClass $invertclass, $grades=null) {
    global $CFG;
    require_once($CFG->libdir.'/gradelib.php');

    /** @example */
    $item = array();
    $item['itemname'] = clean_param($invertclass->name, PARAM_NOTAGS);
    $item['gradetype'] = GRADE_TYPE_VALUE;
    $item['grademax']  = $invertclass->grade;
    $item['grademin']  = 0;

    grade_update('mod/invertclass', $invertclass->course, 'mod', 'invertclass', $invertclass->id, 0, null, $item);
}

/**
 * Update invertclass grades in the gradebook
 *
 * Needed by grade_update_mod_grades() in lib/gradelib.php
 *
 * @param stdClass $invertclass instance object with extra cmidnumber and modname property
 * @param int $userid update grade of specific user only, 0 means all participants
 * @return void
 */
function invertclass_update_grades(stdClass $invertclass, $userid = 0) {
    global $CFG, $DB;
    require_once($CFG->libdir.'/gradelib.php');

    /** @example */
    $grades = array(); // populate array of grade objects indexed by userid

    grade_update('mod/invertclass', $invertclass->course, 'mod', 'invertclass', $invertclass->id, 0, $grades);
}

////////////////////////////////////////////////////////////////////////////////
// File API                                                                   //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the lists of all browsable file areas within the given module context
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by {@link file_browser::get_file_info_context_module()}
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @return array of [(string)filearea] => (string)description
 */
function invertclass_get_file_areas($course, $cm, $context) {
    return array();
}

/**
 * File browsing support for invertclass file areas
 *
 * @package mod_invertclass
 * @category files
 *
 * @param file_browser $browser
 * @param array $areas
 * @param stdClass $course
 * @param stdClass $cm
 * @param stdClass $context
 * @param string $filearea
 * @param int $itemid
 * @param string $filepath
 * @param string $filename
 * @return file_info instance or null if not found
 */
function invertclass_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
    return null;
}

/**
 * Serves the files from the invertclass file areas
 *
 * @package mod_invertclass
 * @category files
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the invertclass's context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 */
function invertclass_pluginfile($course, $cm, $context, $filearea, array $args, $forcedownload, array $options=array()) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }

    require_login($course, true, $cm);

    send_file_not_found();
}

////////////////////////////////////////////////////////////////////////////////
// Navigation API                                                             //
////////////////////////////////////////////////////////////////////////////////

/**
 * Extends the global navigation tree by adding invertclass nodes if there is a relevant content
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $navref An object representing the navigation tree node of the invertclass module instance
 * @param stdClass $course
 * @param stdClass $module
 * @param cm_info $cm
 */
function invertclass_extend_navigation(navigation_node $navref, stdclass $course, stdclass $module, cm_info $cm) {
}

/**
 * Extends the settings navigation with the invertclass settings
 *
 * This function is called when the context for the page is a invertclass module. This is not called by AJAX
 * so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav {@link settings_navigation}
 * @param navigation_node $invertclassnode {@link navigation_node}
 */
function invertclass_extend_settings_navigation(settings_navigation $settingsnav, navigation_node $invertclassnode=null) {
}
