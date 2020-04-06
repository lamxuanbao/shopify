jQuery(document).ready(function () {
    var url = 'https://3b072a85.ngrok.io/api/message';
    var domain = window.location.host;
    var pathArray = window.location.pathname.split('/');
    if (pathArray[pathArray.length - 2] == 'products') {
        var handle = pathArray[pathArray.length - 1];
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                'domain': domain,
                'handle': handle
            },
            success: function (result) {
                const data = result.data;
                data.forEach(element => $("<div style='padding: 10px;background-color: blue;color: white;margin-bottom: 10px;'>" + element.message + "</div>").insertBefore(".shopify-payment-button__button--unbranded"));
            }
        })
    }
});
