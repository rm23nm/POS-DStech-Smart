const fs = require("fs");
let html = fs.readFileSync("BillingSelfService_fixed.blade.php", "utf8");
let matches = html.match(/@(isset|endisset|empty|endempty|auth|endauth|guest|endguest|forelse|endforelse)/g) || [];
console.log(matches.reduce((acc, curr) => { acc[curr] = (acc[curr] || 0) + 1; return acc; }, {}));
