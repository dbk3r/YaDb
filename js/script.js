
$(document).ready(function () {

    var baseUrl = OC.generateUrl('/apps/yadisbo/disboDB');
    $.get(baseUrl).done(function(user){
      alert("Hallo " + user);
    });
});
