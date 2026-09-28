const fs = require("fs");
let js = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/broken_bn_script.js", "utf8");
let lines = js.split('\n');
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('doc.write')) {
        console.log((i+1) + ": " + lines[i]);
        if (lines[i+1]) console.log((i+2) + ": " + lines[i+1]);
    }
}
