const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/rendered_ss.html", "utf8");
let idx = html.indexOf('<!-- ===== MODALS ===== -->');
let startIdx = html.lastIndexOf('</div>', idx - 100);
console.log(html.substring(Math.max(0, startIdx - 1000), idx));
