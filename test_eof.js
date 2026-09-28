const fs = require("fs");
let html = fs.readFileSync("BillingSelfService_fixed.blade.php", "utf8");
let lines = html.split("\n");
for (let i = lines.length - 20; i < lines.length; i++) {
    console.log(i + ": " + lines[i]);
}
