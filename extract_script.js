const fs = require("fs");
const file = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");

const scriptRegex = /<script\b[^>]*>([\s\S]*?)<\/script>/gm;
let match;
let count = 0;
while ((match = scriptRegex.exec(file)) !== null) {
    count++;
    if (count === 6) {
        fs.writeFileSync("script_block_6.js", match[1]);
        console.log("Extracted script 6.");
    }
}
