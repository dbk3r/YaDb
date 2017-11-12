
function isEmpty(str) {
    return (!str || 0 === str.length);
}

function getTopics(p,search) {

  var baseurl = OC.generateUrl('/apps/yadisbo/showall');
  var formdata = {
                  id: p,
                  search: search,
                };
  $.post(baseurl, formdata).done(function(content) {
      $(".db-topic-div").last().after(content).fadeIn();
  });
}

function pinTopic(uuid,pin) {
  var baseurl = OC.generateUrl('/apps/yadisbo/pintopic');
  var formdata = {
                  pin: pin,
                  uuid: uuid,
                };
  $.post(baseurl, formdata).done(function(response) {

      ///alert(response);
  });
}

$(document).ready(function () {

  var track_page = 1;
  var loading = false;

  $("#app-content").scroll(function(){
  		if (jQuery("#app-content").scrollTop() + jQuery("#app-content").height() >= jQuery(".yadb-content").height()){
  			track_page++;
  			getTopics(track_page);
  		}
  });

  tinymce.init({
    selector: '#db-new-topic-editor',
    resize: false,
    height: 375,
    menubar: false,
    statusbar: false,
    paste_data_images: true,
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


  getTopics(1,"");


    $("#db-search").keyup(function(e) {
      if(e.keyCode == 13)
      {
        $(".db-topic-div").not(':first').remove();
        getTopics(1,$("#db-search").val());
      }
    });

    $(".btn-pin").live('click', function(e) {
      var current_uuid = $(this).attr('uuid');
      /// unpin Topic
      if($("#pin-"+current_uuid).hasClass("btn-pinned")) {
        $("#pin-"+current_uuid).removeClass("btn-pinned");
        pinTopic(current_uuid,0);
      } else {
        /// pin Topic
        $("#pin-"+current_uuid).addClass("btn-pinned");
        pinTopic(current_uuid,1)
      }
    });


//neuen Topic anlegen
    $("#btn-new-topic").click(function() {
      var newtopic = OC.generateUrl('/apps/yadisbo/topicformheader/new');
      $.get(newtopic).done(function(content){
        $(".db-new-topic-content-header").html(content);
      });
      $(".btn_newreplysave").html("add Topic");
      $(".btn_newreplysave").attr('action', 'new');
      $(".db-new-topic-bg").fadeIn();
      $(".db-new-topic").slideDown();
    });



    $("#btn-close-topic, .db-new-topic-bg").click(function() {
      tinymce.activeEditor.setContent("");
      $(".db-new-topic").slideUp();
      $(".db-new-topic-bg").fadeOut();

    });


/// Topic Inhalt speichern
    $(".btn_newreplysave").click(function(){
      if ($(this).attr('action') == "save") {
        var baseurl = OC.generateUrl('/apps/yadisbo/savetopic');
        var formdata = {
                        content: tinymce.activeEditor.getContent(),
                        id: $("#nrs-id").val(),
                      };
        $.post(baseurl, formdata).done(function(response) {
            $("#db-topics-content-td-" + $("#nrs-id").val()).html(tinymce.activeEditor.getContent());
            tinymce.activeEditor.setContent("");
            $(".db-new-topic").slideUp();
            $(".db-new-topic-bg").fadeOut();
            ///alert(response);
        });

      }


/// neuen Topic anlegen
      if ($(this).attr('action') == "new") {
        var baseurl = OC.generateUrl('/apps/yadisbo/newtopic');
        var formdata = {
                        title: $(".db-new-topic-input").val(),
                        content: tinymce.activeEditor.getContent(),
                        category: $("#dbCat").val(),
                      };
        $.post(baseurl, formdata).done(function(response) {
            tinymce.activeEditor.setContent("");
            $(".db-new-topic").slideUp();
            $(".db-new-topic-bg").fadeOut();
            $(".db-topic-div").not(':first').remove();
            getTopics(1,"");
        });
      }

      /// auf Topic antworten
      if ($(this).attr('action') == "reply") {
        var uuid = $(this).attr('value');
        var baseurl = OC.generateUrl('/apps/yadisbo/replytopic');
        var formdata = {
                        content: tinymce.activeEditor.getContent(),
                        uuid: $(this).attr('value'),
                      };
        $.post(baseurl, formdata).done(function(response) {
            tinymce.activeEditor.setContent("");
            $(".db-new-topic").slideUp();
            $(".db-new-topic-bg").fadeOut();
            ///alert(response);
        });
        var showtopic = OC.generateUrl('/apps/yadisbo/showtopic/'+ uuid);
        $.get(showtopic).done(function(content){
          $("#db-topic-content-" + uuid).html(content);
        });
      }
    });


/// Topic kommentieren
    $(".btn-reply").live('click', function(e) {
      var topicheader = OC.generateUrl('/apps/yadisbo/topicformheader/'+$(this).attr('dbid'));
      $.get(topicheader).done(function(content){
        $(".db-new-topic-content-header").html(content);
      });

      $(".btn_newreplysave").html("reply");
      $(".btn_newreplysave").val($(this).attr('id'));
      $(".btn_newreplysave").attr('action', 'reply');
      $(".db-new-topic-bg").fadeIn();
      $(".db-new-topic").slideDown();

    });



/// Topic Inhalt bearbeiten
    $(".btn-edit-topic").live('click', function(e) {
      var topicheader = OC.generateUrl('/apps/yadisbo/topicformheader/'+$(this).attr('id'));
      $.get(topicheader).done(function(content){
        $(".db-new-topic-content-header").html(content);
      });
      var edittopic = OC.generateUrl('/apps/yadisbo/topiccontent/'+ $(this).attr('id'));
      $.get(edittopic).done(function(content){
        tinymce.activeEditor.setContent(content);
      });
      $(".btn_newreplysave").html("save");
      $(".btn_newreplysave").val($(this).attr('id'));
      $(".btn_newreplysave").attr('action', 'save');
      $(".db-new-topic-bg").fadeIn();
      $(".db-new-topic").slideDown();
    });


    /// Topic löschen
        $(".btn-del-topic").live('click', function(e) {
          var current_uuid = $(this).attr('id')
          if (confirm('do you realy want to delete this topic?')) {
            var del = OC.generateUrl('/apps/yadisbo/deletetopic/'+current_uuid);
            $.get(del).done(function(delres){
              $("#topic-content-"+current_uuid).slideUp(function() {
                $("#topic-content-"+current_uuid).remove();
                $("#trenner-"+current_uuid).remove();
              });
              if(!$.isNumeric(current_uuid)) {
                $("#db-topic-div-"+current_uuid).slideUp(function() {
                  $("#db-topic-div-"+current_uuid).remove();
                  $("#trenner-"+current_uuid).remove();

                });
              }

            });
          }
        });


/// Topic Inhalt anzeigen
    $(".db-topics-row").live('click', function(e){
      var current_uuid = $(this).attr('id');
      if($("#db-topic-content-" + $(this).attr('id')).css('display') == 'none'){
        $('[id^="db-topic-content-"]').hide();
        $('[id^="db-topic-div-"]').removeClass('db-topic-div-active');
        $("#db-topic-content-" + $(this).attr('id')).show();
        $("#db-topic-div-"+current_uuid).addClass('db-topic-div-active');
        var showtopic = OC.generateUrl('/apps/yadisbo/showtopic/'+ $(this).attr('id'));
        $.get(showtopic).done(function(content){
          $("#db-topic-content-" + current_uuid).html(content);
        });
      } else {
       $("#db-topic-content-" + $(this).attr('id')).hide();
      }

    });

});
