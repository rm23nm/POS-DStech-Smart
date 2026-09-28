const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BengkelPoS.blade.php", "utf8");
let lines = html.split("\n");
for (let i = 0; i < 50; i++) {
    console.log(lines[i]);
}
