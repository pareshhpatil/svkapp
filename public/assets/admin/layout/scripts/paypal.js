paypal.Buttons({
    // Specify the style of the button
    style: {
        color: 'blue',
        layout: 'horizontal',
        shape: 'rect',
        size: 'responsive',
    },
    // Wait for the PayPal button to be clicked
    
    createOrder: function (data, actions) {
        // Replace your create order server url below
        return fetch(request_url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify($('#'+form_id).serializeArray())
        })
                .then(res => res.json())
                .then(data => data.id)

    },
    onApprove: function (data, actions) {
        return actions.order.capture().then(function (details) {
            document.getElementById('loader').style.display = 'block';
            window.location.href = response_url + data.orderID + '/' + paypal_pg_id;
        });
    },
    onError: function (err) {
        console.log(err);
        document.getElementById('error').style.display = 'block';
        document.getElementById('error').innerHTML = 'You have some error. Please check below.';
    }
}).render('#paypal-button-container');