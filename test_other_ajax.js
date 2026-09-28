const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/ApotekPoS.blade.php", "utf8");
let html2 = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/NormalPoS.blade.php", "utf8");
console.log("ApotekPoS uses itemmaster-ViewJson:", html.includes("itemmaster-ViewJson") || html.includes("GetStockPerWhs"));
console.log("NormalPoS uses itemmaster-ViewJson:", html2.includes("itemmaster-ViewJson") || html2.includes("GetStockPerWhs"));
