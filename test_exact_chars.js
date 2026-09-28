const fs = require("fs");
let c = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let lines = c.split("\n");
for (let i = 332; i <= 337; i++) {
    console.log(i + ": " + JSON.stringify(lines[i-1]));
}
