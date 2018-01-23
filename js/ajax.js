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
    if (type)
        $('.nav-tabs a[href="#'+type+'"]').tab('show');
    /*
    $('.btn-avaliar').click(function (e) { 
        $('#avalia_grupo').css('display', 'inherit');
        console.log("valor do aval_id:"+$('#aval_id').val());
        console.log("valor do avagroup_id:"+$('#avagroup_id').val());
        console.log("valor do avatask:"+$('#avatask').val());
        console.log("valor do action:"+$('#action').val());
        console.log("valor do id_curso:"+$('#id_curso').val());
    });
    */
});