<div>
    <?php

        require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
        require_once(dirname(dirname(__FILE__)).'/lib.php');
        require_once(dirname(dirname(__FILE__)).'/locallib.php');

    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
        <?php
            global $DB;
            $idg = required_param('idg',PARAM_TEXT); 
            //$grupo = new stdClass();
            $grupo = $DB->get_record("fpgroups", array('id' => $idg));
            echo "<h3 class='panel-title'>".$grupo->nome."</h3>";
            ?>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-condensed table-hover">
                <thead>
                <tr>
                <th>NOME</th>
                <th>SOBRENOME</th>
                <th>EMAIL</th>
                <th></th>
                    </tr>
                </thead>
                <?php
                $students = $DB->get_records_sql("SELECT u.id, u.firstname,u.lastname, u.email FROM mdl_role_assignments rs INNER JOIN mdl_user u ON u.id=rs.userid INNER JOIN mdl_context e ON rs.contextid=e.id INNER JOIN mdl_fpmembers fpm ON u.id=fpm.id_user WHERE e.contextlevel=50 AND rs.roleid=5 AND e.instanceid=2 AND fpm.id_group=".$idg.";");
                foreach($students as $student){
                    echo '<tr><td>'.$student->firstname.'</td><td>'.$student->lastname.'</td><td>'.$student->email.'</td><td>';
                    echo "<a href='./teacher_views/teacheractions_flip.php?action=up_mmember&group_id=".$idg."&ids=".$student->id."&url_local=".$PAGE->url."'><button class='btn btn-danger' onclick=''><span class='glyphicon glyphicon-bookmark'></span></button></a>";
                    echo "<a href='./teacher_views/teacheractions_flip.php?action=rm_gmember&group_id=".$idg."&ids=".$student->id."&url_local=".$PAGE->url."'><button class='btn btn-danger'><span class='glyphicon glyphicon-remove'></span></button></a>";
                    echo '</td></tr>';        
                }
                ?>
            </table>
            <div class="col-md-8">
                <a href="javascript:history.back()"><button class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> VOLTAR</button></a>
            </div>
        </div>
    </div>
</div>
    