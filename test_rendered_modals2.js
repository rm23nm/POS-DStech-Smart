const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/rendered_ss.html", "utf8");
let idx = html.indexOf('<!-- ===== MODALS ===== -->');
let endIdx = html.indexOf('id="ppFnbList"', idx);
console.log(html.substring(idx, endIdx + 15));
