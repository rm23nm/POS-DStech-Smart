const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let endifCount = (html.match(/@endif/g) || []).length;
let ifCount = (html.match(/@if/g) || []).length;
let foreachCount = (html.match(/@foreach/g) || []).length;
let endforeachCount = (html.match(/@endforeach/g) || []).length;
console.log("Original if: " + ifCount + ", endif: " + endifCount);
console.log("Original foreach: " + foreachCount + ", endforeach: " + endforeachCount);
