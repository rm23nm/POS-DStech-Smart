const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let lines = html.split('\n');
let insideModal = false;
let modalLines = [];
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('modalPilihPaket')) insideModal = true;
    if (insideModal) {
        modalLines.push(lines[i]);
        if (lines[i].includes('</form>')) break; 
    }
}
console.log(modalLines.slice(-30).join('\n'));
