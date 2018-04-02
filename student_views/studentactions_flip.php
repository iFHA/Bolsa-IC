<div>
<?php

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once(dirname(dirname(__FILE__)).'/lib.php');
require_once(dirname(dirname(__FILE__)).'/locallib.php');

$action = optional_param('action',null,PARAM_TEXT);
$tid = optional_param('task_id',null,PARAM_TEXT);
$gid = optional_param('group_id',null,PARAM_TEXT);
echo "Testando..";
global $DB;

switch($action){
	case 'next_step':
		
		$groupSteps = new stdClass();
		$moduleid = required_param('moduleid', PARAM_INT);
		$groupSteps->etapaid = required_param('etapaid', PARAM_INT);
		$ultima = required_param('ultima', PARAM_INT);
		$groupSteps->groupid = required_param('grupoid', PARAM_INT);
		$etapatipo = required_param('etapatipo', PARAM_INT);
		$groupSteps->arquivoid;
		$nome_original = '';
		$nome_final = '';

		if($etapatipo){
			$groupSteps->resposta = optional_param('resposta', 0, PARAM_TEXT);
		} else {
			// tratar upload
			$nome_final = upload_arquivo('../arquivos/anexos_grupos');
			$nome_original = ($nome_final != '') ? $_FILES['arq']['name'] : '';
			echo 'nome final: '.$nome_final;
			echo 'nome original: '.$nome_original;
		}

		if ($nome_final != '' && $nome_original != ''){
			$groupSteps->arquivoid = $DB->insert_record('fpanexos', array('nome_original' => $nome_original, 'nome_final' => $nome_final));
		}

		$DB->insert_record('invertclass_group_steps', $groupSteps);
		if(!$ultima){
			$proximaEtapa = $DB->get_record_sql('SELECT etapa.id FROM mdl_invertclass_steps AS etapa WHERE etapa.id > '.$groupSteps->etapaid.' AND moduleid = '.$moduleid.' LIMIT 1;');
			$DB->update_record('fpgroups', array('id' => $groupSteps->groupid, 'etapaatual' => $proximaEtapa->id));
		}else {
			// fechando o grupo
			$DB->update_record('fpgroups', array('id' => $groupSteps->groupid, 'finalizado' => 1));
		}
		
		/* TODO: salvar a resposta do aluno criando o registro invertclass_group_steps
		verificar se a etapa atual é a última etapa da tarefa, se sim, finaliza aí.
		(SELECT FROM mdl_invertclass_steps WHERE id = '.$etapaatual.';');
		if (etapa->ultima) envia e finaliza a atividade, senão, avança para a próxima
		*/
		$url_local = required_param('url_local', PARAM_TEXT);
		header('Location: '.$url_local.'#etapas');

	break;
	case 'update_feature':
		$userid = required_param('userid', PARAM_INT);
		$featureid = required_param('featureid', PARAM_INT);
		$featurevalue = required_param('featurevalue', PARAM_INT);
		$DB->execute('UPDATE mdl_problem_user_features as f SET f.value='.$featurevalue.' WHERE f.userid='.$userid.' and f.featureid='.$featureid);
		$url_local = required_param('url_local', PARAM_TEXT);
		header('Location: '.$url_local.'#perfil');
	break;

	case 'upload':
		
		$nomeArq = upload_arquivo('../arquivos/anexos_grupos');
		// verificar se o registro jรก existe, se sim, deletar o registro do bd e criar um novo
		$anexo_antigo = $DB->get_records_sql("select * from mdl_fpanexos where tarefa_id = {$tid} and grupo_id = {$gid}");
		//$anexo_antigo = $DB->get_records_sql("select * from mdl_fpanexos where nome_anexo = '{$nomeArq}'");
		if($anexo_antigo == null){
			$DB->execute("insert into mdl_fpanexos values('{$nomeArq}', {$gid}, {$tid})");
		}else{
			foreach($anexo_antigo as $anexo){
				//deletar arquivo do bd e da pasta
				$DB->execute("delete from mdl_fpanexos where nome_anexo = '{$anexo->nome_anexo}'");
				unlink("../arquivos/anexos_grupos/{$anexo->nome_anexo}");// ou $nomeArq
				$DB->execute("insert into mdl_fpanexos values('{$nomeArq}', {$gid}, {$tid})");
				
			}
		}
		$url_local = required_param('url_local', PARAM_TEXT);
		header("Location: {$url_local}");
		break;

	case 'add_feature':
		$feature = $DB->get_record("fp_features", array("descricao" => required_param('feature_description', PARAM_TEXT)));

		if(!$feature->id){
			$feature->description = required_param('feature_description', PARAM_TEXT);
			$feature->id = invertclass_save('fp_features', $feature);
		}

		//CRIA UM OBJETO CARACTERÍSTICA

		$uf = $DB->get_record("fp_user_features", array("featureid" => $feature->id));

		$uf->featureid = $feature->id;
		$uf->value = required_param('level', PARAM_TEXT);
		$uf->userid = $USER->id;
		
		//SALVA OS DADOS DO OBJETO CARACTERÍSTICA NO BANCO DE DADOS
		problem_save('fp_user_features', $uf);

		$url_fp = new moodle_url('/mod/invertclass/view.php', array('id' => $id));
		echo '<br /><br /><a href="'.$url_fp.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';

		break;
		
	case 'delete_feature':

		//DELETA A CARACTERÍSTICA DO BANCO DE DADOS
		$params->featureid = required_param('featureid', PARAM_INT);
		problem_delete('fp_user_features', $params->featureid);

		$url_invertclass = new moodle_url('/mod/invertclass/view.php', array('id' => $id));
		echo '<br /><br /><a href="'.$url_invertclass.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';
		
		break;

	case 'edit_prefered_times':

		//CRIA UM OBJETO PERFIL
		$user_prefered_times = new stdClass();
		//VERIFICA SE PERFIL JÁ EXISTE, SE SIM PEGA O ID
		if($pid = $DB->get_record('problem_user_prefered_times', array("userid" => $USER->id))->id)
			$user_prefered_times->id = $pid;

		$user_prefered_times->sunday = optional_param('sun_m', 0, PARAM_TEXT) . optional_param('sun_t', 0, PARAM_TEXT) . optional_param('sun_n', 0, PARAM_TEXT);
		$user_prefered_times->monday = optional_param('mon_m', 0, PARAM_TEXT) . optional_param('mon_t', 0, PARAM_TEXT) . optional_param('mon_n', 0, PARAM_TEXT);
		$user_prefered_times->tuesday = optional_param('tue_m', 0, PARAM_TEXT) . optional_param('tue_t', 0, PARAM_TEXT) . optional_param('tue_n', 0, PARAM_TEXT);
		$user_prefered_times->wednesday = optional_param('wed_m', 0, PARAM_TEXT) . optional_param('wed_t', 0, PARAM_TEXT) . optional_param('wed_n', 0, PARAM_TEXT);
		$user_prefered_times->thursday = optional_param('thu_m', 0, PARAM_TEXT) . optional_param('thu_t', 0, PARAM_TEXT) . optional_param('thu_n', 0, PARAM_TEXT);
		$user_prefered_times->friday = optional_param('fri_m', 0, PARAM_TEXT) . optional_param('fri_t', 0, PARAM_TEXT) . optional_param('fri_n', 0, PARAM_TEXT);
		$user_prefered_times->saturday = optional_param('sat_m', 0, PARAM_TEXT) . optional_param('sat_t', 0, PARAM_TEXT) . optional_param('sat_n', 0, PARAM_TEXT);
		$user_prefered_times->userid = $USER->id;

		//SALVA OS DADOS DO OBJETO PERFIL NO BANCO DE DADOS
		problem_save('problem_user_prefered_times', $user_prefered_times);

		$url_invertclass = new moodle_url('/mod/invertclass/view.php', array('id' => required_param('id', PARAM_INT)));
		//echo '<br /><br /><a href="'.$url_invertclass.'" class="btn btn-primary"> < VOLTAR > </a><br /><br />';
		header('Location: '.$url_invertclass.'#perfil');
		break;
		
}
/*function upload_arquivo($pathzin){
    if(isset($_POST['send'])&&isset($_FILES['arq'])){
        $arq = $_FILES['arq'];
        $nomeArq = str_replace(' ', '-', $arq['name']);
        //$tamanhoMax = 8388608;
        if(!move_uploaded_file($arq['tmp_name'], $pathzin.'/'.$nomeArq)){
			echo 'ERRO no Upload!';
			var_dump($arq);
        }
        return $nomeArq;
    }
    return "";
    
}*/

function upload_arquivo($pathzin){
    if(isset($_POST['send'])&&isset($_FILES['arq'])){
        $arq = $_FILES['arq'];
		$nomeArq = tratar_arquivo_upload($arq['name']);//str_replace(' ', '-', $arq['name']);
		var_dump($_FILES['arq']);
		var_dump($nomeArq);
        //$tamanhoMax = 8388608;
        if(!move_uploaded_file($arq['tmp_name'], $pathzin.'/'.$nomeArq)){
			echo 'ERRO no Upload!';
			var_dump($arq);
        }
        return $nomeArq;
    }
    return "";
}

function tratar_arquivo_upload($string) {
	// pegando a extensao do arquivo
	$partes 	= explode(".", $string);
	$extensao 	= $partes[count($partes)-1];
	$nome = "".md5(time()).".".$extensao;
	return $nome;
}

?>
</div>