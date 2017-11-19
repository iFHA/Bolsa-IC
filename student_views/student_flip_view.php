<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/*
$groups = groups_get_all_groups($course->id);
$groups_enrolled = $DB->get_records('problem_group', array('problemid' => $problem->id), '', '*') ;

$qtn_groups_enrolled = count($groups_enrolled);
$qnt_groups_not_enrolled = count($groups) - count($groups_enrolled);

$problem->requirements = get_requirements($problem->id);
$problem->goals = get_goals($problem->id);
$problem->features = get_features();


$sep = "";
$features_description = "";
foreach ($problem->features as $feature) {
  $features_description .= $sep."\"".$feature->description."\"";
  $sep = ', ';
}

if(problem_is_enrolled($context, "editingteacher")){*/
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once(dirname(dirname(__FILE__)).'/lib.php');
require_once(dirname(dirname(__FILE__)).'/locallib.php');
?>
    <div class="container-fluid">

        <div class="row"><!-- INÍCIO DA EXIBIÇÃO DO GRUPO -->
            <div class="col-md-12">
                <div role="tabpanel">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#groups" aria-controls="groups" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-home"></i>GRUPOS</a></li>
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
                                <h3 class="panel-title">GRUPOS</h3>

                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered table-condensed table-hover">
                                    <thead>
                                    <tr>
                                        <th>GRUPOS</th>
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
                                <h3 class="panel-title">MEU PERFIL</h3>
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