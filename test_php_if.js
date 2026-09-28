const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/storage/framework/views/0465ce588c385e6502a695cbb8e3e83143b9a604.php", "utf8");
let ifs = (html.match(/<\?php if/g) || []).length;
let endifs = (html.match(/<\?php endif;/g) || []).length;
console.log("if: " + ifs + ", endif: " + endifs);
