<?php
/**
 *
 * @package   mod_invert_classroom
 * @category  groups
 * @copyright 2014 Alguém
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once(dirname(dirname(__FILE__)).'/lib.php');
require_once(dirname(dirname(__FILE__)).'/locallib.php');

// variávis auxiliares
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

?>

<div class="container-fluid">
  <div class="row"><!-- INÍCIO DA EXIBIÇÃO DO GRUPO -->
    <div class="col-md-12">
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation"class="active"><a href="#tarefas" aria-controls="tarefas" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-home"></i>TAREFA</a></li>
          <li role="presentation"><a href="#referencias" aria-controls="referencias" role="tab" data-toggle="tab">REFERÊNCIAS</a></li>
          <li role="presentation"><a href="#area" aria-controls="area" role="tab" data-toggle="tab">ÁREAS DE CONHECIMENTO</a></li>
          <li role="presentation"><a href="#groups" aria-controls="groups" role="tab" data-toggle="tab">GRUPOS</a></li>
          <li role="presentation"><a href="#aproveitamento" aria-controls="aproveitamento" role="tab" data-toggle="tab">APROVEITAMENTO</a></li>
          <li role="presentation"><a href="#avaliar" aria-controls="avaliar" role="tab" data-toggle="tab">AVALIAR</a></li>
          <li role="presentation"><a href="#feedback" aria-controls="feedback" role="tab" data-toggle="tab">FEEDBACK</a></li>
          <li role="presentation"><a href="#sessoes" aria-controls="sessoes" role="tab" data-toggle="tab">SESSÕES</a></li>
        </ul>

        <br />
        <?php
          $op = optional_param('op',null,PARAM_TEXT);
          switch($op){
            case 'show_sessions':
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
            default: 
              echo "<div class='tab-content'>";
              break;
          }
        ?>

          <!-- ################################################################## -->
          <!--                      EXIBIÇÃO DO MÓDULO                         -->
          <!-- ################################################################## -->
          <?php
            $invertclass = $DB->get_record_sql('select * from mdl_invertclass where id in(select instance from mdl_course_modules as cm where cm.id = '.$cm->id.');');
          ?>
          <div role="tabpanel" class="tab-pane active" id="tarefas">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">DADOS DA TAREFA <?=$invertclass->name?></h3>
              </div>
              <div class="panel-body">
                <form action="teacher_views/teacheractions_flip.php" method="POST" enctype="multipart/form-data">
                  <div id="add_task"><!-- style="display: none" -->
                    <table class="table table-bordered table-condensed table-hover">
                      <tr><th>NOME DA TAREFA</th><td><input id="nome" type="text" size=67 name="nome" value="<?=$invertclass->name?>"></td></tr>
                      <tr><th colspan="3">DESCRIÇÃO</th></tr>
                      <tr><td colspan="3"><textarea name="descricao" style="width:100%; height: 80px"><?=$invertclass->descricao?></textarea></td></tr>
                      <!--<td><input id="descricao" type="text" size=80 name="descricao" value="descricao"></td>-->
                      <!--<tr><td>DATA INÍCIO</td><td><input id="data_inicio" type="date" style="height:30px" name="data_inicio" value="<? echo"";/*$invertclass->data_inicio*/?>"></td></tr>
                      <tr><td>DATA FIM</td><td><input id="data_fim" type="date" style="height:30px" name="data_fim" value="<? echo"";/* $invertclass->data_fim */?>"></td></tr>
                      <tr><th colspan="3">METAS DE APRENDIZAGEM</th></tr>
                      <tr><td colspan="3"><textarea id="metas" name="knowledge_area" style="width:100%; height: 80px"><? echo"";/* $invertclass->knowledge_area */?></textarea></td></tr>
                      -->
                      <tr><th colspan="3">PALAVRAS NÃO RELACIONADAS</th></tr>
                      <tr><td colspan="3"><textarea id="naorelacionadas" name="not_related_words" style="width:100%; height: 80px"><?=$invertclass->not_related_words?></textarea></td></tr> <!-- placeholder="DIGITE AQUI AS PALAVRAS NÃO RELACIONADAS À TAREFA SEPARADAS POR VÍRGULA (PALAVRA 1, PALAVRA 2)"  -->
                      <tr><th>ARQUIVO</th><td><input id="arq" type="file" name="arq"></td></tr>
                    </table>
                    <input id="action" name="action" type="hidden" value="up_task"/>
                    <input id="id" name="id" type="hidden" value="<?=$invertclass->id ?>"/>
                    <input id="tarq" name="task_arq" type="hidden" value="<?=empty($invertclass->arquivo)?"":$invertclass->arquivo ?>"/>
                    <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
                    <button name="send" class="btn btn-success" onclick="document.getElementById('add_task').style.display = 'inherit'; this.form.submit();"><span class="glyphicon glyphicon-floppy-disk"></span>ATUALIZAR TAREFA</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- ################################################################## -->




          <!--                       AREAS DE CONHECIMENTO                        -->
          <!-- ################################################################## -->
          
          <div role="tabpanel" class="tab-pane" id="area">
            <?php
              //$invertclass->goals = get_goals($invertclass->id);
            ?>
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">ÁREAS DE CONHECIMENTO</h3>
              </div>
              <div class="panel-body">
                <?php 
                //echo var_dump($invertclass->id);
                $invertclass->requirements = get_requirements($cm->id);
                if(!empty($invertclass->requirements)){ 
                  ?>
                <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>Descrição</th>
                      <th>Nível</th>
                      <th>Significância</th>
                      <th>Ação</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                        foreach ($invertclass->requirements as $requirement) {?>
                          <tr>
                              <td><?= $requirement->feature->descricao ?></td>
                              <td><?= $nivel[$requirement->value] ?></td>
                              <td><?= $importancia[$requirement->importance] ?></td>
                              <td><a href="teacher_views/teacheractions_flip.php?reqid=<?=$requirement->id?>&id=<?=$cm->id?>&action=delete_requirement&url_local=<?=urlencode($PAGE->url)?>" id="btn-del-cloned-input" name="btn-del-cloned-input" class="btn btn-danger btn-xs pull-right" onclick="return confirm(\'Deseja realmente excluir essa meta de aprendizagem?\');"><span class="glyphicon glyphicon-minus"></span>Remover</a></td>
                          </tr>
                          <?php
                        }
                    ?>
                  </tbody>
                </table>
                <?php 
                } else {?>
                  <div class="alert alert-danger" role="alert">Nenhuma área de conhecimento encontrada.</div>
                <?php
                }
                ?>
                <h4>Adicionar área de conhecimento</h4>
                <hr />
                <form class="form-horizontal" action="teacher_views/teacheractions_flip.php" method="POST">
                <input id="id" name="id" type="hidden" value="<?php echo $cm->id; ?>">
                <input id="action" name="action" type="hidden" value="add_invertclass_requirement">
                <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
                <fieldset>
                  <div class="form-group">
                    <label class="col-md-4 control-label">Descrição</label>  
                    <div class="col-md-5">
                      <input id="requirement_description" name="requirement_description" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-4 control-label">Nível de conhecimento</label>
                    <div class="col-md-4">
                        <div class="radio">
                          <label><input type="radio" name="level" id="level-1" value="b">Baixo</label>
                        </div>
                        <div class="radio">
                          <label><input type="radio" name="level" id="level-2" value="m" checked >Médio</label>
                        </div>
                        <div class="radio">
                          <label><input type="radio" name="level" id="level-3" value="a">Alto</label>
                        </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-4 control-label">Significância</label>
                    <div class="col-md-3">
                      <select id="importance" name="importance" class="form-control">
                        <option value="0.1">Irrelevante</option>
                        <option value="0.2">Dispensável</option>
                        <option value="0.3">Extremamente baixa</option>
                        <option value="0.4">Muito Baixa</option>
                        <option value="0.5">Baixa</option>
                        <option value="0.6" selected>Média</option>
                        <option value="0.7">Alta</option>
                        <option value="0.8">Muito alta</option>
                        <option value="0.9">Extremamente alta</option>
                        <option value="1.0">Indispensável</option>
                      </select>
                    </div>
                  </div>
                  <hr />
                  <div class="col-md-8">
                  <button id="button2id" name="button2id" class="btn btn-success" onclick="javascript:this.value='Enviando...'; this.disabled='disabled'; this.form.submit();"><span class="glyphicon glyphicon-plus"></span>ADICIONAR</button>
                  </div>
                </fieldset>
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
                      <th>AÇÃO</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    global $DB;
                    $fpgroups = new stdClass();
                    //$fpgroups = $DB->get_records("fpgroups"); 
                    $fpgroups = $DB->get_records_sql('select * from mdl_fpgroups where moduleid = '.$cm->id.';');
                    
                    foreach ($fpgroups as $group){?>
                      <tr>
                        <td><?=$group->nome?></td>
                        <td>
                          <a href="<?=$PAGE->url?>&op=show_members&idg=<?=$group->id?>">
                            <button class='btn btn-info'><span class='glyphicon glyphicon-list'></span></button>
                          </a>
                          <a href="<?=$PAGE->url?>&moduleid=<?=$cm->id?>&id_curso=<?=$COURSE->id?>&op=up_group&idg=<?=$group->id?>">
                            <button class='btn btn-success'><span class='glyphicon glyphicon-pencil'></span></button>
                          </a>
                          <a href="./teacher_views/teacheractions_flip.php?action=rm_group&group_id=<?=$group->id?>&url_local=<?=$PAGE->url?>">
                            <button class='btn btn-danger'><span class='glyphicon glyphicon-remove'></span></button>
                          </a>
                        </td>
                      </tr>
                    <?php
                    }
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
                if(!isset($temp_group)){  
                ?>
                <div class="col-md-8">
                  <button class="btn btn-primary" onclick="document.getElementById('add_group').style.display = 'inherit';">
                    <span class="glyphicon glyphicon-plus">
                    </span>
                    ADICIONAR GRUPO
                  </button>
                </div>
              </div>
              <div id="add_group" class="panel-body" style="display: <?php if(isset($_SESSION['idgroup'])) echo "inherit"; else echo "none";?>">
                <form class="form-horizontal" action="teacher_views/teacheractions_flip.php" method="POST">
                  <table class="table table-bordered table-condensed table-hover">
                    <tr>
                      <td>
                        <span style="font-weight: bold;">NOME DO GRUPO
                        </span>&nbsp;&nbsp;
                        <input id="gp_name" name="gp_name" type="text" size=40>&nbsp;&nbsp;
                        <button class="btn btn-primary" onclick="this.form.submit()">
                          <span class="glyphicon glyphicon-arrow-right">
                          </span>CRIAR
                        </button>
                      </td>
                    </tr>
                  </table>
                  <input id="action" name="action" type="hidden" value="ad_group"/>
                  <input id="moduleid" name="moduleid" type="hidden" value="<?php echo $cm->id; ?>"/>
                  <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">  
                </form>
                <?php 
                } else { ?>
                ADICIONANDO ALUNOS PARA: <?php echo "".$_SESSION['ntgroup']; ?>
                <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>ALUNO</th>
                      <th>AÇÃO</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // pegando alunos cadastrados no curso - eu acho
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
                    foreach($students as $student){
                      // talvez precise mudar a query para procurar somente por membros do curso atual, acho que nao precisa
                      $ismember = $DB->get_record('fpmembers', array("id_user" => $student->id));
                      // tratando para o caso de não existirem registros na tabela de membros
                      if(!$ismember){?>
                        <tr>
                          <td>
                            <?php echo $student->firstname?>
                          </td>
                          <td>
                            <a href="./teacher_views/teacheractions_flip.php?action=ad_mmember&member_id=<?php echo $student->id?>&url_local=<?php echo $PAGE->url?>"><button class='btn btn-danger' onclick=''><span class='glyphicon glyphicon-bookmark'></span></button></a>
                            <a href="./teacher_views/teacheractions_flip.php?action=ad_gmember&member_id=<?php echo $student->id?>&url_local=<?php echo $PAGE->url?>"><button class='btn btn-success' onclick=''><span class='glyphicon glyphicon-plus'></span></button></a>
                          </td>
                        </tr>
                      <?php
                      }
                      else if($student->id!=$ismember->id_user){?>
                        <tr>
                          <td>
                            <?php echo $student->firstname?>
                          </td>
                          <td>
                            <a href="./teacher_views/teacheractions_flip.php?action=ad_mmember&member_id=<?php echo $student->id?>&url_local=<?php echo $PAGE->url?>"><button class='btn btn-danger' onclick=''><span class='glyphicon glyphicon-bookmark'></span></button></a>
                            <a href="./teacher_views/teacheractions_flip.php?action=ad_gmember&member_id=<?php echo $student->id?>&url_local=<?php echo $PAGE->url?>"><button class='btn btn-success' onclick=''><span class='glyphicon glyphicon-plus'></span></button></a>
                          </td>
                        </tr>
                      <?php
                      }
                    }
                    ?>
                  </tbody>
                </table>      
                <div class="col-md-8">
                  <a href="<?php echo $PAGE->url?>&op=ok">
                    <button id="button2id" name="button2id" class="btn btn-success">
                      <span class="glyphicon glyphicon-plus">
                      </span>CONCLUÍDO
                    </button>
                  </a>
                </div>
                <?php 
                }
                ?>
              </div>
            </div>
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
                      $ref = $DB->get_record_sql('select r.id,r.descricao, r.moduleid, r.arquivo, i.name 
                      from mdl_fpref r inner join mdl_course_modules m on r.moduleid=m.id inner join mdl_invertclass i on i.id=m.instance where moduleid = '.$cm->id.';');
                      if($ref){
                    ?>
                    <tr>
                      <td><?=$ref->descricao?></td>
                      <td>
                        <a href="<?=$PAGE->url?>&op=up_ref&moduleid=<?=$cm->id?>&idr=<?=$ref->id?>">
                          <button class='btn btn-success'>
                            <span class='glyphicon glyphicon-pencil'></span>
                          </button>
                        </a>
                        <a href='./teacher_views/teacheractions_flip.php?action=rm_ref&ref_arquivo=<?=$ref->arquivo?>&ref_id=<?=$ref->id?>&url_local=<?=$PAGE->url?>'>
                          <button class='btn btn-danger'>
                            <span class='glyphicon glyphicon-remove'></span>
                          </button>
                        </a>
                      </td>
                    </tr>
                    <?php
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
                      <input id="id_curso" name="id_curso" type="hidden" value="<?php echo $COURSE->id ?>"/>
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
                <br>
                <br>    
                <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>GRUPO</th>
                      <th>NOTA</th>
                      <th>SOLUÇÃO</th>
                      <th style="text-align:center;">BAIXAR ANEXO</th>
                      <th>SITUAÇÃO</th>
                      <th>TAREFA</th>
                      <th>AÇÃO</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // TODO: VERIFICAR SE AS CONSULTAS ESTÃO CORRETAS
                    $avagroups = $DB->get_records_sql('SELECT a.id, a.id_group, a.nota, a.feedback, a.situacao, a.moduleid,g.nome,i.name as task_name 
                    from mdl_fpavaliar a inner join mdl_fpgroups g on a.id_group=g.id inner join mdl_course_modules m on m.id=a.moduleid inner join mdl_invertclass i on i.id=m.instance
                    where m.id= '.$cm->id.';');
                    foreach($avagroups as $aval){?>
                    <tr>
                      <td><?=$aval->nome?></td>
                      <td><?=$aval->nota?></td>
                      <?php
                      $anexo_grupo = $DB->get_record_sql('SELECT nome_original from mdl_fpanexos a inner join mdl_fpgroups g on g.anexoid=a.id where g.id = '.$aval->id_group.';');
                      if($anexo_grupo == null){?>
                      <td>Sem Anexo</td>
                      <td></td>
                      <?php
                      }else{?>
                      <td><?=$anexo_grupo->nome_anexo?></td>
                      <td style=text-align:center;><a href=arquivos/anexos_grupos/<?=$anexo_grupo->nome_anexo?> target=_blank class='btn btn-primary'>Baixar</a></td>
                      <?php
                      }
                      if($aval->situacao==0){?>
                      <td><span class='btn btn-warning'>PENDENTE</span></td>
                      <td><?=$aval->task_name?></td>
                      <td>
                        <button class='btn btn-primary' onclick="document.getElementById('avagroup_name').innerHTML='<?=$aval->nome?>'; document.getElementById('avagroup_id').value='<?=$aval->id_group?>';document.getElementById('aval_id').value='<?=$aval->id?>';document.getElementById('avatask').value='<?=$aval->id_task?>';document.getElementById('avalia_grupo').style.display = 'inherit';">
                          <span class='glyphicon glyphicon-pencil'>
                          </span> 
                          AVALIAR
                        </button>
                      </td>
                    </tr>
                      <?php
                      }else{?>
                      <td>
                        <span class='btn btn-success'>AVALIADO</span>
                      </td>
                      <td><?=$aval->task_name?></td>
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
                <div id="feedback_aluno" style="display: none">
                  <table class="table table-bordered table-condensed table-hover">
                    <form action="teacher_views/teacheractions_flip.php" method="POST">
                      <tr>
                        <th>CONSIDERAÇÕES SOBRE A TAREFA</th>
                        <th>
                          <span id="fbaluno" name="fbaluno">
                          </span>
                        </th>
                        <th>
                          <select id="taskfb_name" name="taskfb_name">
                            <?php
                            $stasks = $DB->get_records_sql('select * from mdl_invertclass where id = '.$cm->id.';');//$DB->get_records("fptasks");//$DB->get_records('fptasks');
                            foreach( $stasks as $task){?>
                            <option value="<?=$task->id?>">
                              <?=$task->name?>
                            </option>
                            <?php
                            }
                            ?>
                          </select>
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
                          <input id="id_curso" name="id_curso" type="hidden" value="<?php echo $COURSE->id ?>"/>
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
                        <button class="btn btn-primary" onclick="document.getElementById('taskfb_idaluno').value=<?=$student->id?>; document.getElementById('fbaluno').innerHTML=<?=$student->firstname.$student->lastname?>; document.getElementById('feedback_aluno').style.display = inherit;">
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

          <!-- ################################################################## -->

          <!-- ################################################################## -->
          <!--                        SESSOES                                     -->
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane" id="sessoes">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">SESSÕES</h3>
              </div>
              <div class="panel-body">
                <table class="table table-bordered table-condensed table-hover">
                  <thead>
                    <tr>
                      <th>GRUPO</th>
                      <th>AÇÃO</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    global $DB;
                    $fpgroups = new stdClass();
                    //$fpgroups = $DB->get_records("fpgroups"); 
                    $fpgroups = $DB->get_records_sql('select * from mdl_fpgroups where moduleid = '.$cm->id.';');
                    //if(count($fpgroups)==0) echo ("Sem grupos<br>");
                    //TODO: dica JAVASCRIPT PEGAR URL: var url_atual = window.location.href;;
                    foreach ($fpgroups as $group){?>
                        <tr>
                          <td><?=$group->nome?></td>
                          <td>
                            <a href="<?=$PAGE->url?>&op=show_sessions&idg=<?=$group->id?>">
                              <button class='btn btn-info'><span class='glyphicon glyphicon-list'></span>VISUALIZAR SESSÕES</button>
                            </a>
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


        </div>
      </div>
    </div>
  </div><!-- FIM DA EXIBIÇÃO DO INVERTCLASS -->

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
function exibirMensagem($msg) { ?>
  <script type="text/javascript">alert('<?php echo $msg?>')</script>
<?php 
}
?>