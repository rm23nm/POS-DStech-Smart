const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let idx = html.indexOf('@foreach($itemmaster as $item)');
let endIdx = html.indexOf('@endforeach', idx);
console.log(html.substring(endIdx, endIdx + 2000));
