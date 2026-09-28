const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let lines = html.split("\n");
let startLine = 0;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('<div class="fnb-selection-area"')) {
        startLine = i;
        break;
    }
}
for (let i = startLine; i <= startLine + 30; i++) {
    console.log(lines[i]);
}
