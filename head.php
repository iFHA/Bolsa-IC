<?php
/**
 *
 * @package   mod_invertclass
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$p  = optional_param('p', 0, PARAM_INT);  // invertclass instance ID - it should be named as the first character of the module

if ($id) {
    $cm         = get_coursemodule_from_id('invertclass', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $invertclass  = $DB->get_record('invertclass', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $invertclass  = $DB->get_record('invertclass', array('id' => $p), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $invertclass->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('invertclass', $invertclass->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);

/// Print the page header
$PAGE->set_url('/mod/invertclass/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($invertclass->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

// O código da página começa aqui.

// PEGA OS JAVASCRIPTs E CSSs REQUERIDOS PARA A PÁGINA
$PAGE->requires->css('/mod/invertclass/css/bootstrap.css');
$PAGE->requires->css('/mod/invertclass/css/bootstrap-datetimepicker.css');
$PAGE->requires->css('/mod/invertclass/css/bootstrap-editor.css');
$PAGE->requires->css('/mod/invertclass/css/style.css');
$PAGE->requires->css('/mod/invertclass/css/awesomplete.css');


$PAGE->requires->js('/mod/invertclass/js/jquery-1.8.3.js', true);
$PAGE->requires->js('/mod/invertclass/js/bootstrap.js', true);
$PAGE->requires->js('/mod/invertclass/js/wysihtml5-0.3.0.js', true);
$PAGE->requires->js('/mod/invertclass/js/bootstrap-editor.js', true);
$PAGE->requires->js('/mod/invertclass/js/bootstrap-editor-pt-BR.js', true);
$PAGE->requires->js('/mod/invertclass/js/bootstrap-datetimepicker.js', true);
$PAGE->requires->js('/mod/invertclass/js/locales/bootstrap-datetimepicker.pt-BR.js', true);
$PAGE->requires->js('/mod/invertclass/js/scripts.js', true);

//add_to_log($course->id, 'invertclass', 'view_group', "view.php?id={$cm->id}", $invertclass->name, $cm->id);
//add_to_log($course->id, 'invertclass', 'view_session', "view.php?id={$cm->id}", $invertclass->name, $cm->id);
//add_to_log($course->id, 'invertclass', 'send_final_report', "view.php?id={$cm->id}", $invertclass->name, $cm->id);
//add_to_log($course->id, 'invertclass', 'send_session_report', "view.php?id={$cm->id}", $invertclass->name, $cm->id);
//add_to_log($course->id, 'invertclass', 'finish_session', "view.php?id={$cm->id}", $invertclass->name, $cm->id);
//add_to_log($course->id, 'invertclass', 'finish_invertclass', "view.php?id={$cm->id}", $invertclass->name, $cm->id);
//add_to_log($course->id, 'invertclass', 'evaluate_pair', "view.php?id={$cm->id}", $invertclass->name, $cm->id);
//add_to_log($course->id, 'invertclass', 'evaluate_session', "view.php?id={$cm->id}", $invertclass->name, $cm->id);
//add_to_log($course->id, 'invertclass', 'evaluate_group', "view.php?id={$cm->id}", $invertclass->name, $cm->id);
//add_to_log($course->id, 'invertclass', 'view', "view.php?id={$cm->id}", $invertclass->name, $cm->id);

// Output starts here
echo $OUTPUT->header();
?>