const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/TicketingPoS.blade.php", "utf8");
let matches = html.match(/_DiskonGrupCustomer|DiskonPersen|diskon/gi);
console.log(matches ? matches.length : 0);
