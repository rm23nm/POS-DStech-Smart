const fs = require("fs");
let html = fs.readFileSync("BillingSelfService_fixed.blade.php", "utf8");
let foreachCount = (html.match(/@foreach/g) || []).length;
let endforeachCount = (html.match(/@endforeach/g) || []).length;
console.log("foreach: " + foreachCount + ", endforeach: " + endforeachCount);
