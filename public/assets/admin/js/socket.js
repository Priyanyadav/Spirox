const protocol = (document.location.protocol === 'https:') ? 'wss' : 'ws';
const hostname = document.location.hostname;
const port = (document.location.port) ? `:3000` : '';
const socketUrl = `${protocol}://${hostname}${port}`;

// Now use the constructed URL for the socket connection
const socket = io(socketUrl);
socket.on('inquiryalert', (msg) => {
    activeSocketNotify(msg);
});

socket.on('activecustomer', (msg) => {
    console.log(msg);
});

function activeSocketNotify(data) {
    $("._socket-notify .contentArea h2").text(data.title);
    $("._socket-notify .contentArea p").text(data.description);
    if (data.button) {
        $("._socket-notify .contentArea a").attr('href',data.button);
        $("._socket-notify .contentArea a").show();
    }
    else
    {
        $("._socket-notify .contentArea a").hide();
    }
    $("._socket-notify").addClass("show");
}

$(document).on("click", "#_socketNotifyClose", function (e) {
    e.preventDefault();
    $("._socket-notify").removeClass("show");
});