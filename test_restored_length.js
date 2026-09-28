const fs = require("fs");
let restored = fs.readFileSync("restored_chunk_ss.html", "utf8");
console.log(restored.length);
