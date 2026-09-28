const fs = require("fs");
let lines = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/script_block_5.js", "utf8").split('\n');
for (let i = 910; i < 925; i++) {
    if (lines[i]) console.log(`${i+1}: ${lines[i]}`);
}
