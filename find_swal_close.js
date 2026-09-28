const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let lines = html.split("\n");
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('swal.close(')) {
        console.log("swal.close at line " + (i+1));
    }
    if (lines[i].includes('Swal.close(')) {
        console.log("Swal.close at line " + (i+1));
    }
}
