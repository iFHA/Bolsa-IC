<?php
/**
 *
 * @package   mod_invert_classroom
 * @category  groups
 * @copyright 2018 Fernando Henrique Alves
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once(dirname(dirname(__FILE__)).'/lib.php');
require_once(dirname(dirname(__FILE__)).'/locallib.php');

$invertclass->goals = get_goals($cm->id); // pega os 'goals' e requisitos do módulo

?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#problem" aria-controls="problem" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-home"></i>TAREFA</a></li>
          <li role="presentation"><a href="#referencias" aria-controls="referencias" role="tab" data-toggle="tab">REFERÊNCIAS</a></li>
          <li role="presentation"><a href="#groups" aria-controls="groups" role="tab" data-toggle="tab">GRUPOS</a></li>
          <li role="presentation"><a href="#avaliar" aria-controls="avaliar" role="tab" data-toggle="tab">AVALIAR</a></li>
          <li role="presentation"><a href="#feedback" aria-controls="feedback" role="tab" data-toggle="tab">FEEDBACK</a></li>
        </ul>
        <br />
        <?php
        $op = optional_param('op',null,PARAM_TEXT);
        switch($op){
          case 'show_etapas':
            echo "<div>";
            include(dirname(dirname(__FILE__)).'/teacher_views/show_etapas.php'); 
            echo "</div>";
            echo "<div class='tab-content' style='display: none'>";
          break;
          case 'show_sessionsss':
            echo "<div>";
            include(dirname(dirname(__FILE__)).'/teacher_views/show_sessions.php'); 
            echo "</div>";
            echo "<div class='tab-content' style='display: none'>";
          break;
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
          case 'show_sessions':
            echo "<div>";
            include(dirname(dirname(__FILE__)).'/teacher_views/group_sessions.php'); 
            echo "</div>";
            echo "<div class='tab-content' style='display: none'>";
          break;
          default: 
            echo "<div class='tab-content'>";
          break;
        }
        ?>
        <!-- ################################################################## -->
        <!--                    EXIBIÇÃO DO MÓDULO INVERTCLASS                  -->
        <!-- ################################################################## -->
        <?php
        $modulo = $DB->get_record_sql('select * from mdl_invertclass where id in(select instance from mdl_course_modules as cm where cm.id = '.$cm->id.');');
        $modulo->arquivoZin = $DB->get_record('fpanexos', array('id' => $modulo->arquivoid));
        $thereIsFile = false;
        if(!empty($modulo->arquivoZin)){
          $thereIsFile = true;
        }
        ?>
        <div role="tabpanel" class="tab-pane active" id="problem">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">DADOS DA ATIVIDADE <?=$modulo->name?></h3>
            </div>
            <div class="panel-body">
              <form action="teacher_views/teacheractions_flip.php" method="POST" enctype="multipart/form-data">
                <div id="add_task">
                  <table class="table table-hover">
                    <tr>
                      <th>NOME DA TAREFA:</th>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <input id="nome" type="text" size=67 name="nome" value="<?php echo $modulo->name; ?>">
                      </td>
                    </tr>
                    <tr>
                      <th>DESCRIÇÃO:</th>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <textarea class="form-control" name="descricao" style="width:100%; height: 80px"><?php echo $modulo->descricao; ?></textarea>
                      </td>
                    </tr>
                    <tr>
                      <th>ARQUIVO:</th>
                    </tr>
                    <tr>
                      <?php
                      if($thereIsFile){
                      ?>
                      <td><?php echo $modulo->arquivoZin->nome_original; ?>
                        <a href="arquivos/tarefas/<?php echo $modulo->arquivoZin->nome_final; ?>" target="_blank" class="btn btn-primary">Baixar</a>
                      </td>
                      <?php
                      }?>
                      <td>
                        <input id="arq" type="file" name="arq">
                      </td>
                    </tr> 
                    <tr>
                      <th>DATA INÍCIO</th>
                      <td>
                        <input id="data_inicio" type="date" style="height:30px" name="data_inicio" value="<?php echo $modulo->data_inicio ?>">
                      </td>
                    </tr>
                    <tr>
                      <th>DATA FIM</th>
                      <td>
                        <input id="data_fim" type="date" style="height:30px" name="data_fim" value="<?php echo $modulo->data_fim ?>">
                      </td>
                    </tr>
                  </table>
                  <input id="action" name="action" type="hidden" value="up_task"/>
                  <input id="id" name="id" type="hidden" value="<?php echo $modulo->id ?>"/>
                  <input id="tarq" name="task_arq" type="hidden" value="<?php echo empty($modulo->arquivo)?"":$modulo->arquivo ?>"/>
                  <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
                  <button name="send" class="btn btn-success" onclick="document.getElementById('add_task').style.display = 'inherit'; this.form.submit();"><span class="glyphicon glyphicon-floppy-disk"></span>ATUALIZAR TAREFA</button>
                </div>
              </form>
            </div>
          </div>
          <!-- ===================== REQUISITOS DA TAREFA ==================== -->
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title"> REQUISITOS DA TAREFA</h3>
            </div>
            <div class="panel-body">
              <?php
              if(!empty($invertclass->goals)){
              ?>
              <table class="table table-bordered table-condensed table-hover">
                <thead>
                  <tr>
                    <th>Descrição do Requisito</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  foreach ($invertclass->goals as $goal) {
                  ?>
                  <tr>
                    <td> <?php echo $goal->feature->description; ?>
                      <a href="teacher_views/teacheractions.php?id=<?php echo $cm->id; ?>&goalid=<?php echo $goal->id; ?>&featureid=<?php echo $goal->featureid; ?>&action=delete_problem_goal&url_local=<?php echo urlencode($PAGE->url); ?>" id="btn-del-cloned-input" name="btn-del-cloned-input" class="btn btn-danger btn-xs pull-right" onclick="return confirm(\'Deseja realmente excluir esse requisito?\');">
                        <span class="glyphicon glyphicon-minus"></span> Remover
                      </a>
                    </td>
                  </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
              <?php
              }
              ?>
              <form action="teacher_views/teacheractions.php" method="POST" class="col-md-12">
                <input id="id" name="id" type="hidden" value="<?php echo $cm->id; ?>">
                <input id="action" name="action" type="hidden" value="<?php echo 'add_problem_goal'; ?>">
                <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
                <div class="form-group">
                  <label>Descrição</label>
                  <input id="goal_description" name="goal_description" class="form-control" />
                </div>
                <button id="button2id" name="button2id" class="btn btn-primary" onclick="javascript:this.value='Enviando...'; this.disabled='disabled'; this.form.submit();"><span class="glyphicon glyphicon-plus"></span> ADICIONAR REQUISITO</button>
              </form>
            </div>
          </div>
          <!-- ===================== ETAPAS DA TAREFA ==================== -->
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">ETAPAS DA TAREFA</h3>
            </div>
            <div class="panel-body">
              <?php
              $invertclass->etapas = get_etapas($cm->id);
              if(!empty($invertclass->etapas)){ 
              ?>
              <table class="table table-bordered table-condensed table-hover">
                <thead>
                  <tr>
                    <th>Descrição da Etapa</th>
                    <th>Prazo</th>
                    <th>Ação</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $isUltimaEtapaCriada = false;
                  foreach ($invertclass->etapas as $etapa) {
                    if($etapa->ultima == 1){
                      $isUltimaEtapaCriada = true;
                    }
                    $datafim = explode('-', $etapa->data_fim);
                  ?>
                    <tr>
                      <td><?php echo $etapa->descricao; ?></td>
                      <td><?php echo $datafim[2]; ?>/<?php echo $datafim[1]; ?>/<?php echo $datafim[0]; ?></td>
                      <td>
                        <button class="btn btn-success" onClick="updateStep(this);">
                          <input data-js="etapaid" type="hidden" value="<?php echo $etapa->id;?>" />
                          <input data-js="descricao" type="hidden" value="<?php echo $etapa->descricao;?>" />
                          <input data-js="dataFim" type="hidden" value="<?php echo $etapa->data_fim;?>" />
                          <input data-js="ultima" type="hidden" value="<?php echo $etapa->ultima;?>" />
                          <span class="glyphicon glyphicon-pencil"></span> Alterar
                        </button>
                        <a href="teacher_views/teacheractions_flip.php?moduleid=<?php echo $cm->id;?>&etapaid=<?php echo $etapa->id;?>&action=delete_invertclass_step&url_local=<?php echo urlencode($PAGE->url);?>" id="btn-del-cloned-input" name="btn-del-cloned-input" class="btn btn-danger btn-xs pull-right" onclick="return confirm(\'Deseja realmente excluir essa etapa?\');">
                          <span class="glyphicon glyphicon-minus"></span> Remover
                        </a>
                      </td>
                    </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
              <?php
              }
              if(!$isUltimaEtapaCriada){
              ?>
              <form action="teacher_views/teacheractions_flip.php" method="POST" class="col-md-12">
                <input id="id" name="id" type="hidden" value="<?php echo $cm->id; ?>">
                <input id="action" name="action" type="hidden" value="add_invertclass_step">
                <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
                <input name="moduleid" type="hidden" value="<?php echo $cm->id; ?>">
                <div class="form-group">
                  <label>Descrição</label>
                  <input class="form-control" name="descricao" required/>
                </div>
                <div class="form-group">
                  <label>Data de Início: </label>
                  <input type="date" style="height:30px" name="data_inicio" value="<?php echo date('Y-m-d');?>" min="<?php echo date('Y-m-d');?>"required/>
                </div>
                <div class="form-group">
                  <label>Data de Término: </label>
                  <input type="date" style="height:30px" name="data_fim" min="<?php echo date('Y-m-d');?>" required/>
                </div>
                <dl>
                  <dt>Tipo: </dt>
                  <dd>
                    <div class="radio">
                      <label> <input type="radio" name="tipo" id="tipo_1" value="1" > Subjetiva </label>
                    </div>
                    <div class="radio">
                      <label> <input type="radio" name="tipo" id="tipo_2" value="0" checked > Necessário enviar arquivo </label>
                    </div>
                  </dd>
                  <dt>Última etapa? </dt>
                  <dd>
                    <div class="radio">
                      <label> <input type="radio" name="last" id="last_1" value="1" > Sim </label>
                    </div>
                    <div class="radio">
                      <label> <input type="radio" name="last" id="last_2" value="0" checked > Não </label>
                    </div>
                  </dd>
                </dl>
                <input type="submit" class="btn btn-success" value="ADICIONAR ETAPA">
              </form>
              <?php 
              }
              ?>
            </div>
          </div>
        </div>
        <!-- ################################################################## -->
        <!--                             GRUPOS                                 -->
        <!-- ################################################################## -->
        <div role="tabpanel" class="tab-pane" id="groups">
          <?php
          if(there_is_steps($cm->id)){ // se existe alguma etapa considerada como ultima da tarefa
          $fpgroups = $DB->get_records_sql('select * from mdl_fpgroups where moduleid = '.$cm->id.';');
          ?>
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">GRUPOS CRIADOS</h3>
            </div>
            <?php
            if(!empty($fpgroups)) {
            ?>
            <div class="panel-body">
              <table class="table table-bordered table-condensed table-hover">
                <thead>
                  <tr>
                    <th>NOME DO GRUPO</th>
                    <th>AÇÃO</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($fpgroups as $group){?>
                      <tr>
                        <td><?=$group->nome?></td>
                        <td>
                          <!--
                          <a href="<?=$PAGE->url?>&op=show_members&idg=<?=$group->id?>">
                            <button class='btn btn-info'><span class='glyphicon glyphicon-list'></span></button>
                          </a>
                          -->
                          <a href="<?=$PAGE->url?>&moduleid=<?=$cm->id?>&id_curso=<?=$cm->course?>&op=up_group&idg=<?=$group->id?>">
                            <button class='btn btn-success'>
                              <span class='glyphicon glyphicon-pencil'></span> Visualizar / Editar
                            </button>
                          </a>
                          <a href="./teacher_views/teacheractions_flip.php?action=rm_group&group_id=<?=$group->id?>&url_local=<?=$PAGE->url?>">
                            <button class='btn btn-danger'>
                            <span class='glyphicon glyphicon-remove'></span> Remover
                            </button>
                          </a>
                        </td>
                      </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
              
            </div>
            
            <?php 
              } else { ?>
                <br/>
                <div class="alert alert-danger" role="alert">
                  Nenhum grupo foi encontrado!
                </div>
              <?php 
              }
            ?>
            <?php 
            $resp_add = optional_param('op',null,PARAM_TEXT); 
            if($resp_add == 'ok' && isset($_SESSION['idgroup'])){
                unset($_SESSION['idgroup']);
                exibirMensagem('Grupo Criado!');
            }
            ?>
            <?php
            //$temp_group = $_SESSION['idgroup'];
            if(!isset($_SESSION['idgroup'])){
            ?>
            <div class="col-md-8">
              <button class="btn btn-primary" onclick="document.getElementById('add_group').style.display = 'inherit';"><span class="glyphicon glyphicon-plus"></span> ADICIONAR GRUPO</button>
            </div>
            <br/><br/><br/>
            <div id="add_group" class="panel-body" style="display: <?php if(isset($_SESSION['idgroup'])) echo "inherit"; else echo "none";?>">
              <form class="form-horizontal" action="teacher_views/teacheractions_flip.php" method="POST">
                <table class="table table-bordered table-condensed table-hover">
                  <tr><td><span style="font-weight: bold;">NOME DO GRUPO</span>&nbsp;&nbsp;<input id="gp_name" name="gp_name" type="text" size=40>&nbsp;&nbsp;<button class="btn btn-primary" onclick="this.form.submit()"><span class="glyphicon glyphicon-arrow-right"></span>  CRIAR</button></td></tr>
                </table>
                <input id="action" name="action" type="hidden" value="ad_group"/>
                <input id="moduleid" name="moduleid" type="hidden" value="<?php echo $cm->id; ?>"/>
                <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">  
              </form>  
              <?php } else { ?>
            <div>
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
                  // TODO: pegando todos os alunos cadastrados no curso
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
                  AND c.id = $cm->course
                  AND  roleid = 5");
                  //("SELECT u.id, u.firstname,u.lastname, u.email FROM mdl_role_assignments rs INNER JOIN mdl_user u ON u.id=rs.userid INNER JOIN mdl_context e ON rs.contextid=e.id WHERE e.contextlevel=50 AND rs.roleid=5 AND e.instanceid=2");
                  //echo var_dump($students);
                  foreach($students as $student){
                    // talvez precise mudar a query para procurar somente por membros do curso atual, acho que nao precisa
                    $ismember = $DB->get_record_sql('SELECT m.id, m.id_user, m.id_group FROM mdl_fpmembers as m, mdl_fpgroups as g WHERE g.id = m.id_group AND m.id_user ='. $student->id.' AND g.moduleid ='.$cm->id);
                    // tratando se não existem registros na tabela de membros
                    if(!$ismember){
                      echo '<tr><td>'.$student->firstname.'</td><td>';
                      //echo "<a href='./teacher_views/teacheractions_flip.php?action=ad_mmember&member_id=".$student->id."&url_local=".$PAGE->url."'><button class='btn btn-danger' onclick=''><span class='glyphicon glyphicon-bookmark'></span></button></a>";
                      echo "<a href='./teacher_views/teacheractions_flip.php?action=ad_gmember&member_id=".$student->id."&url_local=".$PAGE->url."'><button class='btn btn-success' onclick=''><span class='glyphicon glyphicon-plus'></span></button></a>";
                      echo '</td></tr>';
                    }
                    else if($student->id!=$ismember->id_user){
                        echo '<tr><td>'.$student->firstname.'</td><td>';
                        //echo "<a href='./teacher_views/teacheractions_flip.php?action=ad_mmember&member_id=".$student->id."&url_local=".$PAGE->url."'><button class='btn btn-danger' onclick=''><span class='glyphicon glyphicon-bookmark'></span></button></a>";
                        echo "<a href='./teacher_views/teacheractions_flip.php?action=ad_gmember&member_id=".$student->id."&url_local=".$PAGE->url."'><button class='btn btn-success' onclick=''><span class='glyphicon glyphicon-plus'></span></button></a>";
                        echo '</td></tr>';        
                    }
                  }
                  ?>
                </tbody>
              </table>      
              <div>
                <a href="<?php echo $PAGE->url?>&op=ok"><button id="button2id" name="button2id" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> CONCLUÍDO</button></a>
              </div>
              <?php } ?>
            </div>
          </div>
          <!-- ================================= RECOMENDAÇÃO DE GRUPOS ===================================== -->
          <div class="col-md-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">GRUPOS RECOMENDADOS</h3>
              </div>
              <div class="panel-body">
                <table class="table table-hover">
                  <thead>
                  <th>NOME DO GRUPO</th>
                  <th>INTEGRANTES</th>
                  <th>AÇÃO</th>
                  </thead>
                  <tbody>
                    <?php 
                    $grupos_recomendados = get_grupos_recomendados($cm->id);
                    $_SESSION['grupos_recomendados'] = $grupos_recomendados;
                    foreach($grupos_recomendados as $groupindex => $group){ ?>
                      <tr>
                        <td>
                          <?php echo $group->nome;?>
                        </td>
                        <td>
                          <?php
                          $tempIndex = 0;
                          foreach ($group->membros as $membro) {
                            if($tempIndex + 1 < count($group->membros))
                              echo $membro->nome.', ';
                            else 
                              echo $membro->nome.'.';
                            $tempIndex++;
                          }
                          $tempIndex = 0;
                          ?>
                        </td>
                        <td><!-- if($group->vinculado == 0) {-->
                          <form action="teacher_views/teacheractions_flip.php" method="POST">
                            <input type="hidden" name="groupindex" value="<?php echo $groupindex; ?>" />
                            <input type="hidden" name="moduleid" value="<?php echo $cm->id; ?>" />
                            <input type="hidden" name="action" value="gvinculation" />
                            <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
                            <!-- id='.$cm->id
                            groupid='.$group->id.'&
                            action=link_group&
                            url_local='.urlencode($PAGE->url). -->
                            <button class="btn btn-success" onclick="this.form.submit();">
                              Vincular
                            </button>
                          </form>
                          <!-- } else {
                            <button class="btn btn-warning">
                              Vinculado
                            </button>
                          } -->
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
          <?php 
              } else{ ?>
                <div class="alert alert-danger" role="alert">
                  Crie pelo menos uma Etapa (na aba "Tarefa").
                </div>
            <?php
              }
            ?>
        </div>
        <!-- ################################################################## -->
        <!-- ################################REFERÊNCIAS################################## -->
        <div role="tabpanel" class="tab-pane" id="referencias">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">REFERÊNCIAS</h3>
            </div>
            <div class="panel-body">
              <table class="table table-bordered table-condensed table-hover">
                <thead>
                  <tr>
                    <th style="width: 70%">DESCRIÇÃO</th>
                    <th>AÇÃO</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  //TODO: VERIFICAR SE ESTA CONSULTA ESTÁ CORRETA
                    $refs = $DB->get_records_sql('select r.id,r.descricao, r.moduleid, r.arquivo, i.name 
                    from mdl_fpref r inner join mdl_course_modules m on r.moduleid=m.id inner join mdl_invertclass i on i.id=m.instance where moduleid = '.$cm->id.';');
                    if(!empty($refs)){
                      foreach ($refs as $ref){
                  ?>
                  <tr>
                    <td><?=$ref->descricao?></td>
                    <td> <!--
                      <a href="<?php //echo $PAGE->url?>&op=up_ref&moduleid=<?php //echo $cm->id?>&idr=<?php //echo $ref->id?>">
                        <button class='btn btn-success'>
                          <span class='glyphicon glyphicon-pencil'></span>
                        </button>
                      </a> -->
                      <a href='./teacher_views/teacheractions_flip.php?action=rm_ref&ref_arquivo=<?=$ref->arquivo?>&ref_id=<?=$ref->id?>&url_local=<?=$PAGE->url?>'>
                        <button class='btn btn-danger'>
                          <span class='glyphicon glyphicon-remove'></span>
                        </button>
                      </a>
                    </td>
                  </tr>
                  <?php
                    }
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
                    <tr><td>DESCRIÇÃO</td><td><input id="ref_desc" type="text" size="80" name="ref_desc"></td></tr>
                    <tr><td>ARQUIVO</td><td><input id="ref_file" type="file" name="arq"></td></tr>
                </tbody>
              </table>
                  <div class="col-md-8">
                    <input id="action" name="action" type="hidden" value="ad_ref"/>
                    <input id="id_curso" name="id_curso" type="hidden" value="<?php echo $cm->course ?>"/>
                    <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
                    <input name="moduleid" type="hidden" value="<?php echo $cm->id; ?>">
                    <!--<button name="send" class="btn btn-success" onclick="javascript:this.value='Enviando...'; this.disabled='disabled'; this.form.submit();"><span class="glyphicon glyphicon-plus"></span> ADICIONAR</button>-->
                    <button name="send" class="btn btn-success" onclick="this.form.submit();"><span class="glyphicon glyphicon-plus"></span> ADICIONAR</button>
                  </div>
              </div>
            </form>    
          </div>
        </div>
        <!-- ################################################################## -->
        <!-- ############################# AVALIAÇAO DE GRUPOS ##################################### -->
        <div role="tabpanel" class="tab-pane" id="avaliar">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">AVALIAÇÃO DE GRUPOS</h3>
            </div>
            <div class="panel-body">
              <form action="teacher_views/teacheractions_flip.php" method="POST">
                <div id="avalia_grupo" style="display: none">
                  <table class="table table-bordered table-condensed table-hover">
                    <tr><th>CONSIDERAÇÕES SOBRE A TAREFA</th><th><span id="avagroup_name">GRUPO 1</span></th></tr>
                    <tr><td colspan="2"><textarea id="comments" name="comments" style="width:100%; height: 200px"></textarea></td></tr>
                    <!-- <input type="number" data-featureid="<?php echo $feature->featureid; ?>" min="0" max="10" value="<?php echo $feature->value; ?>"> -->
                    <tr><td>NOTA <input name="nota" type="number" min="0" max="10" value="0" ></td></tr>
                    <tr><td><button id="button2id" name="button2id" class="btn btn-success" onclick="javascript:this.value='Enviando...';  this.form.submit();"><span class="glyphicon glyphicon-ok"></span> ENVIAR</i></td></tr>
                  </table>
                  <input type="hidden" id="aval_id" name="aval_id"/>
                  <input type="hidden" id="avagroup_id" name="avagroup_id"/>
                  <input type="hidden" id="action" name="action" value="add_ava"/>
                  <input id="moduleid" name="moduleid" type="hidden" value="<?php echo $cm->id ?>"/>
                  <input type="hidden" name="url_local" value="<?php echo $PAGE->url ?>"/>
                </div>
              </form>
              <br>
              <br>    
              <table class="table table-bordered table-condensed table-hover">
                <thead>
                  <tr>
                    <th>GRUPO</th>
                    <th>NOTA</th>
                    <!-- <th>RESPOSTA</th>
                    <th style="text-align:center;">BAIXAR ANEXO</th> -->
                    <th>SITUAÇÃO</th>
                    <!-- <th>TAREFA</th> -->
                    <th>AÇÃO</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // TODO: VERIFICAR SE AS CONSULTAS ESTÃO CORRETAS
                  $avagroups = $DB->get_records_sql('SELECT a.id, a.id_group, a.nota, a.feedback, a.situacao, a.moduleid,g.nome,i.name as task_name 
                  from mdl_fpavaliar a inner join mdl_fpgroups g on a.id_group=g.id inner join mdl_course_modules m on m.id=a.moduleid inner join mdl_invertclass i on i.id=m.instance
                  where m.id= '.$cm->id.';');
                  foreach($avagroups as $aval){ ?>
                  <tr>
                    <td><?php echo $aval->nome; ?></td>
                    <td><?php echo $aval->nota; ?></td>
                    <?php
                    /*
                    $anexo_grupo = $DB->get_record_sql('SELECT nome_original from mdl_fpanexos a inner join mdl_fpgroups g on g.anexoid=a.id where g.id = '.$aval->id_group.';');
                    if($anexo_grupo == null){?>
                    <td>Sem Anexo</td>
                    <td></td>
                    <?php
                    }else{?>
                    <td><?php echo $anexo_grupo->nome_anexo; ?></td>
                    <td style=text-align:center;><a href=arquivos/anexos_grupos/<?php echo $anexo_grupo->nome_anexo; ?> target=_blank class='btn btn-primary'>Baixar</a></td>
                    <?php
                    } ?> <?php 
                    */ if($aval->situacao==0){?>
                    <td><span class='btn btn-warning'>PENDENTE</span></td>
                    <!-- <td><?php // echo $aval->task_name; ?></td> -->
                    <td>
                      <button class='btn btn-primary' onclick="document.getElementById('avalia_grupo').style.display = 'inherit'; document.getElementById('avagroup_name').innerHTML='<?php echo $aval->nome;?>'; document.getElementById('avagroup_id').value='<?php echo $aval->id_group; ?>'; document.getElementById('aval_id').value='<?php echo $aval->id; ?>';">
                        <span class='glyphicon glyphicon-pencil'>
                        </span> 
                        AVALIAR
                      </button>
                      <a href="<?php echo $PAGE->url; ?>&op=show_etapas&idg=<?=$aval->id_group?>&gnome=<?=$aval->nome?>">
                        <button class='btn btn-info'><span class='glyphicon glyphicon-list'></span> VISUALIZAR RESPOSTAS</button>
                      </a>
                    </td>
                  </tr>
                    <?php
                    }else{?>
                    <td>
                      <span class='btn btn-success'>AVALIADO</span>
                      <td>
                        <a href="<?php echo $PAGE->url; ?>&op=show_etapas&idg=<?=$aval->id_group?>&gnome=<?=$aval->nome?>">
                          <button class='btn btn-info'><span class='glyphicon glyphicon-list'></span> VISUALIZAR RESPOSTAS</button>
                        </a>
                      </td>
                    </td>
                    <!-- <td><?php //echo $aval->task_name; ?></td> -->
                    <td></td>
                  </tr>
                  <?php
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
              <div id="feedback_aluno" style="display:none">
                <table class="table table-bordered table-condensed table-hover">
                  <form action="teacher_views/teacheractions_flip.php" method="POST">
                    <tr>
                      <th>CONSIDERAÇÕES SOBRE A TAREFA</th>
                      <th>
                        <select id="taskfb_name" name="taskfb_name">
                          <?php
                          $stasks = $DB->get_records_sql('select * from mdl_invertclass where id = '.$cm->instance.';');//$DB->get_records("fptasks");//$DB->get_records('fptasks');
                          foreach( $stasks as $task){?>
                          <option value="<?=$task->id?>">
                            <?=$task->name?>
                          </option>
                          <?php
                          }
                          ?>
                        </select>
                      </th>
                      <th>
                        <span id="fbaluno" name="fbaluno">Maria José
                        </span>
                      </th>
                    </tr>
                    <tr>
                      <td colspan="3">
                        <textarea id="comments" name="comments" style="width:100%; height: 200px">
                        </textarea>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3">
                        <input type="hidden" id="taskfb_idaluno" name="taskfb_idaluno"/>
                        <input type="hidden" id="action" name="action" value="add_feedback"/>
                        <input name="moduleid" type="hidden" value="<?php echo $cm->id ?>"/>
                        <input type="hidden" name="url_local" value="<?php echo $PAGE->url ?>"/>
                        <button id="button2id" name="button2id" class="btn btn-success" onclick="javascript:this.value='Enviando...'; this.display:'none'; this.form.submit();">
                          <span class="glyphicon glyphicon-ok">
                          </span> ENVIAR
                        </button>
                      </td>
                    </tr>
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
                  //TODO: talvez precise mudar esse sql tb
                  $students = $DB->get_records_sql("SELECT u.id, firstname, lastname,nome from 
                  mdl_user u inner join mdl_fpmembers fm ON u.id = fm.id_user inner join mdl_fpgroups fg ON fg.id=fm.id_group 
                  where fg.moduleid = {$cm->id};");
                  foreach($students as $student){?>
                  <tr>
                    <td>
                      <?=$student->firstname.' '.$student->lastname?>
                    </td>
                    <td>
                      <?=$student->nome?>
                    </td>
                    <td>
                      <button class="btn btn-primary" onclick="document.getElementById('taskfb_idaluno').value='<?=$student->id?>'; document.getElementById('fbaluno').innerHTML='<?=$student->firstname." ".$student->lastname?>'; document.getElementById('feedback_aluno').style.display = 'inherit';">
                        <span class=\"glyphicon glyphicon-arrow-right\">
                        </span> 
                        FEEDBACK
                      </button>
                    </td>
                  </tr>
                  <?php
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
</div>
</div>
<div class="container col-md-12">
  <!-- Modal -->
  <div class="modal fade" data-js="modal-update-step" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
            <h4><span class="glyphicon glyphicon-lock"></span> Alterando Etapa</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
          <form action="teacheractions_flip.php" data-js="form-update-step" method="POST">
            <input name="etapaid" type="hidden" data-js="update-step-etapaid" />
            <input name="url" type="hidden" data-js="update-step-url" value="<?php echo urlencode($PAGE->url); ?>"/>
            <input name="action" type="hidden" value="update_invertclass_step" />
            <div class="form-group">
              <label>Descrição:</label>
              <textarea class="form-control" name="descricao" data-js="update-step-description" rows="5" required></textarea>
            </div>
            <div class="form-group">
              <label>Data de Término: </label>
              <input type="date" data-js="update-step-date" style="height:30px" name="data_fim" required/>
            </div>
            <dl>
              <dt>Última etapa? </dt>
              <dd>
                <div class="radio">
                  <label> <input type="radio" data-js="update-step-last1" name="last" id="last_1" value="1" > Sim </label>
                </div>
                <div class="radio">
                  <label> <input type="radio" name="last" data-js="update-step-last2" id="last_2" value="0" > Não </label>
                </div>
              </dd>
            </dl>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Concluir Alteração</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div> 
</div>

<!-- Modal -->
<div data-js="alert-modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Invertclass</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <p data-js="modal2-text">Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

  <!-- FIM DA EXIBIÇÃO DO INVERTCLASS -->

  <br/>

<script src="js/ajax.js"></script>
<script src="js/teacher_flip_view.js"></script>
<?php
function exibirMensagem($msg) { ?>
  <script type="text/javascript">alert('<?php echo $msg?>')</script>
<?php 
}
?>