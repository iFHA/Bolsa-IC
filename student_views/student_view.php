<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$groups = groups_get_user_groups($cm->course, $USER->id);

foreach($groups as $g){
	foreach($g as $gr){ $groupid = $gr; }
}

$group = get_group($groupid,$problem->id);

$final_evaluation = get_evaluationByMeasured($group->problemgroup->id, $USER->id);
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
// TODO: falta alterar aqui
$myprofile->unknown_words = $DB->get_record("problem_unknown_words", array("problem_group" => $group->problemgroup->id, "userid" => $USER->id));

$problem->features = get_features();
$sep = "";
$features_description = "";
foreach ($problem->features as $feature) {
  $features_description .= $sep."\"".$feature->description."\"";
  $sep = ', ';
}

if(problem_is_enrolled($context, "student")){
?>

<div class="container-fluid">

  <div class="row"><!-- INÍCIO DA EXIBIÇÃO DO GRUPO -->
    <div class="col-md-12">
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
          <?php if($group->id){ ?>
          <li role="presentation" class="active"><a href="#problem" aria-controls="problem" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-home"></i> Problema</a></li>
          <li role="presentation"><a href="#group" aria-controls="group" role="tab" data-toggle="tab">Grupo</a></li>
          <li role="presentation"><a href="#sessions" aria-controls="sessions" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-th-list"></i> Sessões</a></li>
          <?php } //Se estiver vinculado a algum grupo para eo problema ?>
          <li role="presentation" <?php if(!$group->id){ echo 'class="active"'; } ?>><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-user"></i> Meu perfil</a></li>
        </ul>

        <br />
        
        <div class="tab-content">
          <?php if($group->id){ ?>

          <!-- ################################################################## -->
          <!--                       EXIBIÇÃO DO PROBLEMA                         -->
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane active" id="problem">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">Dados do problema</h3>
              </div>
              <div class="panel-body">
                <h3><?php echo $problem->name; ?></h3><br />
                <p><?php echo $problem->intro; ?></p><hr />
                <p><strong>Produto final:</strong> <?php echo $problem->product_format; ?></p>
                <p><strong>Áreas de conhecimento:</strong> <?php echo $problem->knowledge_area; ?></p>
              </div>
            </div>

            <div class="panel panel-primary">
              <div class="panel-heading"><h3 class="panel-title">Termos desconhecidos</h3></div>
              <div class="panel-body">
                <p>Cite no campo abaixo, os termos contidos na descrição do problema que você desconhece separados por vírgula:</p>

                <form class="form-horizontal" action="student_views/studentactions.php" method="POST">
                  <input id="id" name="id" type="hidden" value="<?php echo $cm->id; ?>">
                  <input id="problem_group" name="problem_group" type="hidden" value="<?php echo $group->problemgroup->id; ?>">
                  <input id="action" name="action" type="hidden" value="edit_unknown_words">
                  <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
                  <?php 
                    if($myprofile->unknown_words != null){
                      echo '<input id="uwid" name="uwid" type="hidden" value="'.$myprofile->unknown_words->id.'">';
                    }
                  ?>
                  <fieldset>
                    <textarea rows="4" name="unknown_words" class="textarea form-control"><?php echo $myprofile->unknown_words->unknown_words; ?></textarea>
                    <hr />
                    <div class="col-md-8">
                      <button id="button2id" name="button2id" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Salvar termos desconhecidos</button>
                    </div>
                  </fieldset>
                </form>
              </div>
            </div>

            <?php if($group->problemgroup->finished == 1){ ?>

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
                <?php echo "<strong>Avaliação do grupo:</strong> ".$final_evaluation->evaluation; ?>
                    
              </div>
            </div>

            <div class="panel panel-primary">
              <div class="panel-heading"><h3 class="panel-title">Solução</h3></div>
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
            </div>

            <?php } ?>
              


          </div>
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
          <?php } //Se estiver vinculado a algum grupo para eo problema ?>
          <!-- ################################################################## -->
          <!--                        PERFIL DE USUÁRIO                           -->
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane  <?php if(!$group->id){ echo 'active'; } ?>" id="profile">
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
                      <input type="checkbox" id="sun_m" name="sun_m" value="1" <?php if($myprofile->prefered_times->sunday{0} == '1'){ echo ' checked'; } ?>> Manhã
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="sun_t" name="sun_t" value="1" <?php if($myprofile->prefered_times->sunday{1} == '1'){ echo ' checked'; } ?>> Tarde
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="sun_n" name="sun_n" value="1" <?php if($myprofile->prefered_times->sunday{2} == '1'){ echo ' checked'; } ?>> Noite
                    </label>
                  </div>
                  <div class="input-group">
                    <strong><p class="form-control-static">Segunda: </p></strong>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="mon_m" name="mon_m" value="1" <?php if($myprofile->prefered_times->monday{0} == '1'){ echo ' checked'; } ?>> Manhã
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="mon_t" name="mon_t" value="1" <?php if($myprofile->prefered_times->monday{1} == '1'){ echo ' checked'; } ?>> Tarde
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="mon_n" name="mon_n" value="1" <?php if($myprofile->prefered_times->monday{2} == '1'){ echo ' checked'; } ?>> Noite
                    </label>
                  </div>
                  <div class="input-group">
                    <strong><p class="form-control-static">Terça: </p></strong>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="tue_m" name="tue_m" value="1" <?php if($myprofile->prefered_times->tuesday{0} == '1'){ echo ' checked'; } ?>> Manhã
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="tue_t" name="tue_t" value="1" <?php if($myprofile->prefered_times->tuesday{1} == '1'){ echo ' checked'; } ?>> Tarde
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="tue_n" name="tue_n" value="1" <?php if($myprofile->prefered_times->tuesday{2} == '1'){ echo ' checked'; } ?>> Noite
                    </label>
                  </div>
                  <div class="input-group">
                    <strong><p class="form-control-static">Quarta: </p></strong>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="wed_m" name="wed_m" value="1" <?php if($myprofile->prefered_times->wednesday{0} == '1'){ echo ' checked'; } ?>> Manhã
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="wed_t" name="wed_t" value="1" <?php if($myprofile->prefered_times->wednesday{1} == '1'){ echo ' checked'; } ?>> Tarde
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="wed_n" name="wed_n" value="1" <?php if($myprofile->prefered_times->wednesday{2} == '1'){ echo ' checked'; } ?>> Noite
                    </label>
                  </div>
                  <div class="input-group">
                    <strong><p class="form-control-static">Quinta: </p></strong>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="thu_m" name="thu_m" value="1" <?php if($myprofile->prefered_times->thursday{0} == '1'){ echo ' checked'; } ?>> Manhã
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="thu_t" name="thu_t" value="1" <?php if($myprofile->prefered_times->thursday{1} == '1'){ echo ' checked'; } ?>> Tarde
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="thu_n" name="thu_n" value="1" <?php if($myprofile->prefered_times->thursday{2} == '1'){ echo ' checked'; } ?>> Noite
                    </label>
                  </div>
                  <div class="input-group">
                    <strong><p class="form-control-static">Sexta: </p></strong>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="fri_m" name="fri_m" value="1" <?php if($myprofile->prefered_times->friday{0} == '1'){ echo ' checked'; } ?>> Manhã
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="fri_t" name="fri_t" value="1" <?php if($myprofile->prefered_times->friday{1} == '1'){ echo ' checked'; } ?>> Tarde
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="fri_n" name="fri_n" value="1" <?php if($myprofile->prefered_times->friday{2} == '1'){ echo ' checked'; } ?>> Noite
                    </label>
                  </div>
                  <div class="input-group">
                    <strong><p class="form-control-static">Sábado: </p></strong>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="sat_m" name="sat_m" value="1" <?php if($myprofile->prefered_times->saturday{0} == '1'){ echo ' checked'; } ?>> Manhã
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="sat_t" name="sat_t" value="1" <?php if($myprofile->prefered_times->saturday{1} == '1'){ echo ' checked'; } ?>> Tarde
                    </label>
                    <label class="checkbox-inline">
                      <input type="checkbox" id="sat_n" name="sat_n" value="1" <?php if($myprofile->prefered_times->saturday{2} == '1'){ echo ' checked'; } ?>> Noite
                    </label>
                  </div>
                  <hr />
                  <div class="input-group">
                    <button id="button2id" name="button2id" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Salvar dados</button>
                  </div>
                </form>
              </div>
            </div>
            
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Características</h3>
              </div>
              <div class="panel-body">
                <?php if(count($myprofile->features) > 0){ ?>
                <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>Conhecimento</th>
                      <th>Nível</th>
                      <th>Deletar</th>
                    </tr>
                  </thead>
                  <tbody>

                  <?php 
                  foreach ($myprofile->features as $feature) {
                    echo '<tr>';
                    echo '<td>'.$feature->description.'</td>';
                    echo '<td>'.$feature->value.'</td>';
                    echo '<td><a href="student_views/studentactions.php?id='.$cm->id.'&featureid='.$feature->id.'&action=delete_feature&url_local='.urlencode($PAGE->url).'" id="btn-del-cloned-input" name="btn-del-cloned-input" class="btn btn-danger btn-xs"  onclick="return confirm(\'Deseja realmente excluir esse conhecimento?\');"><span class="glyphicon glyphicon-minus"></span> Remover</a></td>';
                    echo '</tr>';
                  }
                  ?>

                  </tbody>
                </table>
                <?php 
                  } else {
                    echo '<div class="alert alert-danger" role="alert">';
                    echo "Nenhuma característica encontrada.";
                    echo '</div>';
                  }
                ?>
                <h4>Adicionar nova característica</h4>
                <hr />
                <form action="student_views/studentactions_flip.php" method="POST" class="col-md-12">
                  <input id="id" name="id" type="hidden" value="<?php echo $cm->id; ?>">
                  <input id="action" name="action" type="hidden" value="<?php echo 'add_feature'; ?>">
                  <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
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
              </div>
            </div>
          </div>
          <!-- ################################################################## -->


        </div>

      </div>
    </div>
  </div><!-- FIM DA EXIBIÇÃO DO GRUPO -->
</div>
<script src="https://leaverou.github.io/awesomplete/awesomplete.js"></script>
<script type="text/javascript">

    var goal = document.getElementById("feature_description");
    new Awesomplete(goal, {
      list: [<?php echo $features_description; ?>]
    });

    $('.text-editor').wysihtml5({locale: "pt-BR"});
  
  </script>

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
<?php
}
