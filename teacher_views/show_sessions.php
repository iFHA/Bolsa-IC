<div>
    <?php
    require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
    require_once(dirname(dirname(__FILE__)).'/lib.php');
    require_once(dirname(dirname(__FILE__)).'/locallib.php');
    /* 
    primeiro listar todas sessões que aquele grupo contem
    depois abrir espaço para o usuário poder criar sessões
    */
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">TAREFA</h3>
        </div>
        <div class="panel-body">            
            <table class="table table-bordered table-condensed table-hover">
                <?php 
                global $DB;
                $idt = required_param('idt',PARAM_TEXT); 
                $task = $DB->get_record('fptasks', array("id" => $idt));
                echo "<tbody>";
                echo "<tr><td>TAREFA</td><td>".$task->nome."</td></tr>";
                echo "<tr><td>ARQUIVO</td><td>".$task->arquivo."</td></tr>";
                echo "<tr><td>DATA INICIO</td><td>".$task->data_inicio."</td></tr>";
                echo "<tr><td>DATA FIM</td><td>".$task->data_fim."</td></tr>";
                echo "<tr><td>DESCRICAO</td><td>".$task->descricao."</td></tr>";
                echo "<tr><td>ULTIMA TAREFA</td><td>".$task->ultimamoodle."</td></tr>";
                echo "</tbody>";
                ?>
            </table>
            <div class="col-md-8">
                <a href="javascript:history.back()"><button class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> VOLTAR</button></a>
            </div>
        </div>
    </div>
</div>