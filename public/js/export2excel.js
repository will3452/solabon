
function exportToExcel() {
    // var elt = document.getElementById('tbl_exporttable_to_xls');
    // var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
    // console.log(wb.Sheets.sheet1);
    // return dl ?
    //     XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
    //     XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
    $("#tbl_exporttable_to_xls").table2excel({
        // exclude CSS class
        exclude: ".noExl",
        name: "Worksheet Name",
        filename: "output", //do not include extension
        fileext: ".xls" // file extension
    }); 
}