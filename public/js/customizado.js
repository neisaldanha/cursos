/* Faz funcionar o Data table*/
// //cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json

/*
$(function () {
  var table = $('#geral').DataTable( {

    "language": {
      "url": "../traducao-datatable.json",
  },
      //rowReorder: {
      //    selector: 'td:nth-child(2)'
      //},
      "responsive": true,
      "buttons": ['copy', 'excel', 'pdf'],
      // faz aparecer os buttons
      //dom: 'Bfrtip',
  } );
  table
    //.order( [ 2, 'asc' ] )
    .draw();
});

*/

/* Auto Load Imagem Pessoa */
$(function () {
    $("#profileImage").click(function (e) {
        $("#imageUpload").click();
    });

    function fasterPreview(uploader) {
        if (uploader.files && uploader.files[0]) {
            $('#profileImage').attr('src',
                window.URL.createObjectURL(uploader.files[0]));
        }
    }

    $("#imageUpload").change(function () {
        fasterPreview(this);
    });

    $('#cpf').mask('000.000.000-00', { reverse: true });
    $('#cnpj').mask('00.000.000/0000-00', { reverse: true });
    $('#data_agenda').mask('00/00/0000', { reverse: true });

});

//RETORNA LOCAL DO EQUIPAMENTO PESQUISANDO PELO CODIGO


$(function () {
    $("input[name='codigo']").on('keyup', function () {
        var codigo = $(this).val();
        var equipamento = $("input[name='equipamento']");
        var local = $("input[name='local']");
        var sub_area = $("input[name='sub_area']");
        // console.log('chegou na consulta');

        $.ajax({
            url: 'busca-equipamento/' + codigo,
            method: 'GET',
            data: {codigo: codigo},
            success: function (result) {
                console.log(result[0]);
                equipamento.val(result[0].DESC_EQUIPAMENTO);
                local.val(result[0].LOCAL);
                sub_area.val(result[0].DESCRICAO);

            }
        });
    });
})

//************************* FIM *******************************************
