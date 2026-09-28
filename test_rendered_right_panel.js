const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/rendered_ss.html", "utf8");
let idx = html.lastIndexOf('</div>', html.indexOf('id="modalPilihPaket"'));
console.log(html.substring(Math.max(0, idx - 1000), idx + 500));
