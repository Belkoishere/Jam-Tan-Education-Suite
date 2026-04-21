var Messages = window.Messages;

if (Messages["Success"] != null && Messages["Success"] != undefined) {
    var SuccessAlert = document.getElementById("alert_success");
    var SuccessAlertText = document.getElementById("alert_success_text");

    SuccessAlert.innerHTML = Messages["Success"];
    SuccessAlert.style.display = "Block";

    SuccessAlert.classList.add("animate");

    setTimeout(() => {
        SuccessAlert.style.display = "None";
    }, 5000);
}

if (Messages["Warning"] != null && Messages["Warning"] != undefined) {
    var WarningAlert = document.getElementById("alert_warning");
    var WarningAlertText = document.getElementById("alert_warning_text");

    WarningAlert.innerHTML = Messages["Warning"];
    WarningAlert.style.display = "Block";

    WarningAlert.classList.add("animate");

    setTimeout(() => {
        WarningAlert.style.display = "None";
    }, 5000);
}