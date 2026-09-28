const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/rendered_ss.html", "utf8");
let idx = html.indexOf('id="modalJualFnb"');
if (idx !== -1) {
    console.log("FOUND modalJualFnb!");
} else {
    console.log("NOT FOUND :(");
}
