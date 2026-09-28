const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/ApotekPoS.blade.php", "utf8");
let lines = html.split("\n");
let inFunction = false;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('function SetDataRow(')) {
        inFunction = true;
    }
    if (inFunction) {
        console.log((i+1) + ": " + lines[i]);
        if (lines[i].includes('dataGridInstance.getDataSource().store().insert(')) {
            for (let j = i+1; j < i+10; j++) console.log((j+1) + ": " + lines[j]);
            break;
        }
    }
}
