const fs = require("fs");
let html = fs.readFileSync("restored_chunk.html", "utf8");
console.log(html);
