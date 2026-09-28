const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BengkelPoS.blade.php", "utf8");

let before = html;
html = html.replace(/'Active'\s*:\s*'Y',/g, "'Active' : 'Y',\n\t\t\t\t'KategoriPOS' : 'BENGKEL',");

if (before !== html) {
    fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BengkelPoS.blade.php", html);
    console.log("Fixed BengkelPoS!");
} else {
    console.log("Not found in BengkelPoS!");
}
