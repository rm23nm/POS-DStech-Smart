const fs = require("fs");
let chunk = fs.readFileSync("restored_chunk_ss.html", "utf8");
console.log(chunk.substring(0, 500));
console.log("\n...\n");
console.log(chunk.substring(chunk.length - 1000));
