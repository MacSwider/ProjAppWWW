function getTheDate() {
    Todays = new Date();
    TheDate = "" + (Todays.getMonth() + 1) + "/" + Todays.getDate() + "/" + (Todays.getYear() - 100);
    document.getElementById("data").innerHTML = TheDate;
}

var timerID = null;
var timerRunning = false;

function stopclock() {
    if (timerRunning) {
        clearTimeout(timerID);
        timerRunning = false;
    }
}

//Funkcja startuje odliczanie czasu. Najpierw zatrzymuje je, potem ustawia biezaca date,
//na koncu wywoluje funkcje showtime(), ktora wyswietla aktualny czas
function startclock() {
    stopclock();
    getTheDate();
    showtime();
}

//showtime
//Funkcja wyswietla aktualny czas w formacie hh:mm:ss AM/PM
//i ustawia timeout na 1 sekunde, by ponownie wywolac sama siebie

function showtime() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();
    var timeValue = "" + ((hours > 12) ? hours - 12 : hours);
    timeValue += ((hours == 0) ? 12 : "");
    timeValue += ((minutes < 10) ? ":0" : ":") + minutes;
    timeValue += ((seconds < 10) ? ":0" : ":") + seconds;
    timeValue += (hours >= 12) ? " PM" : " AM";
    document.getElementById("zegarek").innerHTML = timeValue;
    timerID = setTimeout("showtime()", 1000);
    timerRunning = true;
}
