const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/TableOrderController.php", "utf8");
let lines = html.split("\n");
for (let i = 405; i < 415; i++) {
    console.log((i+1) + ": " + lines[i]);
}
