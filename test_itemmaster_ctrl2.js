const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/ItemMasterController.php", "utf8");
let lines = html.split("\n");
let inFunction = false;
let printed = 0;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('function ViewJson(')) {
        inFunction = true;
    }
    if (inFunction) {
        console.log((i+1) + ": " + lines[i]);
        printed++;
        if (printed > 100) break;
    }
}
