<script type="text/javascript" src="../js/ajax.js"></script>
<div>
  <?php

  require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
  require_once(dirname(dirname(__FILE__)).'/lib.php');
  require_once(dirname(dirname(__FILE__)).'/locallib.php');
  /*
  $action = optional_param('action', null, PARAM_TEXT);
  $id_user = optional_param('id_user', null, PARAM_INT);
  $group_id = optional_param('group_id', null, PARAM_INT);
  */
  $id_curso = optional_param('id_curso', null, PARAM_INT);

  // teste para caso não seja passado o id do curso
  if(!empty($id_curso)){
    $_SESSION['idcursin'] = $id_curso;
  }

  $id_curso = $_SESSION['idcursin'];
  ?>
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">ALTERANDO GRUPO</h3>
    </div>
    <div class="panel-body">
      <table class="table table-bordered table-condensed table-hover">
        <thead>
          <tr>
          <th>NOME</th>
          <th>SOBRENOME</th>
          <th>EMAIL</th>
          <th>AÇÃO</th>
          </tr>
        </thead>
        <?php
          $idg = required_param('idg',PARAM_TEXT);
          $students1 = $DB->get_records_sql("SELECT u.id, u.firstname,u.lastname, u.email FROM mdl_role_assignments rs INNER JOIN mdl_user u ON u.id=rs.userid INNER JOIN mdl_context e ON rs.contextid=e.id INNER JOIN mdl_fpmembers fpm ON u.id=fpm.id_user WHERE e.contextlevel=50 AND rs.roleid=5 AND e.instanceid=2 AND fpm.id_group=".$idg.";");
          foreach($students1 as $student){
            //echo var_dump($student->id);
              echo '<tr><td>'.$student->firstname.'</td><td>'.$student->lastname.'</td><td>'.$student->email.'</td><td>';
              echo "<a href='./teacher_views/teacheractions_flip.php?action=rm_gmember&group_id=$idg&ids=$student->id&url_local=$PAGE->url'><button class='btn btn-danger'><span class='glyphicon glyphicon-remove'></span></button></a>";
              //echo "<a href='$PAGE->url&action=rm_gmember&group_id=".$idg."&id_user=".$student->id."'><button class='btn btn-danger'><span class='glyphicon glyphicon-remove'></span></button></a>";
              echo '</td></tr>';
          }
          
          $students = $DB->get_records_sql("SELECT
          u.firstname,
          u.id,
          U.lastname,
          u.email
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
          AND c.id = $id_curso
          AND NOT EXISTS (
            SELECT * FROM mdl_fpmembers fpm WHERE fpm.id_user = u.id
          )
          ");
          if(!empty($students)){
            foreach($students as $student){
              echo '<tr><td>'.$student->firstname.'</td><td>'.$student->lastname.'</td><td>'.$student->email.'</td><td>';
              echo "<a href='./teacher_views/teacheractions_flip.php?action=add_gmember&idgroup=".$idg."&member_id=".$student->id."&url_local=".$PAGE->url."'><button class='btn btn-success'><span class='glyphicon glyphicon-plus'></span></button></a>";
              //echo "<a href='$PAGE->url&action=ad_gmember&group_id=".$idg."&id_user=".$student->id."'><button class='btn btn-success'><span class='glyphicon glyphicon-plus'></span></button></a>";
              echo '</td></tr>';
            }
          
        ?>
        </tbody>
      </table>
      <?php
      } else {
      ?>
      </tbody>
      </table>
      <div class="col-md-8">
        SEM ALUNOS DISPONÍVEIS PARA SEREM ADICIONADOS AO GRUPO
      </div>
      <?php
      }
      ?>
      <div class="col-md-8">
            <a href="javascript:history.back()"><button class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> VOLTAR</button></a>
      </div>
    </div>
  </div>
</div>
    