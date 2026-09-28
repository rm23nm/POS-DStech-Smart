const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/storage/framework/views/0465ce588c385e6502a695cbb8e3e83143b9a604.php", "utf8");
let lines = html.split("\n");
for (let i = 328; i <= 340; i++) {
    console.log(i + ": " + lines[i-1].trim());
}
