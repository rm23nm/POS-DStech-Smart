const fs = require("fs");
let html = fs.readFileSync("BillingSelfService_fixed.blade.php", "utf8");
let lines = html.split("\n");
for (let i = 330; i <= 345; i++) {
    console.log(i + ": " + lines[i-1].trim());
}
