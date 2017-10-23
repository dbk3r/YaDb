
$(document).ready(function () {


  var baseUrl = OC.generateUrl('/apps/yadisbo/disboDB/2');
  $.get(baseUrl).done(function(content){
    $(".db-topics-content").after(content);
  });


    $(".btn-new-save").click(function() {
      $(".db-new-topic-bg").fadeIn();
      $(".db-new-topic").slideDown();
    });

    $(".btn-close-new-topic, .db-new-topic-bg").click(function() {
      $(".db-new-topic").slideUp();
      $(".db-new-topic-bg").fadeOut();
    });

    $(".db-topics-row").live('click', function(e){
      if($("#db-topic-content-" + $(this).attr('id')).css('display') == 'none'){
        $("#db-topic-content-" + $(this).attr('id')).show();
      } else {
       $("#db-topic-content-" + $(this).attr('id')).hide();
      }

    });

});
