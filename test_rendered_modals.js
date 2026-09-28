const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/rendered_ss.html", "utf8");
let idx = html.indexOf('id="modalPilihPaket"');
if (idx !== -1) {
    console.log("FOUND modalPilihPaket in rendered HTML!");
    console.log(html.substring(Math.max(0, idx - 100), idx + 500));
} else {
    console.log("Not found :(");
}
