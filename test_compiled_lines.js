const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/storage/framework/views/0465ce588c385e6502a695cbb8e3e83143b9a604.php", "utf8");
let lines = html.split("\n");
let ifs = 0, endifs = 0;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('<?php if')) { console.log("IF at line " + (i+1)); ifs++; }
    if (lines[i].includes('<?php endif; ?>')) { console.log("ENDIF at line " + (i+1)); endifs++; }
}
console.log("if: " + ifs + ", endif: " + endifs);
