<div>
    <?php

        require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
        require_once(dirname(dirname(__FILE__)).'/lib.php');
        require_once(dirname(dirname(__FILE__)).'/locallib.php');
        $session->id = 5;
        $group->id = 5;
        $gnome = required_param('gnome',PARAM_TEXT); 
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">DADOS DAS SESSÕES DO <?=$gnome?></h3>
        </div>
        <div class="panel-body">
        
        <?php 
        if(1){ ?>
        <table class="table table-bordered table-condensed table-hover">
            <thead>
            <tr>
                <th>Data</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php 
            if(1){
                echo '<tr>';
                echo '<td>' . date("d/m/Y H:i") .'</td>';
                echo '<td><span class="">';
                if(0)
                echo '<span class="label label-success">Finalizada</span>';
                else if(0)
                echo '<span class="label label-warning">Sessão atual</span>';
                else 
                echo '<span class="label label-primary">Próxima sessão</span>';
                echo '</span></td>';
                echo '<td>';
                echo '<div class="btn-group">';
                echo '<a href="student_views/session.php?id=' . $cm->id . '&sessionid=' .$session->id. '&groupid=' .$group->id. '" class="btn"><span class="glyphicon glyphicon-eye-open"></span> Visualizar</a>';
                echo '<a href="teacheractions.php?id='.$cm->id.'&action=delete_session&sessionid='.$session->id.'&url_local='.urlencode($PAGE->url).'" class="btn"  onclick="return confirm(\'Deseja realmente excluir essa sessão?\');"><span class="glyphicon glyphicon-trash"></span> Excluir</a>';
                echo '<a href="session_edit.php?id=' . $cm->id . '&sessionid=' .$session->id. '&groupid=' .$group->id. '" class="btn"><span class="glyphicon glyphicon-pencil"></span> Editar</a>';
                echo '</div>';
                echo '</td>';
                echo '</tr>';
            }
            
            ?>
            </tbody>
        </table>
        <?php 
            } else {
            echo '<div class="alert alert-danger" role="alert">';
            echo "Nenhuma sessão foi encontrada! Crie a primeira sessão para começar.";
            echo '</div>';
            }
            echo '<a href="session_new.php?id=' . $cm->id . '&groupid=' .$group->id. '" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span><br />Criar nova sessão</a><br><br>';
        ?>
        <div class="col-md-8">
            <a href="javascript:history.back()"><button class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> VOLTAR</button></a>
        </div>
        </div>
    </div>
</div>