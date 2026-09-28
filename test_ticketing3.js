const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/TicketingPoS.blade.php", "utf8");
let lines = html.split("\n");
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('route(\'itemmaster-GetStockPerWhs\')')) {
        for(let j = i; j < i+10; j++) console.log((j+1) + ": " + lines[j]);
    }
}
