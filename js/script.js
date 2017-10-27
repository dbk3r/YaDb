
$(document).ready(function () {

  tinymce.init({
    selector: '#db-new-topic-editor',
    resize: false,
    height: 200,
    menubar: false,
    statusbar: false,
    plugins: [
      'advlist autolink lists link image charmap print preview anchor textcolor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table contextmenu paste code'
    ],
    toolbar: 'insert | undo redo |  formatselect | bold italic forecolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ',
    content_css: [
      '../../css/style.css'
    ]
  });


  var baseUrl = OC.generateUrl('/apps/yadisbo/showall/2');
  $.get(baseUrl).done(function(content){
    $(".db-topics-content").after(content);
  });



    $("#btn-new-topic").click(function() {
      var newtopic = OC.generateUrl('/apps/yadisbo/newreplytopic/new');
      $.get(newtopic).done(function(content){
        $(".db-new-topic-content-header").html(content);
      });
      $(".db-new-topic-bg").fadeIn();
      $(".db-new-topic").slideDown();
    });





    $("#btn-close-topic, .db-new-topic-bg").click(function() {
      tinymce.activeEditor.setContent("");
      $(".db-new-topic").slideUp();
      $(".db-new-topic-bg").fadeOut();

    });


/// Topic Inhalt anzeigen
    $(".db-topics-row").live('click', function(e){
      var current_uuid = $(this).attr('id');
      if($("#db-topic-content-" + $(this).attr('id')).css('display') == 'none'){
        $("#db-topic-content-" + $(this).attr('id')).show();
        var showtopic = OC.generateUrl('/apps/yadisbo/showtopic/'+ $(this).attr('id'));
        $.get(showtopic).done(function(content){
          $("#db-topic-content-" + current_uuid).html(content);
        });
      } else {
       $("#db-topic-content-" + $(this).attr('id')).hide();
      }

    });

});
