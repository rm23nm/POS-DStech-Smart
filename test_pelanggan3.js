const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/PelangganController.php", "utf8");
let lines = html.split("\n");
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('function ViewJson')) {
        console.log("Found at line " + (i+1));
        for(let j=i; j<i+20; j++){
            console.log(lines[j]);
        }
    }
}
