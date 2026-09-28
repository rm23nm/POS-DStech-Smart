const fs = require("fs");
let html = fs.readFileSync("BillingSelfService_fixed.blade.php", "utf8");
let phpCount = (html.match(/@php/g) || []).length;
let endphpCount = (html.match(/@endphp/g) || []).length;
console.log("php: " + phpCount + ", endphp: " + endphpCount);
