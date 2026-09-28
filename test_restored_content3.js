const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/BillingSelfService_restored.blade.php", "utf8");
let idx = html.indexOf('btnSubmitPaket');
if (idx !== -1) {
    console.log("FOUND btnSubmitPaket!");
} else {
    console.log("NOT FOUND :(");
}
