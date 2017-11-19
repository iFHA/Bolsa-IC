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