const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let lines = html.split("\n");
let countIf = 0;
let countForeach = 0;
for (let i = 0; i < lines.length; i++) {
    let ifMatches = (lines[i].match(/@if/g) || []).length;
    let endifMatches = (lines[i].match(/@endif/g) || []).length;
    countIf += ifMatches - endifMatches;
    
    let foreachMatches = (lines[i].match(/@foreach/g) || []).length;
    let endforeachMatches = (lines[i].match(/@endforeach/g) || []).length;
    countForeach += foreachMatches - endforeachMatches;
    
    if (countIf !== 0 || countForeach !== 0) {
        console.log("Line " + (i+1) + " (if:" + countIf + ", foreach:" + countForeach + "): " + lines[i].trim());
    }
}
