const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let lines = html.split("\n");
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('FNB') || lines[i].includes('fnb')) {
        console.log("Found at line " + (i+1) + ": " + lines[i].substring(0, 80));
    }
}
