const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/BillingSelfService_restored.blade.php", "utf8");
let idx = html.indexOf('pp-row');
console.log(html.substring(Math.max(0, idx - 500), idx + 1500));
