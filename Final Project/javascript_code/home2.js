var settings = {
    seatCss: 'seat',
    selectedSeatCss: 'selectedSeat',
    selectingSeatCss: 'selectingSeat',
    bookedSeatCss: 'bookedSeat'
};

var length = 0;
var width = 0 ;

function createTable(larg, lung) {
    length = parseInt(lung);
    width = parseInt(larg);
    var table = document.getElementById("table");
    var tr = document.createElement("TR");
    for (var i = 0; i < width + 1; i++) {
        var th = document.createElement("TH");
        if (i === 0){
            var textnode = document.createTextNode(" ");
        }else {
            var textnode = document.createTextNode(String.fromCharCode(("A".charCodeAt(0) + i) - 1));
        }
        th.appendChild(textnode);
        tr.appendChild(th);
    }
    table.appendChild(tr);

    for (var i = 0; i < length; i++) {
        var tr = document.createElement("TR");
        for (var j = 0; j < width + 1; j++) {
            var td = document.createElement("TD");
            if (j === 0)
                var textnode = document.createTextNode(i + 1);
            else {
                td.className = settings.seatCss;
                td.addEventListener("click", seat_reservation);
                td.addEventListener("click", check_button);
                td.id = i+1 + "," + j;
                var textnode = document.createTextNode(String.fromCharCode("A".charCodeAt(0) + j-1) + "" + (i+1));
            }
            td.appendChild(textnode);
            tr.appendChild(td);
        }
        table.appendChild(tr);
    }
    $.ajax({
        type: "POST",
        url: "tablecreation.php",
        dataType : 'json',
        data: {},
        success: function (data) {
            if (data.oranges !== undefined)
                show_orange(data.oranges);
            if (data.reds !== undefined)
                show_red(data.reds);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert("Status: " + textStatus + "Error: " + errorThrown);
        }
    });
}

var logged = false;
var user = undefined;

    function logged_in(user2) {
        setTimeout(function() {
            check_button();
            logged = true;
            user = user2;
            var x = $('.bookedSeat');
            if (x.length > 0) {
                obj = {};
                obj.seats = [];
                for (var i = 0; i < x.length; i++) {
                    obj.user = user; // user
                    obj.seats.push(x[i].id);
                }
                var jsonString = JSON.stringify(obj);
                $.ajax({
                    type: "POST",
                    url: "changeColor.php",
                    dataType: 'json',
                    data: {data: jsonString},
                    success: function (data) {
                        if (data !== undefined && data !== null)
                            show_yellow(data);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        window.location.reload();
                    }
                });
            }
        },200);
    }

function check_button(){
        setTimeout( function () {
            var x = document.getElementsByClassName(settings.selectingSeatCss);
            if (x.length > 0)
                $('#bookButton').attr("disabled", false);
            else
                $('#bookButton').attr("disabled", true);
        }, 200);
}

function reserve_seats() {
    var x = document.getElementsByClassName(settings.selectingSeatCss);
    while (x.length) {
        x[0].className = settings.selectedSeatCss;
    }
}

function statistics() {
    setTimeout(function() {
        var x = (document.getElementsByClassName(settings.seatCss));
        var y = (document.getElementsByClassName(settings.selectedSeatCss));
        var z = (document.getElementsByClassName(settings.bookedSeatCss));
        document.getElementById("statistics").innerHTML = "<h4>Total number of seats: "+  (length * width) + "<br>Total number of free seats:    " + x.length + "<br>Total number of purchased seats:    " + y.length + "<br>Total number of reserved seats:    " + z.length + "</h4>" ;
    }, 200);
    }

function seat_reservation() {
    if (this.className === settings.selectedSeatCss && logged === true) { //red
        alert('Can not choose this seat, it is already reserved');
    } else if (this.className === settings.selectingSeatCss) {
        this.className = settings.seatCss; //yellow -> green

        obj = {};
        obj.user = user;
        obj.seat = this.id;
        jsonString = JSON.stringify(obj);
        $.ajax({
            type: "POST",
            url: "deletereservation.php",
            data: {data : jsonString},
            success: function (data) {
                if (parseInt(data) === 0){
                    logged = false;
                    alert("User not logged anymore");
                    window.location.reload();
                }
                alert('Seat deselected');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Status: " + textStatus + "Error: " + errorThrown);
            }
        });

    } else if (logged === true && this.className !== settings.bookedSeatCss) {
        this.className = settings.selectingSeatCss; //green -> yellow
        obj2 = {};
        obj2.user = user;
        var id = this.id;
        obj2.seat = id;
        var jsonString = JSON.stringify(obj2);
        var data2;
        $.ajax({
            type: "POST",
            url: "check_reservation.php", //check if not purchased
            data: {data: jsonString},
            success: function (data) {
                data2 = data;
                if (parseInt(data) === 3){
                    logged = false;
                    alert("User not logged anymore");
                    window.location.reload();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Status: " + textStatus + "Error: " + errorThrown);
            }
        }).done (function () {
            if (parseInt(data2) === 1) {
                $.ajax({
                    type: "POST",
                    url: "reserve.php",
                    data: {data: jsonString},
                    success: function (data) {
                        if (parseInt(data) === 0){
                            logged = false;
                            alert("User not logged anymore");
                            window.location.reload();
                        }
                        alert('Seat selected');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("Status: " + textStatus + "Error: " + errorThrown);
                    }
                });
            }else if (parseInt(data2) === 0){
                var x = document.getElementById(id);
                x.className = settings.selectedSeatCss;
                alert("Seat already purchased in the meantime, try again");
            }
        });
    }
        else if (logged === true && this.className === settings.bookedSeatCss){
        this.className = settings.selectingSeatCss;
        obj2 = {};
        obj2.user = user;
        obj2.seat = this.id;
        var jsonString = JSON.stringify(obj2);
        $.ajax({
            type: "POST",
            url: "changeReservation.php",
            data: {data: jsonString},
            success: function (data) {
                alert('Seat selected');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Status: " + textStatus + "Error: " + errorThrown);
            }
        });
    }
}


function show_orange(seats){
    for (var i = 0; i < seats.length; i++){
        var x = document.getElementById(seats[i]);
        x.className = settings.bookedSeatCss;
    }
}

function show_yellow(seats){
    for (var i = 0; i < seats.length; i++){
        var x = document.getElementById(seats[i]);
        x.className = settings.selectingSeatCss;
    }
}

function show_red(seats){
    for (var i = 0; i < seats.length; i++){
        var x = document.getElementById(seats[i]);
        x.className = settings.selectedSeatCss;
    }
}
function event_button() {
    document.getElementById("bookButton").onclick = function () {
        //richiamo booking.php che memorizza i dati del vettore
        obj = {};
        obj.seats = [];
        var arr = [];
        var x = document.getElementsByClassName(settings.selectingSeatCss);

        for (var y = 0; y < x.length; y++) {
            obj.user = user; // user
            obj.seats.push(x[y].id);
        }
        var jsonString = JSON.stringify(obj);
        var data2;

        $.ajax({
            type: "POST",
            url: "booking1.php",
            data: {data: jsonString},
            success: function (data) {
                data2 = data;
                if (parseInt(data) === 2){
                    logged = false;
                    alert("User not logged anymore");
                    window.location.reload();
                }
                        }, error: function (jqXHR, textStatus, errorThrown) {
                            alert("Error while purchasing the seats " + textStatus + " " + errorThrown);
                        }
                    }).done(function (data2){
                        if (parseInt(data2) === 0)
                            update();
                        else if (parseInt(data2) === 1)
                            delecte();
                 });
    }
}

function update() {
    obj = {};
    obj.seats = [];
    var arr = [];
    var x = document.getElementsByClassName(settings.selectingSeatCss);

    for (var y = 0; y < x.length; y++) {
        obj.user = user; // user
        obj.seats.push(x[y].id);
    }
    var jsonString = JSON.stringify(obj);

    $.ajax({
        type: "POST",
        url: "booking.php",
        data: {data: jsonString},
        success: function (data) {
            x = document.getElementsByClassName(settings.selectingSeatCss);
            while (x.length) {
                x[0].className = settings.selectedSeatCss;
            }
        }
    });
    alert("Seats successfully purchased");
    window.location.reload();
}

function delecte(){
    obj.user = user; // user

        var jsonString = JSON.stringify(obj);

        $.ajax({
            type: "POST",
            url: "deleteallreservation.php",
            data: {data: jsonString},
            success: function (data) {
            }, error: function (jqXHR, textStatus, errorThrown) {
                alert("Error " + textStatus + " " + errorThrown);
            }
        });

    window.location.reload();
    alert("One of the selected seats was not available in the meantime, try again");
}