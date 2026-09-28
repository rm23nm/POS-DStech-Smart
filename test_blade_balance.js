const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let countIf = (html.match(/@if/g) || []).length;
let countEndif = (html.match(/@endif/g) || []).length;
console.log("@if count: " + countIf);
console.log("@endif count: " + countEndif);

let countForeach = (html.match(/@foreach/g) || []).length;
let countEndforeach = (html.match(/@endforeach/g) || []).length;
console.log("@foreach count: " + countForeach);
console.log("@endforeach count: " + countEndforeach);
