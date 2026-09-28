const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/rendered_ss.html", "utf8");
let lines = html.split("\n");
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('btnSubmitPaket')) {
        console.log(i + ": " + lines[i].trim());
    }
}
