const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/rendered_ss.html", "utf8");
let idx = html.indexOf('id="ppFnbList"');
let endIdx = html.indexOf('jfCartItems', idx);
console.log(html.substring(idx - 100, idx + 1000));
console.log("\n\n======== JUMP ========\n\n");
console.log(html.substring(endIdx - 500, endIdx + 500));
