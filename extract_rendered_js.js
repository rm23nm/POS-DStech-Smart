const fs = require("fs");
const file = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/rendered_ss.html", "utf8");
const scriptRegex = /<script\b[^>]*>([\s\S]*?)<\/script>/gm;
let match;
let count = 0;
while ((match = scriptRegex.exec(file)) !== null) {
    count++;
    let js = match[1];
    fs.writeFileSync(`script_block_${count}.js`, js);
}
console.log(`Extracted ${count} scripts.`);
