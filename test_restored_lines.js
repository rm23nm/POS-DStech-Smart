const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/BillingSelfService_restored.blade.php", "utf8");
let lines = html.split("\n");
for (let i = 330; i <= 335; i++) {
    console.log(lines[i-1].trim());
}
