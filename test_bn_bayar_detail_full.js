const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let lines = html.split("\n");
let inFunction = false;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('function onBayarFromDetail()')) {
        inFunction = true;
    }
    if (inFunction) {
        console.log((i+1) + ": " + lines[i]);
        if (lines[i].includes('function ')) {
            if (lines[i] !== lines[i].match(/function onBayarFromDetail()/)) {
               if (!lines[i].includes('function onBayarFromDetail()')) break;
            }
        }
    }
}
