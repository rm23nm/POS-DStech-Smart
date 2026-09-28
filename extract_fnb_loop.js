const fs = require("fs");
let bn = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let start = bn.indexOf('<div class="fnb-list-container" id="ppFnbMenuList">');
if (start === -1) {
    start = bn.indexOf('id="ppFnbList"');
}
let end = bn.indexOf('<!-- Row: Service Type', start);
console.log(bn.substring(start, end));
