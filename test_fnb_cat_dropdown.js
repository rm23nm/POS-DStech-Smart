const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/FnBPoS.blade.php", "utf8");
let matches = html.match(/<select[^>]*name="KodeJenisItem"[^>]*>.*?<\/select>/gis);
if (matches) {
    console.log("KodeJenisItem selects found:", matches.length);
} else {
    console.log("No KodeJenisItem select found.");
}
let matches2 = html.match(/<select[^>]*id="KodeJenis"[^>]*>.*?<\/select>/gis);
if (matches2) {
    console.log("KodeJenis selects found:", matches2.length);
} else {
    console.log("No KodeJenis select found.");
}
