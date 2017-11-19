<div>
<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once(dirname(dirname(__FILE__)).'/lib.php');
require_once(dirname(dirname(__FILE__)).'/locallib.php');

?>
    <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">REFERÊNCIA</h3>
                </div>
                <div class="panel-body">
                    
                    
                <form action="teacher_views/teacheractions_flip.php" method="POST" enctype="multipart/form-data">

                <div id="add_task">
                <table class="table table-bordered table-condensed table-hover">
                    <?php 
                        global $DB;
                        $idr = required_param('idr',PARAM_TEXT); 
                        $ref = $DB->get_record('fpref', array("id" => $idr));
                        echo "<tr><td>REFERÊNCIA</td><td><input id='ref_desc' type='text' size=67 name='ref_desc' value='".$ref->descricao."'></td></tr>";
                        echo "<tr><td>ARQUIVO</td><td><input id='ref_file' type='file' name='arq' value='".$ref->arquivo."'></td></tr><input type='hidden' name='ref_arq' value='".$ref->arquivo."'>";  
                        //echo "<tr><td>ARQUIVO</td><td><input id='ref_file' type='file' name='arq' value='".$ref->arquivo."'></td></tr>";  
                        echo "<input id='idr' type='hidden' name='idr' value='".$idr."'/>";
                        echo "<tr><td>TAREFA</td>";
                        echo "<td>";
                        echo "<select id='ref_id_task' name='ref_id_task'>";
                                    $temp_tasks = $DB->get_records("fptasks");
                                    foreach( $temp_tasks as $task){
                                        echo "<option value='".$task->id."'>".$task->nome."</option>";
                                    }
                        echo "</select></td></tr>";
                    ?>
                    
                  </table>
                    <input id="action" name="action" type="hidden" value="up_ref"/>
                    <input id="url_local" name="url_local" type="hidden" value="<?php echo $PAGE->url; ?>">
                    <button name="send" class="btn btn-success" onclick="document.getElementById('add_task').style.display = 'inherit'; this.form.submit();"><span class="glyphicon glyphicon-refresh"></span> ATUALIZAR</button>
                    
                </div>
                </form>
                <a href="javascript:history.back()"><button class="btn btn-primary" style="width: 118px"><span class="glyphicon glyphicon-arrow-left"></span> VOLTAR</button></a>
              </div>
            </div>
</div>
    