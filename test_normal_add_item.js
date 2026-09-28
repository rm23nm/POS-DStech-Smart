const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/NormalPoS.blade.php", "utf8");
let lines = html.split("\n");
let start = false;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('bindGrid') || lines[i].includes('push')) {
        // Just find where items are added to the grid
    }
}
let match = html.match(/push\(\s*\{[^\}]+\}/g);
if (match) {
    console.log(match[0]);
}
