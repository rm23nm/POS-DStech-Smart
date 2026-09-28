const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/BillingSelfService_restored.blade.php", "utf8");
let idx = html.indexOf('<div class="pp-row"');
console.log(html.substring(idx - 100, idx + 1000));
