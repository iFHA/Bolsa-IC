<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/*
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
*/

$myprofile = get_user($USER->id);
//$myprofile->unknown_words = $DB->get_record("fp_unknown_words", array("fp_group" => $group->problemgroup->id, "userid" => $USER->id));

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
                        <li role="presentation" class="active"><a href="#groups" aria-controls="groups" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-home"></i>GRUPO</a></li>
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

                    <!-- ################################################################## -->
                    <!--                       EXIBIÇÃO DOS GRUPOS                         -->
                    <!-- ################################################################## -->
                    <div role="tabpanel" class="tab-pane active" id="groups">
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
                                    $grupos = $DB->get_records_sql("SELECT grupin.nome FROM mdl_fpgroups as grupin, mdl_fpmembers as membros WHERE grupin.id = membros.id_group and membros.id_user = ".$_SESSION["USER"]->id);
                                    foreach($grupos as $group){
                                        echo '<tr><td>'.$group->nome.'</td>';
                                        echo '</tr>';
                                    }
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
                                <h3 class="panel-title">TAREFAS</h3>
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
                                    <th>TAREFA</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                // $DB->get_records_sql("SELECT ref.descricao, ref.id_task FROM mdl_fpref as ref, mdl_fptasks as tarefa, mdl_fpgroups as grupin, mdl_fpmembers as membros WHERE grupin.id = membros.id_group and membros.id_user = ".$_SESSION["USER"]->id." and tarefa.id_curso = grupin.id_curso and ref.id_task = tarefa.id");//$DB->get_records("fptasks");
                                $refs = $DB->get_records_sql("SELECT ref.descricao, ref.id_task, ref.arquivo, tarefa.nome FROM mdl_fpref as ref, mdl_fptasks as tarefa, mdl_fpgroups as grupin, mdl_fpmembers as membros WHERE grupin.id = membros.id_group and membros.id_user = ".$_SESSION["USER"]->id." and tarefa.id_curso = grupin.id_curso and ref.id_task = tarefa.id");//$DB->get_records("fpref");
                                foreach( $refs as $ref){
                                    if($ref->arquivo!=null){
                                        //var_dump($task->arquivo);
                                        $arq = $ref->arquivo."</td><td style=text-align:center;><a href=arquivos/referencias/{$ref->arquivo} target=_blank class='btn btn-primary'>Baixar</a></td><td>";
                                        //var_dump($task->arquivo);
                                    }else 
                                        $arq = "Sem arquivo para baixar</td><td></td><td>";
                                    echo "<tr><td>".$ref->descricao."</td><td>".$arq."".$ref->nome."</td>";
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
                                        $registros = $DB->get_records_sql("select aval.nota, tarefa.nome, aval.situacao from mdl_fpmembers as memb, mdl_fpavaliar as aval, mdl_fptasks as tarefa where memb.id_user = '{$usuario_ident}' and memb.id_group = aval.id_group and tarefa.id = aval.id_task;");
                                        foreach ($registros as $registro){
                                            $situation = $registro->situacao == 0 ? "Obs: tarefa ainda não avaliada" : "Tarefa Avaliada";
                                            echo "<tr><td>{$registro->nome}</td><td>{$registro->nota}</td><td>{$situation}</td></tr>";
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
                                    $feeds = $DB->get_records_sql("SELECT feed.comentario, tarefa.nome as tnome, grupin.nome as gnome FROM mdl_fpfeedback as feed, mdl_fptasks as tarefa, mdl_fpgroups as grupin, mdl_fpmembers as membros WHERE grupin.id = membros.id_group and membros.id_user = ".$_SESSION["USER"]->id." and feed.id_user = membros.id_user and feed.id_course = grupin.id_curso and feed.id_task = tarefa.id");//$DB->get_records("fpref");
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
                                <?php if(0/*count($myprofile->features) > 0*/){ ?>
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
                                /*
                                foreach ($myprofile->features as $feature) {
                                    echo '<tr>';
                                    echo '<td>'.$feature->description.'</td>';
                                    echo '<td>'.$feature->value.'</td>';
                                    echo '<td><a href="student_views/studentactions.php?id='.$cm->id.'&featureid='.$feature->id.'&action=delete_feature&url_local='.urlencode($PAGE->url).'" id="btn-del-cloned-input" name="btn-del-cloned-input" class="btn btn-danger btn-xs"  onclick="return confirm(\'Deseja realmente excluir esse conhecimento?\');"><span class="glyphicon glyphicon-minus"></span> Remover</a></td>';
                                    echo '</tr>';
                                }*/
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
                                <h4>Características a serem adicionadas</h4>
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

    </script>
<?php
//}