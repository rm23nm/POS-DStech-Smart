const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let idx = html.indexOf("@media print");
if (idx !== -1) {
    let sub = html.substring(idx - 100, idx + 500);
    console.log(sub);
} else {
    console.log("No @media print");
}
