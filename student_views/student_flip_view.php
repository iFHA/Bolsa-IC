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
$grupo = get_grupo($USER->id);
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
          <?php //if( 1 /* !empty($grupo) */)
          if(!empty($myprofile->prefered_times)){ ?>
          <li role="presentation"><a href="#problem" aria-controls="problem" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-home"></i> ATIVIDADE</a></li>
          <li role="presentation"><a href="#referencias" aria-controls="referencias" role="tab" data-toggle="tab">REFERÊNCIAS</a></li>
          <!--<li role="presentation" <?php /* if(!$group->id){ echo 'class="active"'; } */ ?>><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-user"></i> Meu perfil</a></li>-->
          <li role="presentation"><a href="#groups" aria-controls="groups" role="tab" data-toggle="tab">GRUPO</a></li>
          <li role="presentation"><a href="#etapas" aria-controls="etapas" role="tab" data-toggle="tab">ETAPAS</a></li>
          <li role="presentation"><a href="#notas" aria-controls="notas" role="tab" data-toggle="tab">AVALIAÇÃO</a></li>
          <!--<li role="presentation"><a href="#aproveitamento" aria-controls="aproveitamento" role="tab" data-toggle="tab">APROVEITAMENTO</a></li> -->
          <!--<li role="presentation"><a href="#avaliar" aria-controls="avaliar" role="tab" data-toggle="tab">AVALIAÇÃO</a></li>-->
          <li role="presentation"><a href="#feedback" aria-controls="feedback" role="tab" data-toggle="tab">FEEDBACK</a></li>
          <li role="presentation"><a href="#perfil" aria-controls="perfil_usuario" role="tab" data-toggle="tab">MEU PERFIL</a></li>
          <?php 
          } else { ?>
            <li role="presentation"><a href="#perfil" aria-controls="perfil_usuario" role="tab" data-toggle="tab">MEU PERFIL</a></li>
          <?php
          }
          ?>
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
        <?php
        $invertclass->arquivozin = get_arquivo($invertclass->arquivoid);
         if( 1 /* $group->id */){ ?>
        <!-- ################################################################## -->
        <!--                       EXIBIÇÃO DO PROBLEMA                         -->
        <!-- ################################################################## -->
        <div role="tabpanel" class="tab-pane <?php if(!empty($myprofile->prefered_times)) echo 'active'; ?>" id="problem">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">Dados da Atividade</h3>
            </div>
            <div class="panel-body">
              <h3><?php echo $invertclass->name; ?></h3><br />
              <p><strong>Descrição:</strong> <?php echo $invertclass->descricao; ?></p><hr />
              <p>
                <strong>Arquivo enviado pelo professor:</strong> 
                <?php echo $invertclass->arquivozin->nome_original; ?>  <a href="./arquivos/tarefas/<?php echo $invertclass->arquivozin->nome_final?>" target=_blank class='btn btn-primary'>Baixar</a>
            </p>
            </div>
          </div>
        </div>
        <?php
        }//Se estiver vinculado a algum grupo para eo problema 
        ?>
        <!-- ################################################################## -->

        <!-- ################################################################## -->
        <!--                       EXIBIÇÃO DOS GRUPOS                         -->
        <!-- ################################################################## -->
                    <div role="tabpanel" class="tab-pane" id="groups">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">INFORMAÇÕES DO GRUPO:  <?php echo $grupo->nome ?></h3>

                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th>Participantes</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    global $DB;
                                    //$fpgroups = new stdClass();
                                    //$fpgroups = $DB->get_records("fpgroups");

                                    // LISTANDO OS GRUPOS AOS QUAIS O USUARIO PERTENCE
                                    
                                    //$grupo = $DB->get_record_sql("SELECT grupin.id, grupin.nome, grupin.etapaatual FROM mdl_fpgroups as grupin, mdl_fpmembers as membros WHERE grupin.id = membros.id_group and membros.id_user = ".$_SESSION["USER"]->id);
                                    foreach($grupo->membros as $membro){ ?>
                                    <tr>
                                        <td>
                                            <?php echo $membro->nome ?>
                                        </td>
                                    </tr>
                                    <?php 
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
                    <div role="tabpanel" class="tab-pane" id="etapas">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">ETAPA</h3>
                            </div>
                            <div class="panel-body">
                                <?php
                                $etapa = $DB->get_record_sql("SELECT etapa.id, etapa.descricao, etapa.data_fim, etapa.tipo, etapa.ultima FROM mdl_invertclass_steps as etapa, mdl_fpgroups as grupin, mdl_fpmembers as membros WHERE grupin.id = membros.id_group and membros.id_user = ".$_SESSION["USER"]->id." and etapa.id = grupin.etapaatual");
                                if(!empty($etapa)){
                                    if($grupo->finalizado == 0){ /// se a última etapa não foi respondida ainda
                                ?>
                                <table class="table table-bordered table-condensed table-hover">
                                    <thead>
                                        <tr>
                                            <th>DESCRIÇÃO DA ETAPA</th>
                                            <!-- <th>ARQUIVO ENVIADO PELO(A) PROF.</th>
                                            <th style= text-align:center;>BAIXAR ARQUIVO</th> -->
                                            <th>PRAZO</th>
                                            <!--<th>ESCOLHER ANEXO</th>
                                            <th>ENVIAR ANEXO</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <!-- //$arq = $task->arquivo."</td><td style=text-align:center;><a href=arquivos/tarefas/{$task->arquivo} target=_blank class='btn btn-primary'>Baixar</a></td><td>";-->
                                        <tr>
                                            <td>
                                                <?php echo $etapa->descricao; ?>
                                            </td>
                                            <td>
                                                <?php 
                                                $datafim = explode('-', $etapa->data_fim);
                                                echo $datafim[2].'/'.$datafim[1].'/'.$datafim[0]; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <form action=student_views/studentactions_flip.php method='POST' enctype="multipart/form-data">
                                    <input type="hidden" name="action" value="next_step">
                                    <input name="url_local" type="hidden" value="<?php echo $PAGE->url?>">
                                    <input type="hidden" name="etapaid" value="<?php echo $etapa->id ?>">
                                    <input type="hidden" name="etapatipo" value="<?php echo $etapa->tipo ?>">
                                    <input type="hidden" name="ultima" value="<?php echo $etapa->ultima ?>">
                                    <input type="hidden" name="grupoid" value="<?php echo $grupo->id ?>">
                                    <input type="hidden" name="send">
                                    <?php if($etapa->tipo){ ?>
                                    <div class="input-group">
                                        <span class="input-group-addon">Resposta: </span>
                                        <input type="text" class="form-control" name="resposta">
                                    </div>
                                    <?php } else { ?>
                                    Arquivo:
                                    <input type="file" name="arq">
                                    <?php } ?>
                                    <br/>
                                    <input type="submit" value="Enviar Resposta" class="btn btn-primary">
                                </form>
                                <?php 
                                    }else{ ?>
                                        <div class="alert alert-success" role="alert">
                                            Todas as etapas foram realizadas.
                                        </div>
                                    <?php
                                    }
                                }
                                ?>
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
                    <div role="tabpanel" class="tab-pane <?php if(empty($myprofile->prefered_times)) echo 'active'; ?>" id="perfil">
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
                                  <a href="#perfil" id="btn-del-cloned-input" name="btn-del-cloned-input" class="btn btn-success btn-xs"  onclick="updateFeature(this)">
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
