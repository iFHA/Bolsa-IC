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
                        //echo "<tr><td>ARQUIVO</td><td><input id='arquivo' type='file' name='arq' value='".$task->arquivo."'></td></tr>";  
                        echo "<tr><td>DATA INÍCIO</td><td><input id='data_inicio' type='text' size=20 name='data_inicio' value='".$task->data_inicio."'></td></tr>";
                        echo "<tr><td>DATA FIM</td><td><input id='data_fim' type='text' size=20 name='data_fim' value='".$task->data_fim."'></td></tr>";
                        echo "<tr><td>ÚLTIMA TAREFA</td><td><input id='ultima' name='ultima' type='radio' value=1>  SIM&nbsp;&nbsp;&nbsp;<input id='ultima' type='radio' name='ultima' value=0>  NAO</td></tr>";
                        echo "<input id='idt' type='hidden' name='idt' value='".$idt."'/>";
                    ?>
                    
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
    