const fs = require("fs");
let stats = fs.statSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BengkelPoS.blade.php");
console.log(stats.size);
