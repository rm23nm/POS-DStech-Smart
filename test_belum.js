const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let lower = html.toLowerCase();
let idx = lower.indexOf('belum ada');
if (idx !== -1) {
    console.log(html.substring(idx - 50, idx + 100));
} else {
    console.log("NOT FOUND AT ALL!");
}
