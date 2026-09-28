const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/NormalPoS.blade.php", "utf8");
let lines = html.split("\n");
let inFunction = false;
for (let i = 2300; i < 2500; i++) {
    if (lines[i].includes('_DiskonGrupCustomer > 0')) {
        console.log("Found at line " + (i+1) + ": " + lines[i]);
    }
}
