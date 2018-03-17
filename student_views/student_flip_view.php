<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$groups = groups_get_user_groups($cm->course, $USER->id);
$groupid = null;
foreach($groups as $g){
	foreach($g as $gr){ $groupid = $gr; }
}

$group = get_group($groupid,$invertclass->id);

//$final_evaluation = get_evaluationByMeasured($group->problemgroup, $USER->id);
if(count($group->sessions) > 0){

  $last = 0;
  foreach($group->sessions as $session){
    if($session->timestart > $last){
      $lastsession = $session;
      $last = $session->timestart;
    }
  }
}


$myprofile = get_user($USER->id);
//$myprofile->unknown_words = $DB->get_record("problem_unknown_words", array("problem_group" => $group->problemgroup, "userid" => $USER->id));
$invertclass->features = get_features();
$sep = "";
$features_description = "";
foreach ($invertclass->features as $feature) {
  $features_description .= $sep."\"".$feature->description."\"";
  $sep = ', ';
}

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once(dirname(dirname(__FILE__)).'/lib.php');
require_once(dirname(dirname(__FILE__)).'/locallib.php');

?>
<div class="container-fluid">
  <div class="row"><!-- INÍCIO DA EXIBIÇÃO DO GRUPO -->
    <div class="col-md-12">
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
          <?php if( 1 /* $group->id */){ ?>
          <li role="presentation" class="active"><a href="#problem" aria-controls="problem" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-home"></i> Problema</a></li>
          <li role="presentation"><a href="#group" aria-controls="group" role="tab" data-toggle="tab">Grupo</a></li>
          <li role="presentation"><a href="#sessions" aria-controls="sessions" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-th-list"></i> Sessões</a></li>
          <?php } //Se estiver vinculado a algum grupo para eo problema ?>
          <!--<li role="presentation" <?php /* if(!$group->id){ echo 'class="active"'; } */ ?>><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-user"></i> Meu perfil</a></li>-->
          <li role="presentation"><a href="#groups" aria-controls="groups" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-home"></i>GRUPO</a></li>
          <li role="presentation"><a href="#tarefas" aria-controls="tarefas" role="tab" data-toggle="tab">TAREFA</a></li>
          <li role="presentation"><a href="#referencias" aria-controls="referencias" role="tab" data-toggle="tab">REFERÊNCIAS</a></li>
          <li role="presentation"><a href="#notas" aria-controls="notas" role="tab" data-toggle="tab">AVALIAÇÃO</a></li>
          <!--<li role="presentation"><a href="#aproveitamento" aria-controls="aproveitamento" role="tab" data-toggle="tab">APROVEITAMENTO</a></li> -->
          <!--<li role="presentation"><a href="#avaliar" aria-controls="avaliar" role="tab" data-toggle="tab">AVALIAÇÃO</a></li>-->
          <li role="presentation"><a href="#feedback" aria-controls="feedback" role="tab" data-toggle="tab">FEEDBACK</a></li>
          <li role="presentation"><a href="#perfil" aria-controls="perfil_usuario" role="tab" data-toggle="tab">MEU PERFIL</a></li>
        </ul>
        <br />
        <?php
        $op = optional_param('op',null,PARAM_TEXT);
        switch($op){
          case 'show_task':
            echo "<div>";
            include(dirname(dirname(__FILE__)).'/teacher_views/su_task.php');
            echo "</div>";
            echo "<div class='tab-content' style='display: none'>";
            break;
          case 'up_task':
            echo "<div>";
            include(dirname(dirname(__FILE__)).'/teacher_views/up_task.php');
            echo "</div>";
            echo "<div class='tab-content' style='display: none'>";
            break;
          case 'up_ref':
            echo "<div>";
            include(dirname(dirname(__FILE__)).'/teacher_views/up_ref.php');
            echo "</div>";
            echo "<div class='tab-content' style='display: none'>";
            break;
          default:
            echo "<div class='tab-content'>";
            break;
        }
        ?>
        <?php if( 1 /* $group->id */){ ?>
        <!-- ################################################################## -->
        <!--                       EXIBIÇÃO DO PROBLEMA                         -->
        <!-- ################################################################## -->
        <div role="tabpanel" class="tab-pane active" id="problem">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Dados do problema</h3>
            </div>
            <div class="panel-body">
              <h3><?php echo $invertclass->name; ?></h3><br />
              <p><?php echo $invertclass->descricao; ?></p><hr />
              <p><strong>Produto final:</strong> <?php echo $invertclass->descricao; ?></p>
              <p><strong>Áreas de conhecimento:</strong> <?php echo $invertclass->knowledge_area; ?></p>
            </div>
          </div><!--
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Termos desconhecidos</h3>
            </div>
            <div class="panel-body">
              <p>Cite no campo abaixo, os termos contidos na descrição do problema que você desconhece separados por vírgula:</p>
              <form class="form-horizontal" action="student_views/studentactions.php" method="POST">
                <input id="id" name="id" type="hidden" value="<?php /* echo $cm->id; ?>">
                <input id="problem_group" name="problem_group" type="hidden" value="<?php echo $group->problemgroup; ?>">
                <input id="action" name="action" type="hidden" value="edit_unknown_words">
                <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
                <?php 
                  if(!empty($myprofile->unknown_words)){
                    echo '<input id="uwid" name="uwid" type="hidden" value="'.$myprofile->unknown_words->id.'">';
                  }
                ?>
                <fieldset>
                  <textarea rows="4" name="unknown_words" class="textarea form-control"><?php echo $myprofile->unknown_words; */ ?></textarea>
                  <hr />
                  <div class="col-md-8">
                    <button id="button2id" name="button2id" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Salvar termos desconhecidos</button>
                  </div>
                </fieldset>
              </form>
            </div>
          </div> -->
        </div>
          <!---PROBLEMA?????   -->
          <?php 
          if($group->problemgroup->finished == 1){
            if($group->problemgroup == 1){ ?>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title"><span class="glyphicon glyphicon-envelope"></span> Avaliação final</h3>
                </div>
                <div class="panel-body">
                  <table class="table table-bordered table-condensed table-hover">
                    <thead>
                      <tr>
                        <th>Meta de aprendizagem</th>
                        <th>Valor</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        foreach ($final_evaluation->evaluations as $ev) {
                          echo '<tr>';
                          echo '<td>'.$ev->feature->description.'</td>';
                          echo '<td>'.$ev->value.'</td>';
                          echo '</tr>';
                        }
                      ?>
                    </tbody>
                  </table>
                  <hr />
                  <?php echo "<strong>Avaliação do grupo:</strong> ".$final_evaluation->evaluation; 
                  ?>
                </div>
              </div>
              <div class="panel panel-primary">
                <div class="panel-heading">
                  <h3 class="panel-title">Solução</h3>
                </div>
                <div class="panel-body">
                  <?php
                  include(dirname(dirname(__FILE__)).'/form_file.php');
                  $maxbytes = $course->maxbytes;
                  if (empty($entry->id)) {
                    $entry = new stdClass;
                    $entry->id = $group->id;
                    $entry->definition       = $group->problemgroup->report;
                    $entry->definitionformat = FORMAT_HTML;
                    $entry->cmid = $cm->id;
                    $entry->definitiontrust  = 0;
                  }
                  $maxfiles = 99;                // TODO: add some setting
                  $maxbytes = $course->maxbytes; // TODO: add some setting

                  $definitionoptions = array('trusttext'=>true, 'maxfiles'=>$maxfiles, 'maxbytes'=>$maxbytes, 'context'=>$context, 'subdirs'=>file_area_contains_subdirs($context, 'mod_problem', 'entry', $entry->id));
                  $attachmentoptions = array('subdirs'=>false, 'maxfiles'=>$maxfiles, 'maxbytes'=>$maxbytes);

                  $entry = file_prepare_standard_editor($entry, 'definition', $definitionoptions, $context, 'mod_problem', 'entry', $entry->id);
                  $entry = file_prepare_standard_filemanager($entry, 'attachment', $attachmentoptions, $context, 'mod_problem', 'attachment', $entry->id);
                    
                  $url_form = new moodle_url('/mod/problem/view.php', array('id' => $cm->id, 'groupid' => $group->id));
                  $mform = new mod_problem_file_form($url_form, array('current'=>$entry,
                                                          'definitionoptions'=>$definitionoptions, 
                                                          'attachmentoptions'=>$attachmentoptions));

                  if ($data = $mform->get_data()) {
                    $newgroup = new stdClass;
                    $newgroup->id = $group->problemgroup->id;
                    $newgroup->report = $data->definition_editor['text'];

                    problem_save('problem_group', $newgroup, $url_form);
                  }

                  $entry = file_postupdate_standard_editor($entry, 'definition', $definitionoptions, $context,'mod_problem', 'entry', $entry->id);
                  $entry = file_postupdate_standard_filemanager($entry, 'attachment', $attachmentoptions, $context, 'mod_problem', 'attachment', $entry->id);

                  $mform->set_data($entry);
                  $mform->display();
                  ?>
                </div>
              </div><?php
            } ?>
        <!-- ################################################################## -->



          <!-- ################################################################## -->

          <!-- ################################################################## -->
          <!--                         EXIBIÇÃO DO GRUPO                          -->
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane" id="group">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Dados do Grupo: <?php echo $group->name; ?></h3>
              </div>
              <div class="panel-body">
                <table class="table table-hover table-condensed table-bordered">
                  <thead>
                    <tr>
                      <th>Nome</th>
                      <th>Avaliação de pares</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      foreach ($group->members as $member) {
                        if(problem_is_enrolled($context, "student", $member->id)){
                          echo '<tr>';
                          echo '<td><a href="student_views/userprofile.php?id=' . $cm->id . '&userid=' . $member->id . '" clas="btn">' . $member->name .'</a></td>';
                          echo '<td><a href="student_views/evaluation.php?id=' . $cm->id . '&userid=' . $member->id . '" clas="btn">Avaliar</a> | <a href="student_views/pair_evaluation.php?id=' . $cm->id.'&userid='.$member->id.'&groupid='.$group->id.'" clas="btn">Visualizar avaliação</a></td>';
                          echo '</tr>';
                        }
                      }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
            <!-- ################################################################## -->

          <!-- ################################################################## -->
          <!--                        LISTAGEM DE SESSÕES                         -->
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane" id="sessions">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Dados das sessões</h3>
              </div>
              <div class="panel-body">
                <?php if(count($group->sessions) > 0){ ?>
                <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>Data</th>
                      <th>Status</th>
                      <th>Ações</th>
                    </tr>
                  </thead>
                  <tbody>
                   <?php 
                    foreach($group->sessions as $session){
                      echo '<tr>';
                      echo '<td>' . date("d/m/Y H:i", $session->timestart) .'</td>';
                      echo '<td><span class="">';
                      if($session->finished)
                        echo '<span class="label label-success">Finalizada</span>';
                      else if($session->id == $lastsession->id && $session->timestart < time())
                        echo '<span class="label label-warning">Sessão atual</span>';
                      else 
                        echo '<span class="label label-primary">Próxima sessão</span>';
                      echo '</span></td>';
                      echo '<td>';
                      echo '<div class="btn-group">';
                      echo '<a href="student_views/session.php?id=' . $cm->id . '&sessionid=' .$session->id. '&groupid=' .$group->id. '" class="btn"><span class="glyphicon glyphicon-eye-open"></span> Visualizar</a>';
                      echo '</div>';
                      echo '</td>';
                      echo '</tr>';
                    }
                  ?>
                  </tbody>
                </table>
                <?php 
                  } else {
                    echo '<div class="alert alert-danger" role="alert">';
                    echo "Nenhuma sessão foi encontrada! Crie a primeira sessão para começar.";
                    echo '</div>';
                  }
                ?>
              </div>
            </div>
          </div>
          <!-- ################################################################## -->
          <?php 
          } //Se estiver vinculado a algum grupo para eo problema 
          ?>


          <!-- ################################################################## -->
          <!--                        PERFIL DE USUÁRIO                           -->
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane  <?php /* if(!$group->id){ echo 'active'; } */ ?>" id="profile">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Horários disponíveis para estudo</h3>
              </div>
              <div class="panel-body">
                <form class="form-inline" action="student_views/studentactions.php" method="POST">
                  <input id="id" name="id" type="hidden" value="<?php echo $cm->id; ?>">
                  <input id="action" name="action" type="hidden" value="edit_prefered_times">
                  <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">

                  <div class="input-group">
                    <strong><p class="form-control-static">Domingo: </p></strong>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="sun_m" name="sun_m" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->sunday{0} == '1'){ echo ' checked'; } ?>> Manhã
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="sun_t" name="sun_t" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->sunday{1} == '1'){ echo ' checked'; } ?>> Tarde
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="sun_n" name="sun_n" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->sunday{2} == '1'){ echo ' checked'; } ?>> Noite
                    </label>
                  </div>
                  <div class="input-group">
                    <strong><p class="form-control-static">Segunda: </p></strong>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="mon_m" name="mon_m" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->monday{0} == '1'){ echo ' checked'; } ?>> Manhã
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="mon_t" name="mon_t" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->monday{1} == '1'){ echo ' checked'; } ?>> Tarde
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="mon_n" name="mon_n" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->monday{2} == '1'){ echo ' checked'; } ?>> Noite
                    </label>
                  </div>
                  <div class="input-group">
                    <strong><p class="form-control-static">Terça: </p></strong>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="tue_m" name="tue_m" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->tuesday{0} == '1'){ echo ' checked'; } ?>> Manhã
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="tue_t" name="tue_t" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->tuesday{1} == '1'){ echo ' checked'; } ?>> Tarde
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="tue_n" name="tue_n" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->tuesday{2} == '1'){ echo ' checked'; } ?>> Noite
                    </label>
                  </div>
                  <div class="input-group">
                    <strong><p class="form-control-static">Quarta: </p></strong>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="wed_m" name="wed_m" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->wednesday{0} == '1'){ echo ' checked'; } ?>> Manhã
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="wed_t" name="wed_t" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->wednesday{1} == '1'){ echo ' checked'; } ?>> Tarde
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="wed_n" name="wed_n" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->wednesday{2} == '1'){ echo ' checked'; } ?>> Noite
                    </label>
                  </div>
                  <div class="input-group">
                    <strong><p class="form-control-static">Quinta: </p></strong>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="thu_m" name="thu_m" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->thursday{0} == '1'){ echo ' checked'; } ?>> Manhã
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="thu_t" name="thu_t" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->thursday{1} == '1'){ echo ' checked'; } ?>> Tarde
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="thu_n" name="thu_n" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->thursday{2} == '1'){ echo ' checked'; } ?>> Noite
                    </label>
                  </div>
                  <div class="input-group">
                    <strong><p class="form-control-static">Sexta: </p></strong>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="fri_m" name="fri_m" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->friday{0} == '1'){ echo ' checked'; } ?>> Manhã
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="fri_t" name="fri_t" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->friday{1} == '1'){ echo ' checked'; } ?>> Tarde
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="fri_n" name="fri_n" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->friday{2} == '1'){ echo ' checked'; } ?>> Noite
                    </label>
                  </div>
                  <div class="input-group">
                    <strong><p class="form-control-static">Sábado: </p></strong>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="sat_m" name="sat_m" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->saturday{0} == '1'){ echo ' checked'; } ?>> Manhã
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="sat_t" name="sat_t" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->saturday{1} == '1'){ echo ' checked'; } ?>> Tarde
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="sat_n" name="sat_n" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->saturday{2} == '1'){ echo ' checked'; } ?>> Noite
                    </label>
                  </div>
                  <hr />
                  <div class="input-group">
                   <button id="button2id" name="button2id" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Salvar dados</button>
                  </div>
                </form>
              </div>
            </div>
            
          </div>            
                    <!-- ################################################################## -->
                    <!--                       EXIBIÇÃO DOS GRUPOS                         -->
                    <!-- ################################################################## -->
                    <div role="tabpanel" class="tab-pane" id="groups">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">GRUPO</h3>

                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th>GRUPO</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    global $DB;
                                    //$fpgroups = new stdClass();
                                    //$fpgroups = $DB->get_records("fpgroups");

                                    // LISTANDO OS GRUPOS AOS QUAIS O USUARIO PERTENCE
                                    /*
                                    $grupos = $DB->get_records_sql("SELECT grupin.nome FROM mdl_fpgroups as grupin, mdl_fpmembers as membros WHERE grupin.id = membros.id_group and membros.id_user = ".$_SESSION["USER"]->id);
                                    foreach($grupos as $group){
                                        echo '<tr><td>'.$group->nome.'</td>';
                                        echo '</tr>';
                                    }
                                    */
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <!-- ################################################################## -->

                    <!-- ################################################################## -->
                    <!--                        TAREFAS                                     -->
                    <!-- ################################################################## -->
                    <div role="tabpanel" class="tab-pane" id="tarefas">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">TAREFA</h3>
                            </div>
                            <div class="panel-body">

                                <table class="table table-bordered table-condensed table-hover">
                                    <thead>
                                    <tr>
                                    <th>TAREFA</th>
                                    <th>ARQUIVO ENVIADO PELO(A) PROF.</th>
                                    <th style= text-align:center;>BAIXAR ARQUIVO</th>
                                    <th>PRAZO</th>
                                    <th>ESCOLHER ANEXO</th>
                                    <th>ENVIAR ANEXO</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                /*
                                // mdl_fpgroups = id, id_curso  mdl_fpmembers = id_user id_group mdl_fptasks
                                $tasks = $DB->get_records_sql("SELECT tarefa.id, tarefa.nome, tarefa.data_fim, tarefa.arquivo, grupin.id as group_id FROM mdl_fptasks as tarefa, mdl_fpgroups as grupin, mdl_fpmembers as membros WHERE grupin.id = membros.id_group and membros.id_user = ".$_SESSION["USER"]->id." and tarefa.id_curso = grupin.id_curso");//$DB->get_records("fptasks");
                                foreach( $tasks as $task){
                                    if($task->arquivo!=null){
                                        
                                        $arq = $task->arquivo."</td><td style=text-align:center;><a href=arquivos/tarefas/{$task->arquivo} target=_blank class='btn btn-primary'>Baixar</a></td><td>";
                                        
                                    }else 
                                        $arq = "Sem arquivo para baixar</td><td></td><td>";
                                    //ALTERADO AQUI 23/09 00:56
                                    echo "<tr><td>".$task->nome."</td><td>".$arq."".$task->data_fim."</td><form action=student_views/studentactions_flip.php method='POST' enctype=multipart/form-data><td><input type=hidden name=action value=upload><input name=url_local type=hidden value={$PAGE->url}><input type=hidden name=task_id value=".$task->id."><input type=hidden name=group_id value=".$task->group_id."><input type='hidden' name='send'><input type='file' name='arq'></td><td style=text-align:center;><input type=submit value='Enviar' class='btn btn-primary'></td></form>";
                                    
                                    echo "</tr>";
                                }
                                */
                                ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <!-- ########################### REFERENCIAS ####################################### -->
                    <div role="tabpanel" class="tab-pane" id="referencias">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">REFERÊNCIAS</h3>

                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered table-condensed table-hover">
                                    <thead>
                                    <tr>
                                    <th style="width: 50%">REFERÊNCIA</th>
                                    <th>ARQUIVO</th>
                                    <th style= text-align:center;>BAIXAR ARQUIVO</th>
                                    <!--<th>TAREFA</th>-->
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                // $DB->get_records_sql("SELECT ref.descricao, ref.id_task FROM mdl_fpref as ref, mdl_fptasks as tarefa, mdl_fpgroups as grupin, mdl_fpmembers as membros WHERE grupin.id = membros.id_group and membros.id_user = ".$_SESSION["USER"]->id." and tarefa.id_curso = grupin.id_curso and ref.id_task = tarefa.id");//$DB->get_records("fptasks");
                                //$refs = $DB->get_records_sql("SELECT ref.descricao, ref.moduleid, ref.arquivo, tarefa.nome FROM mdl_fpref as ref, mdl_fptasks as tarefa, mdl_fpgroups as grupin, mdl_fpmembers as membros WHERE grupin.id = membros.id_group and membros.id_user = ".$_SESSION["USER"]->id." and tarefa.id_curso = grupin.id_curso and ref.id_task = tarefa.id");//$DB->get_records("fpref");
                                $refs = $DB->get_records_sql('SELECT * FROM mdl_fpref WHERE moduleid = '.$cm->id.';');
                                foreach ($refs as $ref) {
                                  if(!empty($ref->arquivo)){
                                    //var_dump($task->arquivo);
                                    $arq = $ref->arquivo."</td><td style=text-align:center;><a href=arquivos/referencias/{$ref->arquivo} target=_blank class='btn btn-primary'>Baixar</a></td>";
                                    //var_dump($task->arquivo);
                                  }else 
                                    $arq = "Sem arquivo para baixar</td><td></td>";
                                  echo "<tr><td>$ref->descricao</td><td>$arq";
                                  echo "</tr>";
                                }
                                ?>
                                </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <!-- ############################### NOTAS ################################### -->
                    <div role="tabpanel" class="tab-pane" id="notas">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">AVALIAÇÃO</h3>

                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered table-condensed table-hover">
                                    <table class="table table-bordered table-condensed table-hover">
                                        <tr>
                                            <th>TAREFA</th>
                                            <th>NOTA</th>
                                            <th>SITUAÇÃO</th>
                                        </tr>
                                        <?php
                                        // MOSTRANDO A AVALIAÇÃO
                                        $usuario_ident = $_SESSION["USER"]->id;
                                        //$aproveitamento = $DB->get_records_sql("SELECT aproveitamento FROM mdl_fpgain WHERE id_user = ".$_SESSION["USER"]->id.";");
                                        $registros = $DB->get_records_sql("select aval.nota, tarefa.name, aval.situacao from mdl_fpmembers as memb, mdl_fpavaliar as aval, mdl_invertclass as tarefa where memb.id_user = {$usuario_ident} and memb.id_group = aval.id_group and tarefa.id = $cm->instance;");
                                        foreach ($registros as $registro){
                                            $situation = $registro->situacao == 0 ? "Obs: tarefa ainda não avaliada" : "Tarefa Avaliada";
                                            echo "<tr><td>{$registro->name}</td><td>{$registro->nota}</td><td>{$situation}</td></tr>";
                                        }
                                        ?>
                                        <!--<tr><th>NOTAS</th></tr>-->
                                        <?php
                                        /*
                                        // MOSTRANDO A AVALIAÇÃO
                                        $aproveitamento = $DB->get_records_sql("SELECT aproveitamento FROM mdl_fpgain WHERE id_user = ".$_SESSION["USER"]->id.";");
                                        foreach ($aproveitamento as $aprov)
                                        echo "<tr><td>".$aprov->aproveitamento."</td></tr>";
                                        */
                                        ?>

                                    </table>
                            </div>
                        </div>

                    </div>
                    <!-- ############################## AVALIACAO #################################### -->
                    <!--
                    <div role="tabpanel" class="tab-pane" id="avaliar">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">AVALIAÇÃO DE GRUPOS</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered table-condensed table-hover">
                                    <tr><td style="width: 21%">TAREFA</td><td>
                                            <select>
                                                <option>TAREFA 1</option>
                                                <option>TAREFA 2</option>
                                                <option>TAREFA 3</option>
                                                <option>TAREFA 4</option>
                                            </select>
                                        </td></tr>
                                </table>

                                <div id="avalia_grupo" style="display: none">
                                    <table class="table table-bordered table-condensed table-hover">
                                        <tr><th>CONSIDERAÇÕES SOBRE A TAREFA</th><th>GRUPO 1</th></tr>
                                        <tr><td colspan="2"><textarea style="width:100%; height: 200px"></textarea></td></tr>
                                        <tr><td>NOTA <input type="text" size=30></td></tr>
                                        <tr><td><button id="button2id" name="button2id" class="btn btn-success" onclick="javascript:this.value='Enviando...'; this.disabled='disabled'; this.form.submit();"><span class="glyphicon glyphicon-ok"></span> ENVIAR</button></td></tr>
                                    </table>
                                </div>
                                <table class="table table-bordered table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th>GRUPO</th>
                                        <th>NOTA</th>
                                        <th>SITUAÇÂO</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr><td>GRUPO 1</td><td></td><td><span class="btn btn-warning">PENDENTE</span></td>
                                    </tr>
                                    <tr><td>GRUPO 2</td><td>8,0</td><td><span class="btn btn-success">AVALIADO</span></td>
                                    </tr>
                                    <tr><td>GRUPO 3</td><td></td><td><span class="btn btn-warning">PENDENTE</span></td>
                                    </tr>
                                    <tr><td>GRUPO 4</td><td>8,0</td><td><span class="btn btn-success">AVALIADO</span></td>
                                    </tr>
                                    <tr><td>GRUPO 5</td><td>8,0</td><td><span class="btn btn-success">AVALIADO</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    -->
                    <!-- ################################################################## -->
                    <div role="tabpanel" class="tab-pane" id="feedback">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">FEEDBACK</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th>FEEDBACK</th>
                                        <th>TAREFA</th>
                                        <th>GRUPO</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    // $DB->get_records_sql("SELECT ref.descricao, ref.id_task FROM mdl_fpref as ref, mdl_fptasks as tarefa, mdl_fpgroups as grupin, mdl_fpmembers as membros WHERE grupin.id = membros.id_group and membros.id_user = ".$_SESSION["USER"]->id." and tarefa.id_curso = grupin.id_curso and ref.id_task = tarefa.id");//$DB->get_records("fptasks");
                                    $feeds = $DB->get_records_sql("SELECT feed.comentario, tarefa.name as tnome, grupin.nome as gnome FROM mdl_fpfeedback as feed, mdl_invertclass as tarefa, mdl_fpgroups as grupin, mdl_fpmembers as membros WHERE grupin.id = membros.id_group and membros.id_user = ".$_SESSION["USER"]->id." and feed.id_user = membros.id_user and feed.moduleid = $cm->id;");//$DB->get_records("fpref");
                                    foreach( $feeds as $feed){
                                        echo "<tr><td>".$feed->comentario."</td><td>".$feed->tnome."</td><td>".$feed->gnome."</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- ################################################################## -->

                    <!-- ###########################====PERFIL====######################### -->
                    <div role="tabpanel" class="tab-pane" id="perfil">
                      <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">Horários disponíveis para estudo</h3>
                        </div>
                        <div class="panel-body">
                          <form class="form-inline" action="student_views/studentactions_flip.php" method="POST">
                            <input id="id" name="id" type="hidden" value="<?php echo $cm->id; ?>">
                            <input id="action" name="action" type="hidden" value="edit_prefered_times">
                            <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
                            <div class="input-group">
                                <strong><p class="form-control-static">Domingo: </p></strong>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="sun_m" name="sun_m" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->sunday{0} == '1'){ echo ' checked'; } ?>> Manhã
                                </label>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="sun_t" name="sun_t" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->sunday{1} == '1'){ echo ' checked'; } ?>> Tarde
                                </label>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="sun_n" name="sun_n" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->sunday{2} == '1'){ echo ' checked'; } ?>> Noite
                                </label>
                            </div>
                            <div class="input-group">
                                <strong><p class="form-control-static">Segunda: </p></strong>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="mon_m" name="mon_m" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->monday{0} == '1'){ echo ' checked'; } ?>> Manhã
                                </label>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="mon_t" name="mon_t" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->monday{1} == '1'){ echo ' checked'; } ?>> Tarde
                                </label>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="mon_n" name="mon_n" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->monday{2} == '1'){ echo ' checked'; } ?>> Noite
                                </label>
                            </div>
                            <div class="input-group">
                                <strong><p class="form-control-static">Terça: </p></strong>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="tue_m" name="tue_m" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->tuesday{0} == '1'){ echo ' checked'; } ?>> Manhã
                                </label>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="tue_t" name="tue_t" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->tuesday{1} == '1'){ echo ' checked'; } ?>> Tarde
                                </label>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="tue_n" name="tue_n" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->tuesday{2} == '1'){ echo ' checked'; } ?>> Noite
                                </label>
                            </div>
                            <div class="input-group">
                                <strong><p class="form-control-static">Quarta: </p></strong>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="wed_m" name="wed_m" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->wednesday{0} == '1'){ echo ' checked'; } ?>> Manhã
                                </label>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="wed_t" name="wed_t" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->wednesday{1} == '1'){ echo ' checked'; } ?>> Tarde
                                </label>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="wed_n" name="wed_n" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->wednesday{2} == '1'){ echo ' checked'; } ?>> Noite
                                </label>
                            </div>
                            <div class="input-group">
                                <strong><p class="form-control-static">Quinta: </p></strong>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="thu_m" name="thu_m" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->thursday{0} == '1'){ echo ' checked'; } ?>> Manhã
                                </label>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="thu_t" name="thu_t" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->thursday{1} == '1'){ echo ' checked'; } ?>> Tarde
                                </label>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="thu_n" name="thu_n" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->thursday{2} == '1'){ echo ' checked'; } ?>> Noite
                                </label>
                            </div>
                            <div class="input-group">
                                <strong><p class="form-control-static">Sexta: </p></strong>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="fri_m" name="fri_m" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->friday{0} == '1'){ echo ' checked'; } ?>> Manhã
                                </label>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="fri_t" name="fri_t" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->friday{1} == '1'){ echo ' checked'; } ?>> Tarde
                                </label>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="fri_n" name="fri_n" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->friday{2} == '1'){ echo ' checked'; } ?>> Noite
                                </label>
                            </div>
                            <div class="input-group">
                                <strong><p class="form-control-static">Sábado: </p></strong>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="sat_m" name="sat_m" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->saturday{0} == '1'){ echo ' checked'; } ?>> Manhã
                                </label>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="sat_t" name="sat_t" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->saturday{1} == '1'){ echo ' checked'; } ?>> Tarde
                                </label>
                                <label class="checkbox-inline">
                                <input type="checkbox" id="sat_n" name="sat_n" value="1" <?php if(!empty($myprofile->prefered_times)&&$myprofile->prefered_times->saturday{2} == '1'){ echo ' checked'; } ?>> Noite
                                </label>
                            </div>
                            <hr />
                            <div class="input-group">
                              <button id="button2id" name="button2id" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Salvar dados</button>
                            </div>
                          </form>
                        </div>
                      </div>

                      <!--  -->
                      <?php 
                      $invertclass->goals = get_goals($cm->id);
                      ?>
                      <div class="panel panel-info">
                        <div class="panel-heading">
                          <h3 class="panel-title">Características</h3>
                        </div>
                        <div class="panel-body">
                          <?php
                          if(count($invertclass->goals) > 0){ ?>                                             
                          <table class="table table-bordered table-condensed table-hover">
                            <thead>
                              <tr>
                                <th>Requisito da Tarefa</th>
                                <th>Nível de Conhecimento(0 a 10)</th>
                                <th>Ação</th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php
                            solveFeaturesInconsistences($invertclass->goals, $USER->id, $cm->id);
                            // se houveram inconsistências, nesse momento já foram resolvidas
                            $myprofile = get_user($USER->id);
                            foreach ($myprofile->features as $feature) { ?>
                              <tr>
                                <td>
                                  <?php echo $feature->description; ?>
                                </td>
                                <td>
                                  <input type="number" data-featureid="<?php echo $feature->featureid; ?>" min="0" max="10" value="<?php echo $feature->value; ?>">
                                </td>
                                <td>
                                  <a href="" id="btn-del-cloned-input" name="btn-del-cloned-input" class="btn btn-success btn-xs"  onclick="updateFeature(this)">
                                    <span class="glyphicon glyphicon-floppy-save"></span> 
                                    Atualizar
                                  </a>
                                </td>
                              </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                          </table>
                          <?php 
                            } else { ?>
                              <div class="alert alert-danger" role="alert">
                                Nenhuma característica encontrada.
                              </div>
                            <?php
                            }
                          ?>
                          <!--
                          <h4>Adicionar nova característica</h4>
                          <hr />
                          <form action="student_views/studentactions.php" method="POST" class="col-md-12">
                            <input id="id" name="id" type="hidden" value="<?php /* echo $cm->id; ?>">
                            <input id="action" name="action" type="hidden" value="<?php echo 'add_feature'; ?>">
                            <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; */ ?>">
                            <div class="form-group">
                              <div class="col-xs-9">
                                <label>Descrição</label>
                                <input id="feature_description" name="feature_description" class="form-control" />
                              </div>
                              <div class="col-xs-3">
                                <label>Valor</label>
                                <select name="level" class="form-control">
                                  <option value="0" selected>0</option>
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                  <option value="5">5</option>
                                  <option value="6">6</option>
                                  <option value="7">7</option>
                                  <option value="8">8</option>
                                  <option value="9">9</option>
                                  <option value="10">10</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-xs-12">
                              <hr />
                            </div>
                            <button id="button2id" name="button2id" class="btn btn-success" onclick="javascript:this.value='Enviando...'; this.disabled='disabled'; this.form.submit();"><span class="glyphicon glyphicon-floppy-disk"></span> Adicionar característica</button>
                          </form>
                          -->
                        </div>
                      </div>
                    </div>
                    <!-- ################################################################## -->

                </div>

            </div>
        </div>
    </div><!-- FIM DA EXIBIÇÃO DO GRUPO -->

    <br />

    </div>
    <script src="https://leaverou.github.io/awesomplete/awesomplete.js"></script>
    <script src="js/ajax.js"></script>
    <script type="text/javascript">

        var goal = document.getElementById("goal_description");
        new Awesomplete(goal, {
            list: [<?php echo $features_description; ?>]
        });
        var requirement = document.getElementById("requirement_description");
        new Awesomplete(requirement, {
            list: [<?php echo $features_description; ?>]
        });
        // Shorthand for $( document ).ready()
        //$(function() {

          function updateFeature(elemento){
            var $inputzin = $(elemento).parent().prev().children();
            var featureId = $inputzin.attr("data-featureid");
            var featureValue = $inputzin.val();
            var url="student_views/studentactions_flip.php?userid=<?php echo $USER->id; ?>&featureid="+ featureId +"&featurevalue="+ featureValue +"&action=update_feature&url_local=<?php echo urlencode($PAGE->url)?>";
            //console.log("featureValue: "+ featureValue +" feature id: "+ featureId);
            location.href = url;
          }

        //});
        
    </script>
<?php
}