$(document).ready(function(){
 
  $("#konusma").load("fonksiyon.php?chat=oku");
      setInterval(function() {
      $("#konusma").load("fonksiyon.php?chat=oku");
  },2000);
  $("#gonder").keyup(function(e){
      var text= $("#gonder").val();
      var karakter =$("#gonder").attr("maxlength");
      var uyead= $("#gonder").attr("sectionId");
      var uzunluk =text.length; 
      if (e.keyCode==13) {
         if (uzunluk>5 && uzunluk<karakter) {
             $.ajax({
                  type:"POST",
                  url:"fonksiyon.php?chat=ekle",
                  data:$("#mesajgonder").serialize(),
                  success: function(donen_bilgi) {
                      $("#gonder").val("");
                      $("#konusmalar").load("fonksiyon.php?chat=oku");								
                      
                      $("#konusmalar").scrollTop($("#konusmalar")[0].scrollHeight);	
                 }
             });
         }
         else{
          $("#gonder").val("");
         }
      }
  });
  $("#sohbetayar a").click(function() {
      var gelendeger=$(this).attr("sectionId");
      $.post("fonksiyon.php?chat=sohbetayar",{"secenek":gelendeger},function(gelenveri) {
          $("#sohbetayar").html(gelenveri).fadeIn();
          setInterval(function() {
         window.location.reload();
          },2000);
      });
  });
  $("#arkabtn").click(function() {
      $.ajax({
          type:"POST",
          url:"fonksiyon.php?chat=arkarenk",
          data:$("#arkaplan").serialize(),
          success: function(gelenveri) {
              $("#arkaplandegistir").html(gelenveri).fadeIn();
              setInterval(function() {
               window.location.reload();
              },2000);
          }
      });
  });
  $("#yazibtn").click(function() {
      $.ajax({
          type:"POST",
          url:"fonksiyon.php?chat=yazirenk",
          data:$("#yazirenk").serialize(),
          success: function(gelenveri) {
              $("#yazidegistir").html(gelenveri).fadeIn();
              setInterval(function() {
               window.location.reload();
              },2000);
          }
      });
  });


});