function alerta(msg, modalText = '[data-js="modal2-text"]', modal = '[data-js="alert-modal"]') {
    const $modal = $(modal);
    const $ModalText = $(modalText);
    $ModalText.text(msg);
    $modal.modal();
}

$(document).ready(function(){
    /*
    $('#buscar').click(function () {
        buscar($("#palavra").val())
    });
    var dadozin = $("#dados");//document.getElementById("dados");
    function carregando(c){
        removeFilhos(c);
        var img = $("<img/>", {
            src: "./ajax-loader.gif"
        });
        c.append(img);
    }
    function removeFilhos(c){
        c.empty();
    }
    function buscar(palavra)
    {
        var page = "busca.php";
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: page,
            beforeSend: function () {
                //$("#dados").html("Carregando...");
                carregando(dadozin);
            },
            data: {palavra: palavra},
            success: function (msg)
            {
                removeFilhos(dadozin);
                $("#dados").html(msg);
            }
        });
    }
    */
    // Exibindo a tab referente ao elemento #tab da url
    var type = window.location.hash.substr(1);
    if (type) {
        $('.nav-tabs a[href="#'+type+'"]').tab('show');
    }
});