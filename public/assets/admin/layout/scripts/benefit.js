function toggleFullDescription(id) {
    var description = id + '-detail';
    var button = id + '-button';

    var showDiv = document.getElementById(description);
    var buttonText = document.getElementById(button);

    if (showDiv.style.display === "none") {
        setTimeout(function () {
            showDiv.style.display = "block";
            buttonText.innerHTML = "Close";
        }, 500);

    } else {
        setTimeout(function () {
            showDiv.style.display = "none";
            buttonText.innerHTML = "Learn more";
        }, 500);
    }
}

function setLink(type) {
    if (type == 'growth') {
        document.getElementById("package_link").href = "https://go.swipez.in/invpkg2";

    } else {
        document.getElementById("package_link").href = "/billing-software-pricing";
    }
}

function setBenefit(id) {
    document.getElementById("company-name").innerHTML = document.getElementById("title_" + id).innerHTML;
    document.getElementById("benefit_id").value = id;
}

function showPaidModal() {
    var showModal = document.getElementById("basic");
    showModal.style.display = "block";
}

function showLoader() {
    $('#apply').modal('hide');
    $('#loader').show();
}