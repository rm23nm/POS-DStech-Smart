const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/NormalPoS.blade.php", "utf8");
let matches = html.match(/<select[^>]*name="KodeJenisItem"[^>]*>.*?<\/select>/gis);
if (matches) {
    console.log(matches[0]);
} else {
    console.log("No KodeJenisItem select found.");
}
