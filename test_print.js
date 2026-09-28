const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let lines = html.split("\n");
let inPrint = false;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('@media print')) {
        inPrint = true;
    }
    if (inPrint) {
        console.log((i+1) + ": " + lines[i]);
        if (lines[i].includes('}')) {
            // Count opening and closing brackets to know when @media print ends
            // Just printing 20 lines is enough
        }
    }
}
