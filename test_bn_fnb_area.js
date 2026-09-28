const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let idx = html.indexOf('fnb-selection-area');
if (idx !== -1) {
    console.log("FOUND fnb-selection-area!");
    console.log(html.substring(Math.max(0, idx - 100), idx + 200));
} else {
    console.log("Not found :(");
}
