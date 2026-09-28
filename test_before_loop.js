const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let idx = html.indexOf('@foreach($itemmaster as $item)');
console.log(html.substring(Math.max(0, idx - 1000), idx));
