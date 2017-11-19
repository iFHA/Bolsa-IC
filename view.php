<?php
/**
 *
 * @package   mod_invertclass
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


include(dirname(__FILE__).'/head.php');

if(invertclass_is_enrolled($context, "student")){
  include(dirname(__FILE__).'/student_views/student_flip_view.php');
}
else if(invertclass_is_enrolled($context, "editingteacher")){
  include(dirname(__FILE__).'/teacher_views/teacher_flip_view.php');
}

// Finish the page
echo $OUTPUT->footer();