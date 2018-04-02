<div>
    <?php

        require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
        require_once(dirname(dirname(__FILE__)).'/lib.php');
        require_once(dirname(dirname(__FILE__)).'/locallib.php');
        $gnome = required_param('gnome',PARAM_TEXT);
        $groupid = required_param('idg',PARAM_INT);
        $grupo = get_grupo_etapas($groupid);
        //var_dump($grupo->etapas);
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">RESPOSTAS DO GRUPO <?=$gnome?></h3>
        </div>
        <div class="panel-body">
        
            <?php 
            if(!empty($grupo)){ ?>
            <table class="table table-hover">
                <?php
                if(!empty($grupo->etapas)){
                    foreach($grupo->etapas as $etapa){
                        $thereIsFile = false;
                        if($etapa->tipo == 0){
                            $etapa->arquivoZin = $DB->get_record('fpanexos', array('id' => $etapa->arquivoid));
                            if(!empty($etapa->arquivoZin)){
                                $thereIsFile = true;
                            }
                        }
                        
                        ?>
                        <tr><th colspan="3">DESCRIÇÃO DA ETAPA:</th></tr>
                        <tr>
                            <td colspan="3">
                                <!-- <textarea name="descricao" style="width:100%; height: 80px" disabled >
                                </textarea> -->
                                <p><?php echo $etapa->descricao; ?></p>
                            </td>
                        </tr>
                        <!--<td><input id="descricao" type="text" size=80 name="descricao" value="descricao"></td>-->
                        <tr>
                            <th>RESPOSTA DO GRUPO:</th>
                        </tr>
                            <?php if($thereIsFile){ ?>
                            <tr>
                                <td><?php echo $etapa->arquivoZin->nome_original; ?> </td>
                                <th>BAIXAR ANEXO:</th>
                                <td>
                                    <a href="./arquivos/anexos_grupos/<?=$etapa->arquivoZin->nome_final?>" target=_blank class='btn btn-primary'>Baixar</a>
                                </td>
                            </tr>
                            <?php 
                        }else{ ?>
                        <tr>
                            <td><p><?php echo $etapa->resposta; ?></p></td>
                        </tr>
                        <?php 
                        }
                    }
                }else { ?>
                    <div class="alert alert-danger" role="alert">
                        Este grupo ainda não respondeu nenhuma etapa!
                    </div>
                <?php
                }
                ?>
            </table>
            <?php 
                } else {
                /* echo '<div class="alert alert-danger" role="alert">';
                echo "Nenhuma sessão foi encontrada! Crie a primeira sessão para começar.";
                echo '</div>'; */
                }
                //echo '<a href="session_new.php?id=' . $cm->id . '&groupid=' .$group->id. '" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span><br />Criar nova sessão</a><br><br>';
            ?>
            <div class="col-md-8">
                <a href="javascript:history.back()"><button class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> VOLTAR</button></a>
            </div>
        </div>
    </div>
</div>