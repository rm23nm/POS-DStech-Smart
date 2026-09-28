const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/rendered_ss.html", "utf8");
let idx = html.indexOf('<!-- Left Panel : Titik Lampu Grid -->');
let endIdx = html.indexOf('<!-- Right Panel : Detail Meja -->', idx);
console.log(html.substring(idx, endIdx));
