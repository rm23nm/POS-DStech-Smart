const fs = require("fs");
let html = fs.readFileSync("BillingSelfService_fixed.blade.php", "utf8");
let lines = html.split("\n");
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('@else')) console.log((i+1) + ": " + lines[i].trim());
    if (lines[i].includes('@elseif')) console.log((i+1) + ": " + lines[i].trim());
}
