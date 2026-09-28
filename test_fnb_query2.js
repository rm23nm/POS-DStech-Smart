const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/TableOrderController.php", "utf8");
let lines = html.split("\n");
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('$itemmaster =')) {
        console.log("Found at line " + (i+1) + ": " + lines[i]);
    }
}
