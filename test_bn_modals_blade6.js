const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let lines = html.split("\n");
let inModal = false;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('<!-- ===== MODAL PILIH PAKET ===== -->')) {
        inModal = true;
    }
    if (inModal) {
        console.log(lines[i]);
        if (lines[i].includes('id="ppFnbList"')) {
            break;
        }
    }
}
