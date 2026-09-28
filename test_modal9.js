const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let lines = html.split('\n');
for (let i = 0; i < lines.length; i++) {
    if (lines[i].toLowerCase().includes('belum ada item')) {
        console.log("Found at line " + (i+1));
        console.log(lines.slice(i-5, i+15).join('\n'));
    }
}
