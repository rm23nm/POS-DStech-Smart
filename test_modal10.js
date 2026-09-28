const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let idx = html.indexOf('id="modalPilihPaket"');
let modalHtml = html.substring(idx, html.indexOf('<!-- END MODAL PILIH PAKET -->', idx));
if(modalHtml.length < 10) modalHtml = html.substring(idx, idx + 5000);
let lines = modalHtml.split('\n');
let btnLines = [];
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('<button') || lines[i].includes('class="pp-modal-footer"')) {
        btnLines.push((i+1) + ": " + lines[i].trim());
    }
}
console.log(btnLines.join('\n'));
