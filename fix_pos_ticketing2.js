const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/TicketingPoS.blade.php", "utf8");

let before = html;
html = html.replace(/Active:\s*'Y',/g, "Active: 'Y', \n\t\t\t\tKategoriPOS: 'TIKET',");

if (before !== html) {
    fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/TicketingPoS.blade.php", html);
    console.log("Fixed TicketingPoS!");
} else {
    console.log("Not found in TicketingPoS!");
}
