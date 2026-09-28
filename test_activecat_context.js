const fs = require("fs");
const file = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
const idx = file.indexOf('let activeCat = "FNB";');
console.log(file.substring(Math.max(0, idx - 200), idx + 200));
