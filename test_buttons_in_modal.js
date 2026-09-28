const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let idx = html.indexOf('id="modalPilihPaket"');
let modalHtml = html.substring(idx, html.indexOf('id="modalJualFnb"', idx));
if(modalHtml.length < 100) modalHtml = html.substring(idx, idx + 5000);
let count = 0;
let lines = modalHtml.split('\n');
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('<button')) {
        console.log(lines[i].trim());
    }
}
