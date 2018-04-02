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
 * Internal library of functions for module problem
 *
 * All the problem specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod_problem
 * @copyright  2011 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

date_default_timezone_set('America/Fortaleza');
defined('MOODLE_INTERNAL') || die();

$nivel = ['b'=>'Baixo',
'm'=>'Médio',
'a'=>'Alto'
];
$importancia = ['0.1'=>'Irrelevante',
'0.2'=>'Dispensável',
'0.3'=>'Extremamente Baixa',
'0.4'=>'Muito Baixa',
'0.5'=>'Baixa',
'0.6'=>'Média',
'0.7'=>'Alta',
'0.8'=>'Muito Alta',
'0.9'=>'Extremamente Alta',
'1.0'=>'Indispensável'
];

function problem_is_enrolled($context, $rolename, $userid=0){
	global $USER;
    $userid = ($userid==0) ? $USER->id : $userid ;
	$roles = get_user_roles($context, $userid, true);

	foreach ($roles as $role) {
        if ($role->shortname == $rolename)
			return true;
    }
    
	return false;

}

function problem_create_panel($config){
	$panel = '<div class="panel panel-default">';
	$panel .= '<div class="panel-heading">';
	$panel .= '<h4 class="panel-title text-left">' . $config->title . '</h4>';
	$panel .= '</div>';
	$panel .= '<div class="panel-body">' . $config->body . '</div>';
	if ($config->buttons != null) {
		$panel .= '<div class="panel-footer"><div class="btn-group">';
		foreach($config->buttons as $button){
			$panel .= $button;
		}
		$panel .= '</div></div>';
	}
	$panel .= '</div>';
	return $panel;
}

function tokenizeQuoted($string){
    for($tokens=array(), $nextToken=strtok($string, ' '); $nextToken!==false; $nextToken=strtok(' '))    {
        if($nextToken{0}=='"')
            $nextToken = $nextToken{strlen($nextToken)-1}=='"' ? 
                substr($nextToken, 1, -1) : substr($nextToken, 1) . ' ' . strtok('"');
        $tokens[] = $nextToken;
    }
    return $tokens;
}

function remover_acentos($string){
	return remover_acentos_com_utf8(utf8_encode($string));
}

function remover_acentos_sem_utf8($a){
	$a = eregi_replace("[àáâäã]","a",$a);
	$a = eregi_replace("[èéêë]","e",$a);
	$a = eregi_replace("[ìíîï]","i",$a);
	$a = eregi_replace("[òóôöõ]","o",$a);
	$a = eregi_replace("[ùúûü]","u",$a);
	$a = eregi_replace("[ÀÁÂÄÃ]","A",$a);
	$a = eregi_replace("[ÈÉÊË]","E",$a);
	$a = eregi_replace("[ÌÍÎÏ]","I",$a);
	$a = eregi_replace("[ÒÓÔÖÕ]","O",$a);
	$a = eregi_replace("[ÙÚÛÜ]","U",$a);
	$a = eregi_replace("ç","c",$a);
	$a = eregi_replace("Ç","C",$a);
	$a = eregi_replace("ñ","n",$a);
	$a = eregi_replace("Ñ","N",$a);
	$a = str_replace("´","",$a);
	$a = str_replace("`","",$a);
	$a = str_replace("¨","",$a);
	$a = str_replace("^","",$a);
	$a = str_replace("~","",$a);
	return $a;
}

function remover_acentos_com_utf8($str, $enc = "UTF-8"){

	$acentos = array(
	'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
	'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
	'C' => '/&Ccedil;/',
	'c' => '/&ccedil;/',
	'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
	'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
	'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
	'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
	'N' => '/&Ntilde;/',
	'n' => '/&ntilde;/',
	'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
	'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
	'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
	'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
	'Y' => '/&Yacute;/',
	'y' => '/&yacute;|&yuml;/',
	'a.' => '/&ordf;/',
	'o.' => '/&ordm;/');

	return preg_replace($acentos, array_keys($acentos), htmlentities($str,ENT_NOQUOTES, $enc));
}

function problem_save($table, stdclass $object, $message = true){
	global $DB;
	if(!empty($object->id)) {
		$id = (int) $object->id;
		if($DB->update_record($table, $object, $bulk=false)) {
			if($message)
				echo 'Dado(s) alterado(s) com sucesso.<br />';
		} else {
			if($message)
				echo 'N&atilde;o foi poss&iacute;vel alterar o(s) dado(s).<br />';
		}
	} else {
		if($id = $DB->insert_record($table, $object)) {
			if($message)
				echo 'Dado(s) inserido(s) com sucesso.<br />';
		} else {
			if($message)
				echo 'N&atilde;o foi poss&iacute;vel inserir o(s) dado(s).<br />';
		}
	}
	
	return $id;
}
function problem_save_requirement(stdclass $object, $message = true){
	global $DB;
	if(!empty($object->id)) {
		$id = (int) $object->id;
		if($DB->update_record('fp_requirements', $object, $bulk=false)) {
			if($message)
				echo 'Dado(s) alterado(s) com sucesso.<br />';
		} else {
			if($message)
				echo 'N&atilde;o foi poss&iacute;vel alterar o(s) dado(s).<br />';
		}
	} else {
		if($id = $DB->execute("insert into mdl_fp_requirements (problemid,featureid,value,importance) VALUES(
		$object->problemid,$object->featureid,'$object->value',$object->importance)")) {
			if($message)
				echo 'Dado(s) inserido(s) com sucesso.<br />';
		} else {
			if($message)
				echo 'N&atilde;o foi poss&iacute;vel inserir o(s) dado(s).<br />';
		}
	}
	
	return $id;
}
function problem_delete($table, $id, $message = true){
	global $DB;
	if(!empty($id)) {
		if(is_array($id)){
			foreach($id as $i){
				$DB->delete_records($table, array("id" => $i));
				if($message)
					echo 'Item #'.$i.' removido com sucesso.<br /><br />';
			}
			if($message)
				echo  '<a href="'.$url.'">Clique aqui</a> para voltar.<br />';
		} else {
			if($DB->delete_records($table, array("id" => $id))) {
				if($message)
					echo 'Remoção realizada com sucesso.<br />';
			} else {
				if($message)
					echo 'N&atilde;o foi poss&iacute;vel remover o item #'.$id.'.<br />';
			}
		}
	}
}


function problem_change($table, $id, $field, $value, $message = true){
	global $DB;
	if(!empty($id) && is_array($id)){
		$object = new stdClass();
		foreach($id as $i){
			$object->id = $i;
			$object->$field = $value;
			$DB->update_record($table, $object, $bulk=false);
			if($message)
				echo 'Item #'.$i.' alterado com sucesso.<br /><br />';
		}
	} if(!empty($id)){
		$object = new stdClass();
		$object->id = $id;
		$object->$field = $value;
		$DB->update_record($table, $object, $bulk=false);
		if($message)
			echo 'Alteração realizada com sucesso.<br /><br />';
	} else
		if($message)
			echo 'N&atilde;o foi poss&iacute;vel alterar o(s) dado(s).<br />';
}

/**
* Send an email to a specific address, using the Moodle system.
* This function is heavily based on "email_to_user()" from Moodle's libraries.
*
* @uses $CFG
* @uses $FULLME
* @param string $to The address to send the email to
* @param string $subject Plain text subject line of the email
* @param string $messagetext Plain text of the message
* @return bool Returns true if mail was sent OK, or false otherwise
*/
function problem_mail($to, $subject, $messagetext) {
    global $CFG, $FULLME;

    // Fetch the PHP mailing functionality
    include_once($CFG->libdir .'/phpmailer/moodle_phpmailer.php');

    // We are going to use textlib services here
    $textlib = textlib_get_instance();

    // Construct a new PHP mailer
    $mail = new moodle_phpmailer;

    // Determine which mail system to use
    if ($CFG->smtphosts == 'qmail') {
        $mail->IsQmail();
    } else if (empty($CFG->smtphosts)) {
        $mail->IsMail();
    } else {
        $mail->IsSMTP();
        if (!empty($CFG->debugsmtp)) {
            echo '<pre>' . "\n";
            $mail->SMTPDebug = true;
        }
        $mail->Host = $CFG->smtphosts;
        if ($CFG->smtpuser) {
            $mail->SMTPAuth = true;
            $mail->Username = $CFG->smtpuser;
            $mail->Password = $CFG->smtppass;
        }
    }

    // Use the admin's address for the Sender field
    $adminuser = get_admin();
    $mail->Sender   = $adminuser->email;
    // Use the 'noreply' address    
    $mail->From     = $CFG->noreplyaddress;
    $mail->FromName = $CFG->wwwroot;

    // Setup the other headers
    $mail->Subject = substr(stripslashes($subject), 0, 900);
    $mail->AddAddress(stripslashes($to), 'Sloodle' );
    //$mail->WordWrap = 79; // We don't want to do a wordwrap
    
    // Add our message text
    $mail->IsHTML(false);
    $mail->Body = $messagetext;

    // Attempt to send the email
    if ($mail->Send()) {
        $mail->IsSMTP();
        if (!empty($CFG->debugsmtp)) {
            echo '</pre>';
        }
        return true;
    } else {
        mtrace('ERROR: '. $mail->ErrorInfo);
        add_to_log(SITEID, 'library', 'mailer', $FULLME, 'ERROR: '. $mail->ErrorInfo);
        if (!empty($CFG->debugsmtp)) {
            echo '</pre>';
        }
        return false;
    }
}

function conversation_problem_detection($problemid, $groupid){
	global $DB;
	$problematic_students = array();
	
	$problem = $DB->get_record("problem", array("id" => $problemid));
	
	$members = $DB->get_records("groups_members", array("groupid" => $groupid));
	foreach($members as $member){
		$sessions = $DB->get_records("problem_session", array("problem" => $problem->id, "groupid" => $groupid));
		foreach($sessions as $session) {
			$count_not_related_words = 0;
			$count_related_words = 0;
			
			$messages = $DB->get_records_sql("SELECT * FROM {chat_messages} WHERE system <> 1 AND timestamp > ? AND timestamp < ? AND userid = ?", array($session->timestart, $session->timeend, $member->userid));
			foreach($messages as $message){
				
				$str = trim($problem->not_related_words);
				$str = preg_replace('/\s(?=\s)/', '', $str);
				$str = preg_replace('/[\n\r\t]/', ' ', $str);
				$not_related_words = explode(",", str_replace(", ", ",", $str));
				
				foreach($not_related_words as $not_related_word){
					$a = remover_acentos(strtolower($message->message));
					$b = remover_acentos(strtolower(html_entity_decode($not_related_word)));
					$count_not_related_words += substr_count($a, $b);
				}
				
				$str = trim($problem->related_words);
				$str = preg_replace('/\s(?=\s)/', '', $str);
				$str = preg_replace('/[\n\r\t]/', ' ', $str);
				
				$related_words = explode(",", str_replace(", ", ",",$str));
				
				foreach($related_words as $related_word){
					$a = remover_acentos(strtolower($message->message));
					$b = remover_acentos(strtolower(html_entity_decode($related_word)));
					$count_related_words += substr_count($a, $b);
				}
			}
			
			$NI = $count_not_related_words / $count_related_words;
			if(($NI) > 0){
				$date = usergetdate($session->timestart);
				$timestart = $date['mday'] . '/' . $date['mon'] . '/' . $date['year'] . ' &agrave;s ' . $date['hours'] . ':' . $date['minutes'];
			
				$obj = new stdClass;
				$obj->id = $member->userid;
				$obj->name = $DB->get_record("user", array("id" => $member->userid), 'CONCAT(firstname, " ", lastname) as name')->name;
				$obj->message = "<p># Usuario " . $obj->name . ", foi detectado em uma poss&iacute;vel conversa&ccedil;&atilde;o fora de contexto na sess&atilde;o do dia " . $timestart . ".</p>";
				$problematic_students[] = $obj;
			}
		}
	}
	$message = "";
	foreach($problematic_students as $problematic_student){
		$message .= $problematic_student->message;
	}
	return $message;
}



function passive_student_detector($cm){
	global $DB;

   $problem = $DB->get_record('problem', array('id' => $cm->instance), '*', MUST_EXIST);
	$groups = $DB->get_records("problem_group", array("problem" => $problem->id));
	
	foreach($groups as $group) {
		$students = array();
		$group_points = array();

		$members = $DB->get_records("groups_members", array("groupid" => $group->groupid));
		foreach($members as $member) {
			$student = new stdClass;
			$fp = 0;
			$cp = 0;

			$forum_post = $DB->get_records("log", array("action" => "add post", "module" => "forum", "cmid" => $problem->forum, "userid" => $member->userid));
			$forum_discuss = $DB->get_records("log", array("action" => "add discussion", "module" => "forum", "cmid" => $problem->forum, "userid" => $member->userid));
			$forum_view = $DB->get_records("log", array("action" => "view discussion", "module" => "forum", "cmid" => $problem->forum, "userid" => $member->userid));
			$chat_talk = $DB->get_records("log", array("action" => "talk", "module" => "chat", "cmid" => $problem->chat, "userid" => $member->userid));
			$chat_view = $DB->get_records("log", array("action" => "view", "module" => "chat", "cmid" => $problem->chat, "userid" => $member->userid));

			$fp += count($forum_view) * 5;
			$fp += count($forum_post) * 10;
			$fp += count($forum_discuss) * 30;
			$cp += count($chat_view) * 5;
			$cp += count($chat_talk) * 5;
			
			$group_points[] = $fp + $cp;
		
			$student->id = $member->userid;
			$student->forum_points = $fp;
			$student->chat_points = $cp;
			$student->group = $group->groupid;
			$student->problem = $problem->id;
			$student->message = "";

			$students[] = $student;
		}

		$median = calculate_median($group_points);
		$media = calculate_media($group_points, $median);
		
		$passives = array();
		foreach($students as $student) {
			if(($student->forum_points + $student->chat_points) / $media < 0.2 || $student->cfc == true) {
				$student->groupname = $DB->get_record("groups", array("id" => $group->groupid))->name;
				$student->name = $DB->get_record("user", array("id" => $student->id), 'CONCAT(firstname, " ", lastname) as name')->name;
				
				$student->message .= "<ul>";
				
				if(($student->forum_points + $student->chat_points) / $media < 0.2){
					$student->message .= "<li><strong>" . $student->name . "</strong> do grupo <strong>" . $student->groupname . "</strong></li>";
				}
				
				$student->message .= "</ul>";
				
			}
			$passives[] = $student;
		}
	}
	if(count($passives) > 0){
		echo '<h3>Alunos passivos detectados</h3><br />';
		echo 'Os seguintes estudantes n&atilde;o interagiram ou interagiram pouco com os demais membros do grupo nas ferramentas colaborativas (F&oacute;rum/Chat):';
		}
		
	foreach($passives as $passive){
		echo $passive->message;
	}
}

function calculate_median($arr) {
	sort(array_unique($arr));
	$count = count($arr);
	$middleval = floor(($count - 1) / 2);
	if($count % 2) {
		$median = $arr[$middleval];
	} else {
		$low = $arr[$middleval];
		$high = $arr[$middleval+1];
		$median = (($low + $high) / 2);
	}
	return $median;
}

function calculate_media($arr, $median) {
	$sum = 0;
	$n = 0;
	
	foreach($arr as $foo){
		$v = $foo / $median;
		if($v > 0.2 && $v < 5.0){
			$sum += $foo;
			$n++;
		}
	}
	return $sum / $n;
}



function in_array_field($needle, $needle_field, $haystack, $strict = false) { 
    if ($strict) { 
        foreach ($haystack as $item) 
            if (isset($item->$needle_field) && $item->$needle_field === $needle) 
                return true; 
    } 
    else { 
        foreach ($haystack as $item) 
            if (isset($item->$needle_field) && $item->$needle_field == $needle) 
                return true; 
    }
    return false; 
}

function get_grupo($userid, $moduleid){
	global $DB;
	$grupo = $DB->get_record_sql('SELECT grupo.id, grupo.nome, grupo.moduleid, grupo.anexoid, grupo.etapaatual, grupo.finalizado FROM mdl_fpgroups as grupo, mdl_fpmembers as membro WHERE grupo.id = membro.id_group AND membro.id_user ='.$userid.' AND grupo.moduleid = '.$moduleid.';');
	if(!empty($grupo)){
		$grupo->membros = $DB->get_records_sql("SELECT
                    u.id, u.firstname AS nome,
                    u.lastname
                    FROM 
                    mdl_role_assignments ra 
                    JOIN mdl_user u ON u.id = ra.userid
                    JOIN mdl_role r ON r.id = ra.roleid
                    JOIN mdl_context cxt ON cxt.id = ra.contextid
                    JOIN mdl_course c ON c.id = cxt.instanceid
					JOIN mdl_fpmembers membro ON membro.id_group = $grupo->id
                    WHERE ra.userid = u.id
                    AND ra.contextid = cxt.id
                    AND cxt.contextlevel =50
                    AND cxt.instanceid = c.id
					AND roleid = 5
					AND u.id = membro.id_user");
	}
	return $grupo;
}

function get_grupo_etapas($grupoid){
	global $DB;
	// invertclass_steps id = invertclass_group_steps.etapaid
	$grupo = $DB->get_record('fpgroups', array('id' => $grupoid));
	if(!empty($grupo)){
		$grupo->etapas = $DB->get_records_sql('SELECT s.descricao, gs.resposta, gs.arquivoid, s.tipo, s.ultima FROM mdl_invertclass_group_steps AS gs, mdl_invertclass_steps AS s WHERE gs.etapaid = s.id AND gs.groupid = '.$grupoid.';');
	}
	return $grupo;
}

function get_arquivo($anexoid){
	global $DB;
	$arquivo = $DB->get_record('fpanexos', array('id' => $anexoid));
	return $arquivo;
}

function get_grupos_recomendados($moduleid){
	global $DB;
	$grupos_recomendados = $DB->get_records('invertclass_rgroups', array('moduleid' => $moduleid));
	if(!empty($grupos_recomendados)){
		foreach ($grupos_recomendados as $index => $grupo_recomendado){
			$grupos_recomendados[$index]->membros = $DB->get_records_sql("SELECT
			u.firstname AS nome,
			u.id
			FROM 
			mdl_role_assignments ra 
			JOIN mdl_user u ON u.id = ra.userid
			JOIN mdl_role r ON r.id = ra.roleid
			JOIN mdl_context cxt ON cxt.id = ra.contextid
			JOIN mdl_course c ON c.id = cxt.instanceid
			JOIN mdl_invertclass_rmembers membro ON membro.id_group = $grupo_recomendado->id
			WHERE ra.userid = u.id
			AND ra.contextid = cxt.id
			AND cxt.contextlevel =50
			AND cxt.instanceid = c.id
			AND roleid = 5
			AND u.id = membro.id_user");
		}
	}
	return $grupos_recomendados;
}

function salvarGrupoRecomendado(){
	
}

function get_group($groupid, $problemid){
	global $DB;

	$group = $DB->get_record("groups", array("id" => $groupid), 'id, courseid, name, description');
	$members = $DB->get_records("groups_members", array("groupid" => $group->id), 'userid');
	
	$group->problemgroup = $DB->get_record("problem_group", array("groupid" => $groupid, "problemid" => $problemid));

	if($group->problemgroup){
		foreach ($members as $key => $member) {
			$group_user = $DB->get_record("user", array("id" => $member->userid), 'id, CONCAT(firstname, " ", lastname) as name');
			$group_user->prefered_times = $DB->get_record("problem_user_prefered_times", array("userid" => $member->userid));
			$group_user->features = $DB->get_records("problem_user_features", array("userid" => $member->userid));
			$group_user->unknown_words = $DB->get_record("problem_unknown_words", array("problem_group" => $group->problemgroup->id, "userid" => $member->userid));
			/* 
			$group_user->evaluations = $DB->get_records("problem_pair_evaluation", array("problem_group" => $group->problemgroup->id));
			foreach ($group_user->evaluations as $key_gu => $evaluation) {
				$evaluation->description = $DB->get_record("problem_features", array("id" => $evaluation->feature), 'description')->description;
				$evaluation->measured =  $DB->get_record("user", array("id" => $evaluation->measured), 'id, CONCAT(firstname, " ", lastname) as name');
				$group_user->evaluations[$key_gu] = $evaluation; 
			}
			 */
			$members[$key] = $group_user;
		}
	} else {
		foreach ($members as $key => $member) {
			$group_user = $DB->get_record("user", array("id" => $member->userid), 'id, CONCAT(firstname, " ", lastname) as name');
			$members[$key] = $group_user;
		}
	}

	$group->members = $members;

	$group->sessions = $DB->get_records("problem_group_session", array("problem_group" => $group->problemgroup));
	
	return $group;
}

function get_evaluation($problemgroup, $userid){
	global $DB;

	$pg = $DB->get_record("problem_group", array("id" => $problemgroup));
	$pg->evaluations = $DB->get_records("problem_evaluation_measured", array("problem_group" => $problemgroup, "measurer" => $userid));
	foreach ($pg->evaluations as $key => $ev) {
		$new_ev = $ev;
		$new_ev->measured = get_user($ev->measured);
		$new_ev->measurer = get_user($userid);
		$new_ev->feature = $DB->get_record("problem_features", array("id" => $ev->feature));
		$pg->evaluations[$key] = $new_ev;
	}

	return $pg;
}

function get_evaluationByMeasured($problemgroup, $userid){
	global $DB;

	$pg = $DB->get_record("problem_group", array("id" => $problemgroup));
	$pg->evaluations = $DB->get_records("problem_evaluation_measured", array("problem_group" => $problemgroup, "measured" => $userid));
	foreach ($pg->evaluations as $key => $ev) {
		$new_ev = $ev;
		$new_ev->measured = get_user($ev->measured);
		$new_ev->measurer = get_user($userid);
		$new_ev->feature = $DB->get_record("problem_features", array("id" => $ev->feature));
		$pg->evaluations[$key] = $new_ev;
	}

	return $pg;
}

function get_user($userid){
	global $DB;
	$this_user = $DB->get_record("user", array("id" => $userid), 'id, CONCAT(firstname, " ", lastname) as name');
	$this_user->prefered_times = $DB->get_record("problem_user_prefered_times", array("userid" => $userid));
	$uf = $DB->get_records("problem_user_features", array("userid" => $userid));
	$this_user->features = $uf;
	foreach ($uf as $key => $feature) {
		$this_user->features[$key]->description = $DB->get_record("problem_features", array("id" => $feature->featureid))->description;
	}
	return $this_user;
}

function update_user_feature($userId, $featureId, $featureValue){
	global $DB;
	$isValidValue = ($featureValue >= 0 && $featureValue <= 10);
	if($isValidValue)
		return $DB->update_record('problem_user_features', array('userid' => $userId, 'featureid' => $featureId, 'value' => $featureValue));
	else
		return -1;
}
// TODO: terminar esse método
function solveFeaturesInconsistences($invertclassGoals, $userid, $moduleid){
	global $DB;
	$featuresIds = '';
	foreach ($invertclassGoals as $key => $value){
		$featuresIds[$key] = $value->feature->id;
	}
	$featuresIds = implode(', ', $featuresIds);  // POG, VULGO PROGRAMAÇÃO ORIENTADA A GAMBIARRA
	$userFeatures = $DB->get_records_sql('SELECT f.id FROM mdl_problem_user_features as f, mdl_fpmembers as m, mdl_fpgroups as g WHERE f.featureid IN ('.$featuresIds.') AND f.userid = m.id_user AND m.id_group = g.id AND g.moduleid = '.$moduleid.' AND f.userid = '.$userid.';');
	//$userFeatures = $DB->get_records("problem_user_features", array('userid' => $userid));
	$caracteristica = new stdClass();
	$qtdinvertclassGoals = count($invertclassGoals);
	$qtdProfileFeatures = count($userFeatures); 
	/* echo 'qtdinvertclassGoals: '.$qtdinvertclassGoals;
	echo ' qtdProfileFeatures '.$qtdProfileFeatures; */
	$shouldAddProfileFeatures = $qtdinvertclassGoals - $qtdProfileFeatures;
	// Se should > 0, então deve - se acrescentar, se menor, então deve remover, senão, tudo ok -->
	if($shouldAddProfileFeatures < 0) {
		// remover os requisitos que estão sobrando
		//$DB->delete_records_sql('DELETE FROM mdl_problem_user_features AS uf WHERE featureid NOT IN (SELECT id FROM mdl_problem_features AS pf WHERE pf.id = uf.featureid AND uf.userid = '.$userid.');');
		//echo ' entrou no $shouldAddProfileFeatures < 0 ';
	} else if ($shouldAddProfileFeatures > 0) {
		// adicionar os novos requisitos
		//echo 'Adicionando os novos requisitos: ';
		$problemFeatures = $DB->get_records_sql('SELECT id FROM mdl_problem_features AS pf WHERE pf.id IN ('.$featuresIds.') AND pf.id NOT IN (SELECT featureid FROM mdl_problem_user_features AS uf WHERE pf.id = uf.featureid AND uf.userid = '.$userid.');');
		$caracteristica->userid = $userid;
		//$caracteristica->moduleid = $moduleid;
		foreach ($problemFeatures as $problemFeature){
			//echo ' '.$problemFeature->id;
			$caracteristica->value = 5;
			$caracteristica->featureid = $problemFeature->id;
			//$DB->execute('INSERT INTO mdl_problem_user_features VALUES (default, 5, '.$problemFeature->id.', '.$userid.');');
			$DB->insert_record('problem_user_features', $caracteristica);
		}
		//echo ' Adicionados. ';
	}
}

function get_horarios_disponiveis($userid){
	global $DB;
	return $DB->get_record('problem_user_prefered_times', array('userid' => $userid));
}

function get_requirements($problemid){
	global $DB;
	$requirements = $DB->get_records('problem_requirements', array('problemid' => $problemid));
	/* $requirements = $DB->get_records('fp_requirements', array('invertclassid' => $problemid)); */

	foreach ($requirements as $key => $requirement) {
		$requirements[$key]->feature = $DB->get_record('problem_features', array('id' => $requirement->featureid));
	}

	return $requirements;
}

function get_problem_groups($problemid){
	global $DB;
	return $DB->get_records('fpgroups', array('problemid' => $problemid), '', '*') ;
}

function get_sessions_by_group($groupid, $problemid){
	global $DB;

	if($groupid)
	  $pg = $DB->get_record('fpgroups', array('problemid' => $problemid, 'groupid' => $groupid), '*');
	else
		die("Não foi possível encontrar o grupo");

	return $DB->get_records('fp_group_session', array('fp_group' => $pg->id), 'timestart');
}

function get_goals($problemid){
	global $DB;
	$goals = $DB->get_records('problem_goals', array('problemid' => $problemid));

	foreach ($goals as $key => $goal) {
		$goals[$key]->feature = $DB->get_record('problem_features', array('id' => $goal->featureid));
	}

	return $goals;
}

function get_features(){
	global $DB;
	return $DB->get_records("problem_features");
}

function get_etapas($id){
	global $DB;
	return $DB->get_records('invertclass_steps', array('moduleid' => $id));
}

function is_steps_finished($id){
	global $DB;
	return $DB->get_record_sql('SELECT * FROM mdl_invertclass_steps WHERE moduleid = '.$id.' AND ultima = 1 LIMIT 1;');
}