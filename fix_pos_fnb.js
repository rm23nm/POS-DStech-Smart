const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/FnBPoS.blade.php", "utf8");

let before = html;
html = html.replace(/'Active' \s*: 'Y',/g, "'Active' : 'Y',\n\t\t\t\t'KategoriPOS' : 'FNB',");

if (before !== html) {
    fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/FnBPoS.blade.php", html);
    console.log("Fixed FnBPoS!");
} else {
    console.log("Not found in FnBPoS!");
}
