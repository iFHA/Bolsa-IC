<div>
<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once(dirname(dirname(__FILE__)).'/lib.php');
require_once(dirname(dirname(__FILE__)).'/locallib.php');

$action = optional_param('action',null,PARAM_TEXT);

global $DB;

$caminhoTarefas = "../arquivos/tarefas";
$caminhoReferencias = "../arquivos/referencias";

switch($action){
    case 'ad_group':
        $grupo = new stdClass();
        $grupo->nome = required_param('gp_name', PARAM_TEXT);
        $grupo->moduleid = required_param('moduleid',PARAM_INT);
        $DB->execute("insert into mdl_fpgroups (nome, moduleid) values ('$grupo->nome',$grupo->moduleid)");
        //$gtemp = $DB->get_record('fpgroups', array("nome" => $grupo->nome));
        $gtemp = $DB->get_record_sql("select * from mdl_fpgroups where moduleid = {$grupo->moduleid} and nome = '{$grupo->nome}';");
        $_SESSION['idgroup']=$gtemp->id;
        $_SESSION['ntgroup']=$gtemp->nome;
        //echo var_dump($gtemp);

        // ======== populando tabela fp-avaliar ========
        //$group_id = $DB->get_field_sql("select id from mdl_fpgroups where nome='{$grupo->nome}' and id_curso = '{$grupo->id_curso}'");
        $group_id = $gtemp->id;
        
        $DB->execute("insert into mdl_fpavaliar values (default, $group_id, 0, 0, '', $grupo->moduleid)");
        // =============================================

        //echo var_dump($temp_avalia);
        $url_local = required_param('url_local', PARAM_TEXT);
		header("Location: ".$url_local."#grupos");
        break;
    case 'ad_gmember':
        //$member = new stdClass();
        $member->id_user = required_param('member_id', PARAM_TEXT);
        $member->id_group = $_SESSION['idgroup'];
        $member->moderador = 1;
        $DB->execute("insert into mdl_fpmembers values(NULL,".$member->id_user.",".$member->id_group.",".$member->moderador.")");
        $DB->execute("insert into mdl_fpgain values(NULL,".$member->id_user.",0)");
        $url_local = required_param('url_local', PARAM_TEXT);
		header("Location: ".$url_local."#grupos");
        break;
    case 'add_gmember':
        //$member = new stdClass();
        $member->id_user = required_param('member_id', PARAM_TEXT);
        $member->id_group = required_param('idgroup', PARAM_TEXT);
        $member->moderador = 1;
        $DB->execute("insert into mdl_fpmembers values(NULL,".$member->id_user.",".$member->id_group.",".$member->moderador.")");
        $DB->execute("insert into mdl_fpgain values(NULL,".$member->id_user.",0)");
        $url_local = required_param('url_local', PARAM_TEXT);
		header("Location: ".$url_local."#grupos");
        break;
    case 'ad_mmember':
        //$member = new stdClass();
        $member->id_user = required_param('member_id', PARAM_TEXT);
        $member->id_group = $_SESSION['idgroup'];
        $member->moderador = 2;
        //$DB->insert_record('fpmembers',$member);
        $DB->execute("insert into mdl_fpmembers values(NULL,".$member->id_user.",".$member->id_group.",".$member->moderador.")");
        $DB->execute("insert into mdl_fpgain values(NULL,".$member->id_user.",0)");
        $url_local = required_param('url_local', PARAM_TEXT);
		header("Location: ".$url_local."#grupos");
        break;    
    case 'ad_task':
        
        //upload_arquivo();
        $teste = new stdClass();
        $teste->nome = required_param('nome', PARAM_TEXT);
        $teste->arquivo = upload_arquivo($caminhoTarefas);//required_param('arq', PARAM_TEXT);
        $teste->data_inicio = required_param('data_inicio', PARAM_TEXT);
        $teste->data_fim = required_param('data_fim', PARAM_TEXT);
        $teste->ultima = required_param('ultima',PARAM_INT);
        $teste->descricao = $_POST['descricao'];
        $teste->id_curso = required_param('id_curso',PARAM_INT);
        //echo var_dump($teste);
        $DB->insert_record('fptasks',$teste);
        $url_local = required_param('url_local', PARAM_TEXT);
        header("Location: ".$url_local."#tarefas");
        
        break;
    case 'rm_task':
        $id_task = required_param('task_id',PARAM_TEXT);
        // 1- recuperar registro
        // 2- deletar $fileToDeletePath = caminhodaTarefa/registro->nomeArquivo
        // unlink($fileToDeletePath);
        $arquivo = required_param('task_arquivo',PARAM_TEXT);
        $fileTemp = $caminhoTarefas."/".$arquivo;
        if (file_exists($fileTemp)){
            unlink($fileTemp);
        }
        $DB->delete_records('fptasks', array("id" => $id_task));
        $url_local = required_param('url_local', PARAM_TEXT);
        header("Location: ".$url_local."#tarefas");
        break;
    case 'up_task':
        $teste = new stdClass();
        $teste->id = required_param('id', PARAM_TEXT);
        $teste->name = required_param('nome', PARAM_TEXT);
        // DELETAR ARQUIVO UNLINK(ARQUIVOPATH/required_param('task_arq', PARAM_TEXT));)
        verificaArquivo($caminhoTarefas, 'task');
        $teste->arquivo = upload_arquivo($caminhoTarefas);//upload_arquivo(caminhodoarquivo/required_param('task_arq', PARAM_TEXT)); :)
        $teste->data_inicio = required_param('data_inicio', PARAM_TEXT);
        $teste->data_fim = required_param('data_fim', PARAM_TEXT);
        $teste->descricao = $_POST['descricao'];
        $teste->ultima=0;
        $teste->knowledge_area = $_POST['knowledge_area'];
        $teste->not_related_words = $_POST['not_related_words'];
        $DB->update_record('invertclass',$teste);
        $url_local = required_param('url_local', PARAM_TEXT);
		header("Location: ".$url_local."#tarefas");
        break;
    case 'ad_ref':
        $teste = new stdClass();
        $teste->descricao = required_param('ref_desc', PARAM_TEXT);
        $teste->arquivo = upload_arquivo($caminhoReferencias);//required_param('ref_file', PARAM_TEXT);
        $teste->moduleid = required_param('moduleid', PARAM_INT);
        $DB->insert_record('fpref',$teste);
        $url_local = required_param('url_local', PARAM_TEXT);
		header("Location: ".$url_local."#referencias");
        break;
    case 'rm_ref':
        $id_ref = required_param('ref_id',PARAM_TEXT);
        // 1- recuperar registro
        // 2- deletar $fileToDeletePath = caminhodaReferencia/registro->nomeArquivo
        // unlink($fileToDeletePath);
        $arquivo = required_param('ref_arquivo',PARAM_TEXT);
        $fileTemp = $caminhoReferencias."/".$arquivo;
        if (file_exists($fileTemp)){
            unlink($fileTemp);
        }
        $DB->delete_records('fpref', array("id" => $id_ref));
        $url_local = required_param('url_local', PARAM_TEXT);
        header("Location: ".$url_local."#referencias");
        break;
    case 'up_ref':
        $teste = new stdClass();
        $teste->id = required_param('idr', PARAM_TEXT);
        $teste->descricao = required_param('ref_desc', PARAM_TEXT);
        // DELETAR ARQUIVO UNLINK(ARQUIVOPATH/required_param('ref_arq', PARAM_TEXT));)
        verificaArquivo($caminhoReferencias, 'ref');
        $teste->arquivo = upload_arquivo($caminhoReferencias);//upload_arquivo(caminhodoarquivo/required_param('ref_arq', PARAM_TEXT);
        $teste->id_task = required_param('ref_id_task', PARAM_TEXT);
        $teste->id_course = required_param('id_curso',PARAM_INT);//$COURSE->id;
        $DB->update_record('fpref',$teste);
        $url_local = required_param('url_local', PARAM_TEXT);
		header("Location: ".$url_local."#referencias");
        break;
    case 'rm_group':
        $id_group = required_param('group_id',PARAM_TEXT);
        // remover registro da mdl_fpavaliar: delete from fp_avaliar where id_group = $id_grupo e fpmembers: delete from mdl_fpmembers where id_group = $id_grupo
        // e fpgain
        $gains = new stdClass();
        $gains = $DB->get_records_sql("SELECT id FROM mdl_fpgain as gain WHERE gain.id_user in (SELECT memb.id_user FROM mdl_fpmembers as memb where memb.id_group = $id_group);");
        foreach ($gains as $gain){
            $DB->delete_records('fpgain', array("id" => $gain->id));
        }
        $DB->delete_records('fpmembers', array("id_group" => $id_group));
        $DB->delete_records('fpavaliar', array("id_group" => $id_group));
        $DB->delete_records('fpgroups', array("id" => $id_group));
        
        $url_local = required_param('url_local', PARAM_TEXT);
        header("Location: ".$url_local."#referencias");
        break;
    case 'rm_gmember':
        $id_group = required_param('group_id',PARAM_TEXT);
        $ids = required_param('ids',PARAM_TEXT);
        $DB->delete_records('fpmembers', array("id_group" => $id_group, "id_user" => $ids));
        $DB->delete_records('fpgain', array("id_user" => $ids));
        $url_local = required_param('url_local', PARAM_TEXT);
        header("Location: ".$url_local."#grupos");
        break;
    case 'up_mmember':
        $id_group = required_param('group_id',PARAM_TEXT);
        $ids = required_param('ids',PARAM_TEXT);
        $DB->execute("update mdl_fpmembers set moderador=1 where moderador=2");
        $DB->execute("update mdl_fpmembers set moderador=2 where id_user=".$ids);
        $url_local = required_param('url_local', PARAM_TEXT);
        header("Location: ".$url_local."#referencias");
        break;
    case 'up_gain':
        $gain = $_POST['gain'];
        $ids = $_POST['member_id'];
        $DB->execute("update mdl_fpgain set aproveitamento=".$gain." where id_user=".$ids);
        $url_local = required_param('url_local', PARAM_TEXT);
        header("Location: ".$url_local."#referencias");
        break;
    case 'add_feedback':
        $feedback = new stdClass();
        $feedback->id_user = $_POST['taskfb_idaluno'];
        //echo "alert(".$_POST['taskfb_name'].")";
        $feedback->id_task = $_POST['taskfb_name'];
        $feedback->comentario = $_POST['comments'];
        $feedback->id_course = required_param('id_curso',PARAM_INT);//$COURSE->id;
        $DB->insert_record('fpfeedback',$feedback);
        $url_local = required_param('url_local', PARAM_TEXT);
        header("Location: ".$url_local."#referencias");
        break;
    case 'add_ava':
        $ava = new stdClass();
        $ava->id = $_POST['aval_id'];
        $ava->id_group = $_POST['avagroup_id'];
        $ava->nota = $_POST['nota'];
        $ava->situacao=1;
        $ava->feedback= $_POST['comments'];
        $ava->id_task = $_POST['avatask'];
        $ava->id_course = required_param('id_curso',PARAM_INT);//$COURSE->id;
        $DB->update_record('fpavaliar',$ava);
        $url_local = required_param('url_local', PARAM_TEXT);
        header("Location: ".$url_local."#referencias");
        break;
    // NAO E MAIS UTILIZADO
    case 'download':
        $nomeArquivo = optional_param('file',null,PARAM_TEXT);
        downloadzin($nomeArquivo);
    break;

    case 'add_invertclass_requirement':
        
        
        $old_feature = $DB->get_record_sql('select * from mdl_fp_features where descricao = \''.required_param('requirement_description', PARAM_TEXT).'\';');
        //echo var_dump($old_feature->id);
        
        //CRIA UM OBJETO FEATURE
        $feature = new stdClass();
        $feature->id = $old_feature->id;
        $feature->descricao = required_param('requirement_description', PARAM_TEXT);
        $feature->categoria = 0;
        
        //SALVA OS DADOS DO OBJETO REQUERIMENTO NO BANCO DE DADOS
        $feature->id = invertclass_save('fp_features', $feature);
        
        if($feature->id)
            $old_requirement = $DB->get_record_sql('select * from mdl_fp_requirements where featureid = '.$feature->id.';');
        $invertclassid = required_param('id', PARAM_INT);
        $url_invertclass = new moodle_url('/mod/invertclass/view.php', array('id' => $invertclassid));
        
        if(empty($old_requirement)){
            //CRIA UM OBJETO REQUERIMENTO
            $pr = new stdClass();
            $pr->invertclassid = (int) $invertclassid;
            $pr->featureid = $feature->id;
            $pr->value = required_param('level', PARAM_TEXT);
            $pr->importance = required_param('importance', PARAM_FLOAT);
        
            //SALVA OS DADOS DO OBJETO REQUERIMENTO NO BANCO DE DADOS
            invertclass_save_requirement($pr);
        } else {
            header("Location: ".$url_invertclass);
        }
        
        header("Location: ".$url_invertclass);
        
        break;

    case 'delete_requirement':

        //DELETA O REQUERIMENTO DO BANCO DE DADOS
        $requirementid = required_param('reqid', PARAM_INT);
        $id = required_param('id', PARAM_INT);
        invertclass_delete('fp_requirements', $requirementid);

        $url_invertclass = new moodle_url('/mod/invertclass/view.php', array('id' => $id));
        header("Location: ".$url_invertclass);

        break;
}    

function upload_arquivo($pathzin){
    if(isset($_POST['send'])&&isset($_FILES['arq'])){
        $arq = $_FILES['arq'];
        $nomeArq = tratar_arquivo_upload($arq['name']);//str_replace(' ', '-', $arq['name']);
        //$tamanhoMax = 8388608;
        if(!move_uploaded_file($arq['tmp_name'], $pathzin.'/'.$nomeArq)){
			echo 'ERRO no Upload!';
            var_dump($arq);
            return "";
        }
        return $nomeArq;
    }
    return "";
    
}

function tratar_arquivo_upload($string) {
	// pegando a extensao do arquivo
	$partes 	= explode(".", $string);
	$extensao 	= $partes[count($partes)-1];
	$nome = md5(time()).".".$extensao;
	return $nome;
 }

function verificaArquivo($caminho, $reftask){
    
    $fileTemp = $caminho."/".required_param($reftask.'_arq', PARAM_TEXT);
    if (file_exists($fileTemp)){
        unlink($fileTemp);
    }

}

?>
</div>
<?php
function exibirMensagem($msg) { ?>
  <script type="text/javascript">alert('<?php echo $msg?>')</script>
<?php 
}
?>