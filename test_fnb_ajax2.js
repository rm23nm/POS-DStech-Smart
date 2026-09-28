const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/FnBPoS.blade.php", "utf8");
let lines = html.split("\n");
let inFunction = false;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('url: "{{route(\'itemmaster-ViewJson\')}}"')) {
        inFunction = true;
    }
    if (inFunction) {
        console.log((i+1) + ": " + lines[i]);
        if (lines[i].includes('}')) {
            // we want to see the whole data object
            if (lines[i].includes('success:')) break;
        }
    }
}
