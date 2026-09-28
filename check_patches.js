const fs = require("fs");
const files = fs.readdirSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com");
let found = [];
for (let f of files) {
    if (f.endsWith('.php') && f.startsWith('test_')) {
        let content = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/" + f, "utf8");
        if (content.includes('file_put_contents') && content.includes('str_replace')) {
            found.push(f);
        }
    }
}
console.log("Patches: ", found.join(', '));
