const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BengkelPoS.blade.php", "utf8");
let lines = html.split("\n");
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('$.ajax(') || lines[i].includes('jQuery.ajax(')) {
        console.log("Found at line " + (i+1));
        for(let j=i; j<i+5; j++) console.log((j+1) + ": " + lines[j]);
    }
}
