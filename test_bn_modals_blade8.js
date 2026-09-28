const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let lines = html.split("\n");
let startLine = 2876;
let endLine = 0;
for (let i = startLine; i < lines.length; i++) {
    if (lines[i].includes('id="ppFnbList"')) {
        endLine = i + 1; // get the next line which is the foreach
        break;
    }
}
for (let i = startLine; i <= endLine; i++) {
    console.log(lines[i]);
}
