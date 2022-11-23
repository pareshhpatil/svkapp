

function validatePass(p1, p2)
         {

              if (p1.value != p2.value || p1.value == '' || p2.value == '') 
	      {
			 p2.setCustomValidity('Passwords must match eachother');
              }
	      else
	      {
		         p2.setCustomValidity('');
	      }
	  }




    function sameaddress(status)
    {
        if (status == true) {
            document.getElementById("current_address").value = document.getElementById("address").value;
            document.getElementById("current_address2").value = document.getElementById("address2").value;
            document.getElementById("current_city").value = document.getElementById("city").value;
            document.getElementById("current_zip").value = document.getElementById("zip").value;
            document.getElementById("current_state").value = document.getElementById("state").value;
            document.getElementById("current_country").value = document.getElementById("country").value;
        }
        else
        {
            document.getElementById("current_address").value = '';
            document.getElementById("current_address2").value = '';
            document.getElementById("current_city").value = '';
            document.getElementById("current_zip").value = '';
            document.getElementById("current_state").value = '';
            document.getElementById("current_country").value = '';
        }
    }

    