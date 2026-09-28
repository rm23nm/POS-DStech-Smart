const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/BillingSelfService.blade.php", "utf8");
let lines = html.split('\n');
let count = 0;
let betweenLines = [];
let capture = false;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('onclick="{{ $isHabis ? "swal')) {
        count++;
        if (count === 1) capture = true;
        if (count === 2) {
            capture = false;
            break;
        }
    }
    if (capture && count === 1) {
        betweenLines.push(lines[i]);
    }
}
fs.writeFileSync('missing_chunk.txt', betweenLines.join('\n'));
console.log("Missing chunk has " + betweenLines.length + " lines.");
