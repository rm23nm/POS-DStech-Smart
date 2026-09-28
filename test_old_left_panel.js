const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/BillingSelfService.blade.php", "utf8");
let idx = html.indexOf('<div class="left-panel">');
let endIdx = html.indexOf('<!-- Right Panel : Detail Meja -->', idx);
if (endIdx === -1) endIdx = html.indexOf('<!-- ===== MODALS ===== -->', idx);
console.log(html.substring(idx, endIdx));
