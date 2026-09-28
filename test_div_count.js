const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let lines = html.split("\n");
let startStr = '<!-- Main Layout -->';
let startIdx = 0;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('Main Layout')) startIdx = i;
}
let leftPanelCount = 0;
for (let i = startIdx; i <= 335; i++) {
    let l = lines[i];
    leftPanelCount += (l.match(/<div/g) || []).length;
    leftPanelCount -= (l.match(/<\/div>/g) || []).length;
    console.log(i + ": " + l.trim() + " (" + leftPanelCount + ")");
}
