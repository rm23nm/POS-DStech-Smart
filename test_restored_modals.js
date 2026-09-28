const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/BillingSelfService_restored.blade.php", "utf8");
let idx = html.indexOf('<!-- ===== MODALS ===== -->');
if (idx !== -1) {
    console.log("FOUND MODALS!");
    console.log(html.substring(idx, idx + 500));
} else {
    console.log("Not found :(");
}
