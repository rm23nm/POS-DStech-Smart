const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/rendered_ss.html", "utf8");
let start = html.indexOf('id="ppFnbList"');
let lastItem = html.lastIndexOf('<div class="fnb-item"', html.indexOf('jfCartItems'));
let end = html.indexOf('jfCartItems', start);
console.log(html.substring(lastItem + 200, end));
