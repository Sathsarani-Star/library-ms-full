function searchTable(searchBoxId, tableId) {
    var searchBox = document.getElementById(searchBoxId);
    var searchText = searchBox.value.toUpperCase();
    var bookTable = document.getElementById(tableId);
    var tableRows = bookTable.getElementsByTagName("tr");

    for (var rowNumber = 1; rowNumber < tableRows.length; rowNumber++) {
        var rowText = tableRows[rowNumber].textContent || tableRows[rowNumber].innerText;

        if (rowText.toUpperCase().indexOf(searchText) > -1) {
            tableRows[rowNumber].style.display = "";
        } else {
            tableRows[rowNumber].style.display = "none";
        }
    }
}

function openPopup(popupId) {
    document.getElementById(popupId).style.display = "flex";
}

function closePopup(popupId) {
    document.getElementById(popupId).style.display = "none";
}
