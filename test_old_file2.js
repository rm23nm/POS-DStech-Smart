const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/BillingSelfService.blade.php", "utf8");
let lines = html.split('\n');
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('btnSubmitPaket')) {
        console.log("Line " + (i+1) + ": " + lines[i].trim());
    }
}
