<?php
/**
 *
 * @package   mod_problem
 * @category  groups
 * @copyright 2014 Danilo Gomes Carlos
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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

if(problem_is_enrolled($context, "editingteacher")){
?>

<div class="container-fluid">
  <div class="row"><!-- INÍCIO DA EXIBIÇÃO DO GRUPO -->
    <div class="col-md-12">
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#problem" aria-controls="problem" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-home"></i> Problema</a></li>
          <li role="presentation"><a href="#groups" aria-controls="groups" role="tab" data-toggle="tab">Listagem de grupos</a></li>
        </ul>
        <br />
        <div class="tab-content">
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
                        foreach ($problem->goals as $goal) {
                          echo '<tr>';
                          echo '<td>'.$goal->feature->description;
                          echo '<a href="teacher_views/teacheractions.php?id='.$cm->id.'&goalid='.$goal->id.'&action=delete_problem_goal&url_local='.urlencode($PAGE->url).'" id="btn-del-cloned-input" name="btn-del-cloned-input" class="btn btn-danger btn-xs pull-right" onclick="return confirm(\'Deseja realmente excluir essa meta de aprendizagem?\');"><span class="glyphicon glyphicon-minus"></span> Remover</a></td>';
                          echo '</tr>';
                        }
                    ?>
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
                <h3 class="panel-title"><span class="glyphicon glyphicon-envelope"></span> Requisitos do problema</h3>
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
                    ?>
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
          </div>
          <!-- ################################################################## -->

          <!-- ################################################################## -->
          <!--                        LISTAGEM DE GRUPOS                          -->
          <!-- ################################################################## -->
          <div role="tabpanel" class="tab-pane" id="groups">
            <?php if(count($groups) > 0){ ?>
            <div class="col-md-12">
              <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title">Grupos vinculados ao problema</h3>
                </div>
                <div class="panel-body">
                  <table class="table table-hover table-condensed table-bordered">
                    <thead>
                      <tr>
                        <th>Nome</th>
                        <th>Status</th>
                        <th>Membros</th>
                        <th>Ações</th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php 
                      foreach($groups as $group){
                        $group = get_group($group->id, $problem->id);
                        if(in_array_field($group->id, 'groupid', $groups_enrolled)){

                          $group->members = groups_get_members($group->id, $fields='u.*', $sort='lastname ASC');
                          echo '<tr>';
                          echo '<td><strong>'. $group->name . '</strong></td>';
                          if($group->problemgroup->finished == 1)
                            echo '<td><span class="label label-success">Finalizado</span></td>';
                          else
                            echo '<td><span class="label label-primary">Em andamento</span></td>';
                          echo '<td><span class="label label-default"> '. count($group->members) . ' membros</span></td>';
                          echo '<td>';
                          echo '<a href="teacher_views/teacher_group_view.php?id='.$cm->id.'&groupid='.$group->id.'" class="btn btn-primary btn-sm">Visualizar</a> | ';
                          echo '<a href="teacher_views/teacheractions.php?id='.$cm->id.'&groupid='.$group->id.'&action=unlink_group&url_local='.urlencode($PAGE->url).'" class="btn btn-danger btn-sm"  onclick="return confirm(\'Deseja realmente desvincular esse grupo?\');">Desvincular</a>';
                          if($group->problemgroup->finished == 0)
                            echo ' | <a href="teacher_views/teacheractions.php?id='.$cm->id.'&groupid='.$group->id.'&action=finish_problem&url_local='.urlencode($PAGE->url).'" class="btn btn-success btn-sm"  onclick="return confirm(\'Deseja realmente finalizar o problema pra esse grupo?\');">Finalizar</a>';
                          echo '</td>';
                          echo '</tr>';
                        }
                      }
                    ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="panel panel-danger">
                <div class="panel-heading">
                  <h3 class="panel-title">Grupos não vinculados ao problema</h3>
                </div>
                <div class="panel-body">
                  <table class="table table-hover">
                    <tbody>
                     <?php 
                      foreach($groups as $group){
                        if(!in_array_field($group->id, 'groupid', $groups_enrolled)){

                          $group->members = groups_get_members($group->id, $fields='u.*', $sort='lastname ASC');
                          echo '<tr>';
                          echo '<td><span class="badge pull-left">'. count($group->members) . '</span> '. $group->name . '</td>';
                          echo '<td><div class="btn-group">';
                          echo '<a href="teacher_views/teacher_group_view.php?id='.$cm->id.'&groupid='.$group->id.'" class="btn btn-primary">Visualizar</a>';
                          echo '<a href="teacher_views/teacheractions.php?id='.$cm->id.'&groupid='.$group->id.'&action=link_group&url_local='.urlencode($PAGE->url).'" class="btn btn-success"  onclick="return confirm(\'Deseja realmente vincular esse grupo?\');">Vincular</a>';
                          echo '</div></td>';
                          echo '</tr>';
                        }
                      }
                    ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <?php 
              } else {
                echo '<div class="alert alert-danger" role="alert">';
                echo "Nenhum grupo foi encontrado!";
                echo '</div>';
              }
            ?>
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
}