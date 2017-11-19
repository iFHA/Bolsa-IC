<?php

// pega o endereço do arquivo
$nomeArquivo = $_POST['file'];
$endereco = '../arquivos/tarefas/'.$nomeArquivo;
$file = $endereco;
//var_dump($nomeArquivo);
// verificando a existencia do arquivo
if (file_exists($file)) {
    header("Content-Type: application/save"); 
    header("Content-Length:".filesize($file)); 
    header('Content-Disposition: attachment; filename="' . $nomeArquivo . '"'); 
    header("Content-Transfer-Encoding: binary");
    header('Expires: 0'); 
    header('Pragma: no-cache'); 
    // nesse momento o arquivo é lido e enviado
    $fp = fopen("$file", "r"); 
    fpassthru($fp); 
    fclose($fp);
} else {
    echo "<script>alert('O arquivo \"{$nomeArquivo}\" nao existe no servidor');history.back();</script>";
}

?>