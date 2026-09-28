const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let lines = html.split("\n");
let inFunction = false;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('function onCheckOut()')) {
        inFunction = true;
    }
    if (inFunction) {
        console.log((i+1) + ": " + lines[i]);
        if (lines[i].includes('function ')) {
            if (lines[i] !== lines[i].match(/function onCheckOut()/)) {
               // maybe next function
               if (!lines[i].includes('function onCheckOut()')) break;
            }
        }
    }
}
