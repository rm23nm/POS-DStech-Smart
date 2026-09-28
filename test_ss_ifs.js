const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let lines = html.split("\n");
let ifs = 0;
let endifs = 0;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('@if') && !lines[i].includes('@endif')) {
        let matchIf = (lines[i].match(/@if/g) || []).length;
        let matchEndif = (lines[i].match(/@endif/g) || []).length;
        ifs += matchIf;
        endifs += matchEndif;
    } else if (lines[i].includes('@endif')) {
        let matchEndif = (lines[i].match(/@endif/g) || []).length;
        endifs += matchEndif;
    }
}
console.log("Ifs: " + ifs + ", Endifs: " + endifs);
