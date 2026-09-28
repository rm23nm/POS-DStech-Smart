const fs = require("fs");
let html = fs.readFileSync("BillingSelfService_fixed.blade.php", "utf8");
let lines = html.split("\n");
for (let i = 478; i <= 490; i++) {
    console.log((i+1) + ": " + lines[i].trim());
}
