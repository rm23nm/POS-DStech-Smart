const fs = require("fs");
let chunk = fs.readFileSync("footer_chunk.html", "utf8");
console.log(chunk.substring(0, 500));
console.log("\n...\n");
console.log(chunk.substring(chunk.length - 500));
