const fs = require("fs");
let html = fs.readFileSync("BillingSelfService_fixed.blade.php", "utf8");
let endifCount = (html.match(/@endif/g) || []).length;
let ifCount = (html.match(/@if/g) || []).length;
console.log("if: " + ifCount + ", endif: " + endifCount);
