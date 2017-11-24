<?php
/**
 *
 * @package   mod_invert_classroom
 * @category  groups
 * @copyright 2014 Alguem
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
          <li role="presentation"class="active"><a href="#tarefas" aria-controls="tarefas" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-home"></i>TAREFA</a></li>
          <li role="presentation"><a href="#groups" aria-controls="groups" role="tab" data-toggle="tab">GRUPOS</a></li>
          <li role="presentation"><a href="#referencias" aria-controls="referencias" role="tab" data-toggle="tab">REFERÊNCIAS</a></li>
          <li role="presentation"><a href="#aproveitamento" aria-controls="aproveitamento" role="tab" data-toggle="tab">APROVEITAMENTO</a></li>
          <li role="presentation"><a href="#avaliar" aria-controls="avaliar" role="tab" data-toggle="tab">AVALIAR</a></li>
          <li role="presentation"><a href="#feedback" aria-controls="feedback" role="tab" data-toggle="tab">FEEDBACK</a></li>
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
                 case 'show_members':
                    echo "<div>";
                    include(dirname(dirname(__FILE__)).'/teacher_views/su_groups.php'); 
                    echo "</div>";
                    echo "<div class='tab-content' style='display: none'>";
                    break;
                case 'up_group':
                  echo "<div>";
                  include(dirname(dirname(__FILE__)).'/teacher_views/up_group.php'); 
                  echo "</div>";
                  echo "<div class='tab-content' style='display: none'>";
                  break;
                 default: 
                    echo "<div class='tab-content'>";
                    break;
             }
          ?>

          <!-- ################################################################## -->
          <!--                       TAREFAS                         -->
          <!-- ################################################################## -->

          <div role="tabpanel" class="tab-pane active" id="tarefas">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">TAREFAS</h3>
                </div>
                <div class="panel-body">
                    
                 <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>TAREFA</th>
                      <th>PRAZO</th>
                        <th></th>
                      
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                            //$fpgroups = $DB->get_records_sql("select * from mdl_fpgroups where id_curso = {$COURSE->id}");
                            //echo "id do curso: ".var_dump($COURSE->id);
                            $tasks = $DB->get_records_sql("select * from mdl_fptasks where id_curso = {$COURSE->id}");
                            foreach( $tasks as $task){
                                //echo "<form action='teacher_views/teacheractions_flip.php' method='GET'>";
                                echo "<tr><td>".$task->nome."</td><td>".$task->data_fim."</td><td>";
                                echo "<a href='".$PAGE->url."&op=show_task&idt=".$task->id."'><button class='btn btn-info'><span class='glyphicon glyphicon-search'></span></button></a>";
                                echo "<a href='".$PAGE->url."&op=up_task&id_curso={$COURSE->id}&idt=".$task->id."'><button class='btn btn-success'><span class='glyphicon glyphicon-pencil'></span></button></a>";
                                echo "<a href='./teacher_views/teacheractions_flip.php?action=rm_task&task_id=".$task->id."&url_local=".$PAGE->url."&task_arquivo=".$task->arquivo."'><button class='btn btn-danger'><span class='glyphicon glyphicon-remove'></span></button></a>";
                                echo "</td></tr>";
                            }
                            //exibirMensagem("testando msg!");
                        ?>
                      
                      
                    
                  </tbody>
                </table>
                    
                    <div class="col-md-8">
                      <button class="btn btn-primary" onclick="document.getElementById('add_task').style.display = 'inherit';"><span class="glyphicon glyphicon-plus"></span> ADICIONAR TAREFA</button>
                    </div>
                    
                    <br><br><br>
                                     
                     
                    <form action="teacher_views/teacheractions_flip.php" method="POST" enctype="multipart/form-data">
                    
                <div id="add_task" style="display: none">
                <table class="table table-bordered table-condensed table-hover">
                    <tr><td>NOME DA TAREFA</td><td><input id="nome" type="text" size=67 name="nome"></td></tr>
                    <tr><td>DESCRIÇÃO</td><td><input id="descricao" type="text" size=80 name="descricao"></td></tr> 
                    <tr><td>ARQUIVO</td><td><input id="arq" type="file" name="arq"></td></tr> 
                    <tr><td>DATA INÍCIO</td><td><input id="data_inicio" type="text" size=20 name="data_inicio"></td></tr>
                    <tr><td>DATA FIM</td><td><input id="data_fim" type="text" size=20 name="data_fim"></td></tr>
                    <tr><td>ÚLTIMA TAREFA</td><td><input id="ultima" name="ultima" type="radio" value=1>  SIM&nbsp;&nbsp;&nbsp;<input id="ultima" type="radio" name="ultima" value=0>  NAO</td></tr>
                    <?php echo var_dump($cmid);echo var_dump($CMID);?>
                  </table>
                    <input id="action" name="action" type="hidden" value="ad_task"/>
                    <input id="id_curso" name="id_curso" type="hidden" value="<?php echo $COURSE->id ?>"/>
                    <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
                    <button name="send" class="btn btn-success" onclick="document.getElementById('add_task').style.display = 'inherit'; this.form.submit();"><span class="glyphicon glyphicon-plus"></span> ADICIONAR</button>
                </div>
                </form>
              </div>
            </div>

          </div>
          <!-- ################################################################## -->

          <!-- ################################################################## -->
          <!--                        GRUPOS                                     -->
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane" id="groups">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">GRUPOS</h3>
              </div>
              <div class="panel-body">
                <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>GRUPO</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    global $DB;
                    $fpgroups = new stdClass();
                    //$fpgroups = $DB->get_records("fpgroups"); 
                    $fpgroups = $DB->get_records_sql("select * from mdl_fpgroups where id_curso = {$COURSE->id};");
                    //if(count($fpgroups)==0) echo ("Sem grupos<br>");
                    foreach ($fpgroups as $group){
                        echo '<tr><td>'.$group->nome.'</td><td>';    
                        echo "<a href='".$PAGE->url."&op=show_members&idg=".$group->id."'><button class='btn btn-info'><span class='glyphicon glyphicon-list'></span></button></a>";
                        echo "<a href='".$PAGE->url."&id_curso=$COURSE->id&op=up_group&idg=".$group->id."'><button class='btn btn-success'><span class='glyphicon glyphicon-pencil'></span></button></a>";
                        echo "<a href='./teacher_views/teacheractions_flip.php?action=rm_group&group_id=".$group->id."&url_local=".$PAGE->url."'><button class='btn btn-danger'><span class='glyphicon glyphicon-remove'></span></button></a>";
                        echo '</td>';
                        echo '</tr>';
                    }
                    //echo "ID DO CURSO = {$COURSE->id}";
                    ?>
                  </tbody>
                </table>
                <?php 
                $resp_add = optional_param('op',null,PARAM_TEXT); 
                if($resp_add == 'ok' && isset($_SESSION['idgroup'])){
                    unset($_SESSION['idgroup']);
                }
                ?>
                <?php
                $temp_group = $_SESSION['idgroup'];
                //echo var_dump($temp_group);
                if(!isset($temp_group)){  
                ?>
                <div class="col-md-8">
                  <button class="btn btn-primary" onclick="document.getElementById('add_group').style.display = 'inherit';"><span class="glyphicon glyphicon-plus"></span> ADICIONAR GRUPO</button>
                </div>
              </div>
              <div id="add_group" class="panel-body" style="display: <?php if(isset($_SESSION['idgroup'])) echo "inherit"; else echo "none";?>">
                <form class="form-horizontal" action="teacher_views/teacheractions_flip.php" method="POST">
                  <table class="table table-bordered table-condensed table-hover">
                    <tr><td><span style="font-weight: bold;">NOME DO GRUPO</span>&nbsp;&nbsp;<input id="gp_name" name="gp_name" type="text" size=40>&nbsp;&nbsp;<button class="btn btn-primary" onclick="this.form.submit()"><span class="glyphicon glyphicon-arrow-right"></span>  CRIAR</button></td></tr>
                  </table>
                  <input id="action" name="action" type="hidden" value="ad_group"/>
                  <input id="id_curso" name="id_curso" type="hidden" value="<?php echo $COURSE->id ?>"/>
                  <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">  
                </form>  
                <?php } else { ?>
                ADICIONANDO ALUNOS PARA: <?php echo "".$_SESSION['ntgroup']; ?>
                <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>ALUNO</th>
                      <th></th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $students = $DB->get_records_sql("SELECT
                    u.firstname,
                    u.id
                                                    
                    FROM 
                    mdl_role_assignments ra 
                    JOIN mdl_user u ON u.id = ra.userid
                    JOIN mdl_role r ON r.id = ra.roleid
                    JOIN mdl_context cxt ON cxt.id = ra.contextid
                    JOIN mdl_course c ON c.id = cxt.instanceid
                    
                    WHERE ra.userid = u.id
                                                    
                    AND ra.contextid = cxt.id
                    AND cxt.contextlevel =50
                    AND cxt.instanceid = c.id
                    AND  roleid = 5");
                    //("SELECT u.id, u.firstname,u.lastname, u.email FROM mdl_role_assignments rs INNER JOIN mdl_user u ON u.id=rs.userid INNER JOIN mdl_context e ON rs.contextid=e.id WHERE e.contextlevel=50 AND rs.roleid=5 AND e.instanceid=2");
                    //echo var_dump($students);
                    foreach($students as $student){
                      // talvez precise mudar a query para procurar somente por membros do curso atual, acho que nao precisa
                      $ismember = $DB->get_record('fpmembers', array("id_user" => $student->id));
                      // tratando se não existem registros na tabela de membros
                      if(!$ismember){
                        echo '<tr><td>'.$student->firstname.'</td><td>';
                        echo "<a href='./teacher_views/teacheractions_flip.php?action=ad_mmember&member_id=".$student->id."&url_local=".$PAGE->url."'><button class='btn btn-danger' onclick=''><span class='glyphicon glyphicon-bookmark'></span></button></a>";
                        echo "<a href='./teacher_views/teacheractions_flip.php?action=ad_gmember&member_id=".$student->id."&url_local=".$PAGE->url."'><button class='btn btn-success' onclick=''><span class='glyphicon glyphicon-plus'></span></button></a>";
                        echo '</td></tr>';
                      }
                      else if($student->id!=$ismember->id_user){
                          echo '<tr><td>'.$student->firstname.'</td><td>';
                          echo "<a href='./teacher_views/teacheractions_flip.php?action=ad_mmember&member_id=".$student->id."&url_local=".$PAGE->url."'><button class='btn btn-danger' onclick=''><span class='glyphicon glyphicon-bookmark'></span></button></a>";
                          echo "<a href='./teacher_views/teacheractions_flip.php?action=ad_gmember&member_id=".$student->id."&url_local=".$PAGE->url."'><button class='btn btn-success' onclick=''><span class='glyphicon glyphicon-plus'></span></button></a>";
                          echo '</td></tr>';        
                      }
                    }
                    ?>
                  </tbody>
                </table>      
                <div class="col-md-8">
                  <a href="<?php echo $PAGE->url?>&op=ok"><button id="button2id" name="button2id" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> CONCLUÍDO</button></a>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <!-- ################################################################## -->

          <div role="tabpanel" class="tab-pane" id="referencias">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">REFERÊNCIAS</h3>
              
              </div>
              <div class="panel-body">
                <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th style="width: 70%">REFERÊNCIA</th>
                      <th>TAREFA</th>
                      <th></th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    
                    <?php
                      
                      $refs = $DB->get_records_sql("select r.id,r.descricao, r.id_task, r.arquivo, r.id_course, t.nome from mdl_fpref r inner join mdl_fptasks t on r.id_task=t.id where id_course = {$COURSE->id} and id_course = id_curso;");
                      foreach( $refs as $ref){
                            echo "<tr><td>".$ref->descricao."</td><td>".$ref->nome."</td><td>";
                            echo "<a href='".$PAGE->url."&op=up_ref&id_curso={$COURSE->id}&idr=".$ref->id."'><button class='btn btn-success'><span class='glyphicon glyphicon-pencil'></span></button></a>";
                            echo "<a href='./teacher_views/teacheractions_flip.php?action=rm_ref&ref_arquivo=".$ref->arquivo."&ref_id=".$ref->id."&url_local=".$PAGE->url."'><button class='btn btn-danger'><span class='glyphicon glyphicon-remove'></span></button></a>";
                            echo "</td></tr>";
                      }
                      
                    ?>
                  </tbody>
                </table>
                    <div class="col-md-8">
                      <button class="btn btn-primary" onclick="document.getElementById('add_ref').style.display = 'inherit';"><span class="glyphicon glyphicon-plus"></span> ADICIONAR REFERÊNCIA</button>
                    </div>
                  
                

                

              </div>
                <form action="teacher_views/teacheractions_flip.php" method="POST" enctype="multipart/form-data">
              <div id="add_ref" class="panel-body" style="display: none">
                <table class="table table-bordered table-condensed table-hover">
                  <tbody>
                    <tr><td>REFERÊNCIA</td><td><input id="ref_desc" type="text" size="80" name="ref_desc"></td></tr>
                    <tr><td>ARQUIVO</td><td><input id="ref_file" type="file" name="arq"></td></tr>
                    <tr><td>TAREFA</td>
                        <td>
                            <select id="ref_id_task" name="ref_id_task">
                                <?php
                                    $temp_tasks = $DB->get_records_sql("select * from mdl_fptasks where id_curso = {$COURSE->id}");//$DB->get_records("fptasks");
                                    foreach( $temp_tasks as $task){
                                        echo "<option value='".$task->id."'>".$task->nome."</option>";
                                    }
                                ?>
                            </select></td></tr>  
                  </tbody>
                </table>

                    <div class="col-md-8">
                      <input id="action" name="action" type="hidden" value="ad_ref"/>
                      <input id="id_curso" name="id_curso" type="hidden" value="<?php echo $COURSE->id ?>"/>
                       <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">    
                      <!--<button name="send" class="btn btn-success" onclick="javascript:this.value='Enviando...'; this.disabled='disabled'; this.form.submit();"><span class="glyphicon glyphicon-plus"></span> ADICIONAR</button>-->
                      <button name="send" class="btn btn-success" onclick="this.form.submit();"><span class="glyphicon glyphicon-plus"></span> ADICIONAR</button>
                    </div>
              </div>
                </form>    
            </div>

          </div>
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane" id="aproveitamento">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">APROVEITAMENTO</h3>
              
              </div>
              <div class="panel-body">
                <table class="table table-bordered table-condensed table-hover">
                  <table class="table table-bordered table-condensed table-hover">
                      <tr><th>ALUNO</th><th>APROVEITAMENTO</th><th></th></tr>
                      <tbody>
                      <?php
                      // talvez precise mudar esse sql
                        //$students = $DB->get_records_sql("SELECT u.id, u.firstname,u.lastname, u.email FROM mdl_role_assignments rs INNER JOIN mdl_user u ON u.id=rs.userid INNER JOIN mdl_context e ON rs.contextid=e.id WHERE e.contextlevel=50 AND rs.roleid=5 AND e.instanceid=2");
                        $students = $DB->get_records_sql("SELECT
                        u.firstname,
                        u.id
                                                        
                        FROM 
                        mdl_role_assignments ra 
                        JOIN mdl_user u ON u.id = ra.userid
                        JOIN mdl_role r ON r.id = ra.roleid
                        JOIN mdl_context cxt ON cxt.id = ra.contextid
                        JOIN mdl_course c ON c.id = cxt.instanceid
                        
                        WHERE ra.userid = u.id
                                                        
                        AND ra.contextid = cxt.id
                        AND cxt.contextlevel =50
                        AND cxt.instanceid = c.id
                        AND  roleid = 5
                        AND c.id = {$COURSE->id}");
                        // mostrar o aproveitamento apenas se houver grupos criados
                        if (!empty($students)) {
                          foreach ($students as $student) {
                              $gain = new stdClass();
                              $gain = $DB->get_record('fpgain', array('id_user' => $student->id));
                              if(!empty($gain)) {
                              echo "<form action='teacher_views/teacheractions_flip.php' method='POST'>";
                              echo "<tr><td>".$student->firstname."</td><td><input id=\"gain\" name=\"gain\" type='number' value='".$gain->aproveitamento."' min='0' max='100'></td>";
                              echo "<input type='hidden' name='member_id' value='".$student->id."'>";
                              echo "<input type='hidden' name='url_local' value='".$PAGE->url."'>";
                              echo "<input type='hidden' name='action' value='up_gain'>";
                              echo "<td><button class='btn btn-success' onclick='this.form.submit()'><span class='glyphicon glyphicon-refresh'></span></button></a>";
                              echo "</td></tr></form>";
                            }
                          }
                        }
                    ?>
                    </tbody>      
                </table>

                
              </div>
            </div>

          </div>
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane" id="avaliar">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">AVALIAÇÃO DE GRUPOS</h3>
                </div>
                <div class="panel-body">
                 <form action="teacher_views/teacheractions_flip.php" method="POST">         
                 
                    
                 <div id="avalia_grupo" style="display: none">
                     <table class="table table-bordered table-condensed table-hover">
                     
                     <tr><th>CONSIDERAÇÕES SOBRE A TAREFA</th><th><span id="avagroup_name"></span></th></tr>
                     <tr><td colspan="2"><textarea id="comments" name="comments" style="width:100%; height: 200px"></textarea></td></tr>
                     <tr><td>NOTA <input id="nota" name="nota" type="text" size=30></td></tr>
                     <tr><td><button id="button2id" name="button2id" class="btn btn-success" onclick="javascript:this.value='Enviando...';  this.form.submit();"><span class="glyphicon glyphicon-ok"></span> ENVIAR</button></td></tr>
                          
                     </table>
                     <input type="hidden" id="aval_id" name="aval_id"/>
                     <input type="hidden" id="avagroup_id" name="avagroup_id"/>
                     <input type="hidden" id="avatask" name="avatask"/>
                     <input type="hidden" id="action" name="action" value="add_ava"/>
                     <input id="id_curso" name="id_curso" type="hidden" value="<?php echo $COURSE->id ?>"/>
                     <input type="hidden" name="url_local" value="<?php echo $PAGE->url ?>"/>
                 </div>
                 </form>     
                 <br><br>    
                 <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>GRUPO</th>
                      <th>NOTA</th>
                      <th>RESPOSTA</th>
                      <th style="text-align:center;">BAIXAR ANEXO</th>
                      <th>SITUAÇÃO</th>
                      <th>TAREFA</th>
                      <th>AÇÃO</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      // alterado aqui 30/10 15:47
                        $avagroups = $DB->get_records_sql("select  a.id, a.id_group, a.nota, a.feedback, a.situacao, a.id_task,g.nome,t.nome as task_name from mdl_fpavaliar a inner join mdl_fpgroups g on a.id_group=g.id  inner join mdl_fptasks t on t.id=a.id_task where a.id_course= {$COURSE->id} and a.id_course=t.id_curso and t.id_curso=g.id_curso;");
                        foreach($avagroups as $aval){
                            // ALTERADO AQUI BY FERNANDO 23/09 12:44(talvez precise mudar novamente os codigos sql para ter o id do curso)
                            echo "<tr><td>".$aval->nome."</td><td>".$aval->nota."</td><td>";
                            $anexo_grupo = $DB->get_record_sql("select nome_anexo from mdl_fpanexos where tarefa_id ={$aval->id_task} and grupo_id ={$aval->id_group};");
                            if($anexo_grupo == null){
                              echo "Sem Anexo</td><td></td><td>";
                            }else{
                              echo "{$anexo_grupo->nome_anexo}</td><td style=text-align:center;><a href=arquivos/anexos_grupos/{$anexo_grupo->nome_anexo} target=_blank class='btn btn-primary'>Baixar</a></td><td>";
                            }
                            if($aval->situacao==0){
                                echo "<span class='btn btn-warning'>PENDENTE</span></td><td>{$aval->task_name}</td>";    
                                echo "<td><button class='btn btn-primary' onclick=\"document.getElementById('avagroup_name').innerHTML='".$aval->nome."'; document.getElementById('avagroup_id').value='".$aval->id_group."';document.getElementById('aval_id').value='".$aval->id."'; document.getElementById('avatask').value='".$aval->id_task."'; document.getElementById('avalia_grupo').style.display = 'inherit';\"><span class='glyphicon glyphicon-pencil'></span> AVALIAR</button></td></tr>";
                                
                            }else{
                                echo "<span class='btn btn-success'>AVALIADO</span></td><td>{$aval->task_name}</td><td></td></tr>";
                            }

                            
                        }
                      
                      ?>
                      
                      
                      
                    
                  </tbody>
                </table>
                
                    <br><br><br>
                
              </div>
            </div>

          </div>
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane" id="feedback">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">FEEDBACK</h3>
                </div>
                <div class="panel-body">
                 <div id="feedback_aluno" style="display: none">
                     <table class="table table-bordered table-condensed table-hover">
                     <form action="teacher_views/teacheractions_flip.php" method="POST">
                     <tr><th>CONSIDERAÇÕES SOBRE A TAREFA</th><th><span id="fbaluno" name="fbaluno"></span></th>
                         <th><select id="taskfb_name" name="taskfb_name">
                             <?php
                                $stasks = $DB->get_records_sql("select * from mdl_fptasks where id_curso = {$COURSE->id}");//$DB->get_records("fptasks");//$DB->get_records('fptasks');
                                foreach( $stasks as $task){
                                    echo "<option value=".$task->id.">".$task->nome."</option>";
                                }
                             ?>
                            </select>  </th></tr>
                     <tr><td colspan="3"><textarea id="comments" name="comments" style="width:100%; height: 200px"></textarea></td></tr>
                     <tr><td colspan="3">
                         <input type="hidden" id="taskfb_idaluno" name="taskfb_idaluno"/>
                         <input type="hidden" id="action" name="action" value="add_feedback"/>
                         <input id="id_curso" name="id_curso" type="hidden" value="<?php echo $COURSE->id ?>"/>
                         <input type="hidden" name="url_local" value="<?php echo $PAGE->url ?>"/>
                         <button id="button2id" name="button2id" class="btn btn-success" onclick="javascript:this.value='Enviando...'; this.display:'none'; this.form.submit();"><span class="glyphicon glyphicon-ok"></span> ENVIAR</button></td></tr>
                         </form>
                     </table>
                 </div>        
                 <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>ALUNO</th>
                      <th>GRUPO</th>
                      <th></th>
                      
                    </tr>
                  </thead>
                  <tbody>
                      <?php 
                      // talvez precise mudar esse sql tb
                        $students = $DB->get_records_sql("select u.id, firstname, lastname,nome from mdl_user u inner join mdl_fpmembers fm ON u.id = fm.id_user inner join mdl_fpgroups fg ON fg.id=fm.id_group where fg.id_curso = {$COURSE->id};");
                        foreach($students as $student){
                            echo "<tr><td>".$student->firstname." ".$student->lastname."</td><td>".$student->nome."</td>";
                            echo "<td><button class=\"btn btn-primary\" onclick=\"document.getElementById('taskfb_idaluno').value='".$student->id."'; document.getElementById('fbaluno').innerHTML='".$student->firstname." ".$student->lastname."'; document.getElementById('feedback_aluno').style.display = 'inherit';\"><span class=\"glyphicon glyphicon-arrow-right\"></span> FEEDBACK</button></td></tr>";
                        }
                      ?>
                      
                      
                    
                  </tbody>
                </table>
                
                    <br><br><br>
                
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
function exibirMensagem($msg) { ?>
  <script type="text/javascript">alert('<?php echo $msg?>')</script>
<?php 
}
?>