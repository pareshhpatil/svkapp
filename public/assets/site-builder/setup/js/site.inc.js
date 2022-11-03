var t = 1;
function timer(limit) {
	var timeinterval = setInterval(function () {
		limit = limit - 1;
		document.getElementById('timer').innerHTML = 'Try again in ' + limit;
		if (limit <= 0) {
			clearInterval(timeinterval);
			$('#vbtn').css('display', 'block');
			document.getElementById('timer').innerHTML = '';
		}
	}, 1000);
}

function verifyTXT() {
	try {
		$('#vbtn').css('display', 'none');
		document.getElementById('status_').innerHTML = 'Processing please wait...';
		var data = '';
		$.ajax({
			type: 'POST',
			url: '/merchant/website/verifytxt',
			data: data,
			success: function (data) {
				if (data == 0) {
					document.getElementById('status_').innerHTML = 'TXT verification failed please try again.';
					timer(30);
					return false;
				}
				else if (data == 2) {
					document.getElementById('status_').innerHTML = 'Domain name already exist please try another name.';
					$('#bbtn').css('display', 'block');
					return false;
				}
				else {
					$('form#vform').submit();
				}
			}
		});
		return false;

	} catch (o) {
		return false;
		alert(o.message);
	}
}

function verifyDNS() {
	try {
		$('#vbtn').css('display', 'none');
		document.getElementById('status_').innerHTML = 'Processing please wait...';
		var data = '';
		$.ajax({
			type: 'POST',
			url: '/merchant/website/verifydns',
			data: data,
			success: function (data) {
				if (data == 0) {
					document.getElementById('status_').innerHTML = 'DNS verification failed please try again.';
					timer(30);
					return false;
				} else {
					$('form#vform').submit();
				}
			}
		});
		return false;

	} catch (o) {
		return false;
		alert(o.message);
	}
}
