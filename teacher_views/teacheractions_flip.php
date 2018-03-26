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

    case 'gvinculation':
    $groupIndex = required_param('groupindex', PARAM_INT);
    $moduleid = required_param('moduleid', PARAM_INT);
    $gruposRecomendados = $_SESSION['grupos_recomendados'];
    $grupoRecomendado = $gruposRecomendados[$groupIndex];

    $podeVincular = true;
    foreach($grupoRecomendado->membros as $membro){
        // verifica se algum membro recomendado já está na tabela fpmembers, se sim, não pode vincular
        $fpmember = $DB->get_record_sql('SELECT membro.id FROM mdl_fpmembers AS membro, mdl_fpgroups AS grupo WHERE membro.id_user = '.$membro->id.' AND membro.id_group = grupo.id AND grupo.moduleid = '.$moduleid);
        if(!empty($fpmember))
            $podeVincular = false;
    }
    if($podeVincular){
        // vincula grupo e membros do mesmo

        // TODO: entrar aqui somente se todas as etapas para essa tarefa foram definidas, ou seja, se a ultima
        // etapa tem o campo ultima = 1;

        $grupo = new stdClass();
        $grupo->nome = $grupoRecomendado->nome;
        $grupo->moduleid = $grupoRecomendado->moduleid;
        // inserir a etapa atual do grupo como sendo a primeira da tarefa
        $grupo->etapaatual = $DB->get_record_sql('SELECT * FROM mdl_invertclass_steps WHERE moduleid = '.$grupo->moduleid.' LIMIT 1;')->id;
        
        $fpGroupId = $DB->insert_record('fpgroups', $grupo);
        // ======== populando tabela fp-avaliar ========
        $DB->execute("insert into mdl_fpavaliar values (default, $fpGroupId, 0, 0, '', $grupoRecomendado->moduleid)");
        // =============================================

        foreach($grupoRecomendado->membros as $membro){
            // adicionar cada membro na tabela fpmembers
            $member = new stdClass();
            $member->id_user = $membro->id;
            $member->id_group = $fpGroupId;
            $member->moderador = 1;
            $DB->execute("insert into mdl_fpmembers values(NULL,".$member->id_user.",".$member->id_group.",".$member->moderador.")");
            $DB->execute("insert into mdl_fpgain values(NULL,".$member->id_user.",0)");
        }

        // remove o grupo e membros recomendados das tabelas rgroups e rmembers
        $DB->delete_records('invertclass_rmembers', array('id_group' => $grupoRecomendado->id));
        $DB->delete_records('invertclass_rgroups', array('id' => $grupoRecomendado->id));

    }

    unset($_SESSION['grupos_recomendados']);
    $url_local = required_param('url_local', PARAM_TEXT);
    header("Location: ".$url_local."#groups");

    break;

    case 'add_invertclass_step':
    $step = new stdClass();
    $step->descricao = required_param('descricao', PARAM_TEXT);
    $step->moduleid = required_param('moduleid', PARAM_INT);
    $step->prazo = optional_param('prazo', null, PARAM_INT);
    $step->tipo = required_param('tipo', PARAM_INT);
    $step->ultima = required_param('last',PARAM_INT);
    $step->data_inicio = required_param('data_inicio', PARAM_TEXT);
    $step->data_fim = required_param('data_fim', PARAM_TEXT);
    
    $DB->insert_record('invertclass_steps', $step);
    $url_local = required_param('url_local', PARAM_TEXT);
    header("Location: ".$url_local."#tarefas");

    break;

    case 'delete_invertclass_step':
    $stepid = required_param('etapaid', PARAM_INT);
    
    $DB->delete_records('invertclass_steps', array('id' => $stepid));
    $url_local = required_param('url_local', PARAM_TEXT);
    header("Location: ".$url_local."#tarefas");

    break;

    case 'ad_group':
        $grupo = new stdClass();
        $grupo->nome = required_param('gp_name', PARAM_TEXT);
        $grupo->moduleid = required_param('moduleid',PARAM_INT);
        // inserir a etapa atual do grupo como sendo a primeira da tarefa
        $grupo->etapaatual = $DB->get_record_sql('SELECT * FROM mdl_invertclass_steps WHERE moduleid = '.$grupo->moduleid.' LIMIT 1;')->id;
        
        $DB->insert_record('fpgroups', $grupo);
        //$gtemp = $DB->get_record('fpgroups', array("nome" => $grupo->nome));
        $gtemp = $DB->get_record_sql("select * from mdl_fpgroups where moduleid = {$grupo->moduleid} and nome = '{$grupo->nome}';");
        $_SESSION['idgroup']=$gtemp->id;
        $_SESSION['ntgroup']=$gtemp->nome;
        // ======== populando tabela fp-avaliar ========
        $group_id = $gtemp->id;
        
        $DB->execute("insert into mdl_fpavaliar values (default, $group_id, 0, 0, '', $grupo->moduleid)");
        // =============================================

        $url_local = required_param('url_local', PARAM_TEXT);
		header("Location: ".$url_local."#groups");
        break;
    case 'ad_gmember':
        //$member = new stdClass();
        $member->id_user = required_param('member_id', PARAM_TEXT);
        $member->id_group = $_SESSION['idgroup'];
        $member->moderador = 1;
        $DB->execute("insert into mdl_fpmembers values(NULL,".$member->id_user.",".$member->id_group.",".$member->moderador.")");
        $DB->execute("insert into mdl_fpgain values(NULL,".$member->id_user.",0)");
        $url_local = required_param('url_local', PARAM_TEXT);
		header("Location: ".$url_local."#groups");
        break;
    case 'add_gmember':
        //$member = new stdClass();
        $member->id_user = required_param('member_id', PARAM_TEXT);
        $member->id_group = required_param('idgroup', PARAM_TEXT);
        $member->moderador = 1;
        $DB->execute("insert into mdl_fpmembers values(NULL,".$member->id_user.",".$member->id_group.",".$member->moderador.")");
        $DB->execute("insert into mdl_fpgain values(NULL,".$member->id_user.",0)");
        $url_local = required_param('url_local', PARAM_TEXT);
		header("Location: ".$url_local."#groups");
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
		header("Location: ".$url_local."#groups");
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
        $teste->id = required_param('id', PARAM_INT);
        $teste->name = required_param('nome', PARAM_TEXT);
        // DELETAR ARQUIVO UNLINK(ARQUIVOPATH/required_param('task_arq', PARAM_TEXT));)
        $arquivoid = $DB->get_record('invertclass', array('id' => $teste->id))->arquivoid;
        if(!empty($arquivoid) && !empty($_FILES['arq']['name']) ){
            // já existe um arquivo upado, então o mesmo deve ser apagado caso o usuário tenha enviado outro
            $arquivozin = $DB->get_record('fpanexos', array('id' => $arquivoid));
            verificaArquivo($caminhoTarefas, $arquivozin->nome_final);
            // deletar arquivo do bd
            $DB->delete_records('fpanexos', array('id' => $arquivoid));
        }
        // tratar upload
        $nome_final = upload_arquivo($caminhoTarefas);
        $teste->arquivo = $nome_final;//upload_arquivo(caminhodoarquivo/required_param('task_arq', PARAM_TEXT)); :)
        $nome_original = (!empty($nome_final)) ? $_FILES['arq']['name'] : '';
        if (!empty($nome_final) && !empty($nome_original)){
			$teste->arquivoid = $DB->insert_record('fpanexos', array('nome_original' => $nome_original, 'nome_final' => $nome_final));
		}
        echo 'nome final: '.$nome_final;
        echo 'nome original: '.$nome_original;
        $teste->data_inicio = required_param('data_inicio', PARAM_TEXT);
        $teste->data_fim = required_param('data_fim', PARAM_TEXT);
        $teste->descricao = $_POST['descricao'];
        $teste->ultima=0;
        //$teste->knowledge_area = $_POST['knowledge_area'];
        //$teste->not_related_words = $_POST['not_related_words'];
        echo var_dump($teste);
        $DB->update_record('invertclass',$teste);
        //$DB->update_record('problem', array('id' => $teste->id, 'not_related_words' => $teste->not_related_words));
        
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

function verificaArquivo($caminho, $fileName){
    $fileTemp = $caminho.'/'.$fileName;
    if (file_exists($fileTemp)){
        unlink($fileTemp);
    }
}


/* function verificaArquivo($caminho, $reftask){
    
    $fileTemp = $caminho."/".required_param($reftask.'_arq', PARAM_TEXT);
    if (file_exists($fileTemp)){
        unlink($fileTemp);
    }

} */

?>
</div>
<?php
function exibirMensagem($msg) { ?>
  <script type="text/javascript">alert('<?php echo $msg?>')</script>
<?php 
}
?>