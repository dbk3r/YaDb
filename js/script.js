
$(document).ready(function () {


  var baseUrl = OC.generateUrl('/apps/yadisbo/disboDB/2');
  $.get(baseUrl).done(function(content){
    $(".db-content").append(content);
  });


    $(".btn-new-save").click(function() {
      $(".db-new-topic-bg").fadeIn();
      $(".db-new-topic").fadeIn();
    });

    $(".btn-close-new-topic").click(function() {
      $(".db-new-topic").fadeOut();
      $(".db-new-topic-bg").fadeOut();
    });


});
