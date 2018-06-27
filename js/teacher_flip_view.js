($(() => {
  const $form = $('[data-js="form-update-step"]');
  const $modal = $('[data-js="alert-modal"]');
  const $ModalText = $('[data-js="modal2-text"]');
  $form.on('submit', (e) => {
    e.preventDefault();
    $.ajax({
      url: 'teacher_views/teacheractions_flip.php',//'http://localhost/moodle/mod/invertclass/teacher_views/teacheractions_flip.php',
      type: 'POST',
      data: $form.serialize(),
      beforeSend: function(){
        //
      },
      success: function (data) {
        const obj = JSON.parse(data);
        if (obj.msg == 1) {
          $ModalText.text('Sucesso ao Atualizar!');
          $('[data-js="modal-update-step"]').modal('toggle');
          $modal.modal();
        } else {
          $ModalText.text(data);
          $modal.modal();
        }
      }
    });
  });
}))();

function updateStep(elemento) {
  const $id = $(elemento).children('[data-js="etapaid"]').val();
  const $descricao = $(elemento).children('[data-js="descricao"]').val();
  const $dataFim = $(elemento).children('[data-js="dataFim"]').val();
  const $ultima = $(elemento).children('[data-js="ultima"]').val();
  
  const $modal = $('[data-js="modal-update-step"]');

  $('[data-js="update-step-etapaid"]').val($id);
  $('[data-js="update-step-description"]').val($descricao);
  $('[data-js="update-step-date"]').val($dataFim);
  $('[data-js="update-step-date"]').prop('min', $dataFim);

  if($ultima == 1) {
    $('[data-js="update-step-last1"]').prop('checked', true);
  } else {
    $('[data-js="update-step-last2"]').prop('checked', true);
  }
  $modal.modal();
}
