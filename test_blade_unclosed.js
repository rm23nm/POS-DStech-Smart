const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
let lines = html.split("\n");
let unclosedIfs = [];
let unclosedForeachs = [];
for (let i = 0; i < lines.length; i++) {
    let ifMatches = (lines[i].match(/@if/g) || []).length;
    let endifMatches = (lines[i].match(/@endif/g) || []).length;
    for(let k=0; k<ifMatches; k++) unclosedIfs.push(i+1);
    for(let k=0; k<endifMatches; k++) unclosedIfs.pop();
    
    let foreachMatches = (lines[i].match(/@foreach/g) || []).length;
    let endforeachMatches = (lines[i].match(/@endforeach/g) || []).length;
    for(let k=0; k<foreachMatches; k++) unclosedForeachs.push(i+1);
    for(let k=0; k<endforeachMatches; k++) unclosedForeachs.pop();
}
console.log("Unclosed @if on lines:", unclosedIfs);
console.log("Unclosed @foreach on lines:", unclosedForeachs);
