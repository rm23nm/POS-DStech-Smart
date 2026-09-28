const fs = require("fs");
let html = fs.readFileSync("BillingSelfService_fixed.blade.php", "utf8");
let lines = html.split("\n");
let start = 0;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('@foreach($itemmaster as $item)')) {
        start = i;
        break;
    }
}
for (let i = start; i <= start + 20; i++) {
    console.log((i+1) + ": " + lines[i].trim());
}
