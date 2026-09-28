const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/rendered_ss.html", "utf8");
let idx = html.indexOf('kelompok-section');
console.log(html.substring(Math.max(0, idx - 200), idx + 2000));
