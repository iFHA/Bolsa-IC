<div>
<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once(dirname(dirname(__FILE__)).'/lib.php');
require_once(dirname(dirname(__FILE__)).'/locallib.php');

?>
    <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">TAREFA</h3>
                </div>
                <div class="panel-body">
                    
                    
                <form action="teacher_views/teacheractions_flip.php" method="POST" enctype="multipart/form-data">

                <div id="add_task">
                <table class="table table-bordered table-condensed table-hover">
                    <?php 
                        global $DB;
                        $idt = required_param('idt',PARAM_TEXT); 
                        $task = $DB->get_record('fptasks', array("id" => $idt));
                        echo "<tr><td>NOME DA TAREFA</td><td><input id='nome' type='text' size=67 name='nome' value='".$task->nome."'></td></tr>";
                        echo "<tr><td>DESCRIÇÃO</td><td><input id='descricao' type='text' size=80 name='descricao' value='$task->descricao'></td></tr>";
                        echo "<tr><td>ARQUIVO</td><td><input id='arquivo' type='file' name='arq' value='".$task->arquivo."'></td></tr><input type='hidden' name='task_arq' value='".$task->arquivo."'>";
                        echo '<tr><th>ÁREAS DE CONHECIMENTO</th></tr>';
                        echo '<tr><td colspan="3"><textarea id="areaConhecimento" name="knowledge" placeholder="DIGITE AQUI AS ÁREAS DE CONHECIMENTO SEPARADAS POR VÍRGULA (ÁREA 1, ÁREA 2)" style="width:100%; height: 80px"></textarea></td></tr>';
                        echo '<tr><th>PALAVRAS NÃO RELACIONADAS</th></tr>';
                        echo '<tr><td colspan="3"><textarea id="naorelacionadas" name="naorelacionadas" placeholder="DIGITE AQUI AS PALAVRAS NÃO RELACIONADAS À TAREFA SEPARADAS POR VÍRGULA (PALAVRA 1, PALAVRA 2)" style="width:100%; height: 80px"></textarea></td></tr>';
                        //echo "<tr><td>ARQUIVO</td><td><input id='arquivo' type='file' name='arq' value='".$task->arquivo."'></td></tr>";  
                        echo "<tr><td>DATA INÍCIO</td><td><input id='data_inicio' type='text' size=20 name='data_inicio' value='".$task->data_inicio."'></td></tr>";
                        echo "<tr><td>DATA FIM</td><td><input id='data_fim' type='text' size=20 name='data_fim' value='".$task->data_fim."'></td></tr>";
                        echo "<tr><td>ÚLTIMA TAREFA</td><td><input id='ultima' name='ultima' type='radio' value=1>  SIM&nbsp;&nbsp;&nbsp;<input id='ultima' type='radio' name='ultima' value=0>  NAO</td></tr>";
                        echo "<input id='idt' type='hidden' name='idt' value='".$idt."'/>";
                    ?>

                  <?php
                    /*
                    <!-- ===============================REQUISITOS E METAS============================== -->

                <!-- <div role="tabpanel" class="tab-pane active" id="problem"> -->
                  <div class="panel panel-info">
                    <div class="panel-heading">
                      <h3 class="panel-title"><span class="glyphicon glyphicon-envelope"></span> Metas de aprendizagem</h3>
                    </div>
                    <div class="panel-body">
                      <table class="table table-bordered table-condensed table-hover">
                        <thead>
                          <tr>
                            <th>Descrição</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          /*
                              foreach ($problem->goals as $goal) {
                                echo '<tr>';
                                echo '<td>'.$goal->feature->description;
                                echo '<a href="teacher_views/teacheractions.php?id='.$cm->id.'&goalid='.$goal->id.'&action=delete_problem_goal&url_local='.urlencode($PAGE->url).'" id="btn-del-cloned-input" name="btn-del-cloned-input" class="btn btn-danger btn-xs pull-right" onclick="return confirm(\'Deseja realmente excluir essa meta de aprendizagem?\');"><span class="glyphicon glyphicon-minus"></span> Remover</a></td>';
                                echo '</tr>';
                              }
                              */
                          ?>
                          <?
                          /* apagar esse daqui de cima
                        </tbody>
                      </table>
                      <form action="teacher_views/teacheractions.php" method="POST" class="col-md-12">
                        <input id="id" name="id" type="hidden" value="<?php echo $cm->id; ?>">
                        <input id="action" name="action" type="hidden" value="<?php echo 'add_problem_goal'; ?>">
                        <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
                        <div class="form-group">
                          <label>Descrição</label>
                          <input id="goal_description" name="goal_description" class="form-control" />
                        </div>
                        <button id="button2id" name="button2id" class="btn btn-success" onclick="javascript:this.value='Enviando...'; this.disabled='disabled'; this.form.submit();"><span class="glyphicon glyphicon-floppy-disk"></span> Adicionar meta de aprendizagem</button>
                      </form>
                    </div>
                  </div>
                  <div class="panel panel-info">
                    <div class="panel-heading">
                      <h3 class="panel-title"><span class="glyphicon glyphicon-envelope"></span> Requisitos da Tarefa</h3>
                    </div>
                    <div class="panel-body">
                      <table class="table table-bordered table-condensed table-hover">
                        <thead>
                          <tr>
                            <th>Descrição</th>
                            <th>Nível</th>
                            <th>Significância</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          /*
                              foreach ($problem->requirements as $requirement) {
                                echo '<tr>';
                                echo '<td>'.$requirement->feature->description.'</td>';

                                if($requirement->value == 'b')
                                  echo '<td>Baixo</td>';
                                else if($requirement->value == 'm')
                                  echo '<td>Médio</td>';
                                else if($requirement->value == 'a')
                                  echo '<td>Alto</td>';

                                if($requirement->importance == 0.1)
                                  echo '<td>Irrelevante</td>';
                                else if($requirement->importance == 0.2)
                                  echo '<td>Dispensável</td>';
                                else if($requirement->importance == 0.3)
                                  echo '<td>Extremamente baixa</td>';
                                else if($requirement->importance == 0.4)
                                  echo '<td>Muito Baixa</td>';
                                else if($requirement->importance == 0.5)
                                  echo '<td>Baixa</td>';
                                else if($requirement->importance == 0.6)
                                  echo '<td>Média</td>';
                                else if($requirement->importance == 0.7)
                                  echo '<td>Alta</td>';
                                else if($requirement->importance == 0.8)
                                  echo '<td>Muito alta</td>';
                                else if($requirement->importance == 0.9)
                                  echo '<td>Extremamente alta</td>';
                                else if($requirement->importance == 1.0)
                                  echo '<td>Indispensável</td>';

                                echo '<td><a href="teacher_views/teacheractions.php?id='.$cm->id.'&requirementid='.$requirement->id.'&action=delete_problem_requirement&url_local='.urlencode($PAGE->url).'" id="btn-del-cloned-input" name="btn-del-cloned-input" class="btn btn-danger btn-xs" onclick="return confirm(\'Deseja realmente excluir esse requisito?\');"><span class="glyphicon glyphicon-minus"></span> Remover</a></td>';
                                echo '</tr>';
                              }
                              */
                          ?>
                          <?
                          /* apagar esse daqui de cima
                        </tbody>
                      </table>
                      <form class="form-horizontal" action="teacher_views/teacheractions.php" method="POST">
                        <input id="id" name="id" type="hidden" value="<?php echo $cm->id; ?>">
                        <input id="action" name="action" type="hidden" value="<?php echo 'add_problem_requirement'; ?>">
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
                                <label><input type="radio" name="level" id="level-2" value="m">Médio</label>
                              </div>
                              <div class="radio">
                                <label><input type="radio" name="level" id="level-3" value="a">Alto</label>
                              </div>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-4 control-label">Significância</label>
                            <div class="col-md-2">
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
                            <button id="button2id" name="button2id" class="btn btn-success" onclick="javascript:this.value='Enviando...'; this.disabled='disabled'; this.form.submit();"><span class="glyphicon glyphicon-floppy-disk"></span> Adicionar área de conhecimento</button>
                          </div>
                        </fieldset>
                      </form>
                    </div>
                  </div>
                <!-- </div> -->

                <!-- ===============================/REQUISITOS E METAS============================ -->
                */ ?>
                  </table>
                    <input id="action" name="action" type="hidden" value="up_task"/>
                    <input id="id_curso" name="id_curso" type="hidden" value="<?php echo required_param('id_curso',PARAM_TEXT); ?>"/>
                    <input name="send" type="hidden"/>
                    <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
                    <button class="btn btn-success" onclick="document.getElementById('add_task').style.display = 'inherit'; this.form.submit();"><span class="glyphicon glyphicon-refresh"></span> ATUALIZAR</button>
                    
                </div>
                </form>
                <a href="javascript:history.back()"><button class="btn btn-primary" style="width: 118px"><span class="glyphicon glyphicon-arrow-left"></span> VOLTAR</button></a>
              </div>
            </div>
</div>
    