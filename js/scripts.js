function checkPresentEmail(emailInput){
  $.ajax({
   url: "email_availability.php",
   method:"POST",
   data:{'emailID':emailInput.value},
   success: function(data){
     $('#emailStatus').html(data);
   }
 });
}

//controllo client minima lunghezza e coincidenza della password con sua conferma 
function checkRegistration(form) {
  var elems = form.getElementsByTagName("input");
  if(elems[3].value.length < 8) {
    swal("Ops!", "Password non sicura: deve essere di almeno 8 caratteri!", "error");
    return false;
  }
  if(elems[4].value != elems[3].value) {
    swal("Ops!", "Le password non coincidono!", "error");
    return false;
  }
  return true;
}
