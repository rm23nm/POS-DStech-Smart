const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Models/ItemMaster.php", "utf8");
let lines = html.split("\n");
for (let i = 45; i < 95; i++) {
    console.log((i+1) + ": " + lines[i]);
}
