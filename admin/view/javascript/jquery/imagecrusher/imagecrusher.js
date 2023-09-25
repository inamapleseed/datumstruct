function showValue(newValue, silderValueToUpdate) {
    var id = silderValueToUpdate;
    document.getElementById(id).innerHTML=newValue;
}

function deselectOn() {
    document.getElementById("image_crusher_image_optimise_on").checked = false;
}

function deselectOff() {
    document.getElementById("image-optimise-off").checked = false;
}

