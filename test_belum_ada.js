const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let idx = html.indexOf('Belum ada item');
if (idx === -1) {
    console.log("Not found in HTML. Let's check JS.");
    let lines = html.split('\n');
    for (let i = 0; i < lines.length; i++) {
        if (lines[i].includes('Belum ada item')) {
            console.log(lines[i].trim());
        }
    }
}
