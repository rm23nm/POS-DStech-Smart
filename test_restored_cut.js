const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/BillingSelfService_restored.blade.php", "utf8");
let lines = html.split("\n");
for (let i = 328; i <= 340; i++) {
    console.log(i + ": " + lines[i-1].trim());
}
