const fs = require("fs");
let chunk = fs.readFileSync("restored_chunk_ss.html", "utf8");
console.log((chunk.match(/@if/g) || []).length);
console.log((chunk.match(/@endif/g) || []).length);
