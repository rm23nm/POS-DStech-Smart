const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/BillingSelfService_compiled_0906.php", "utf8");
console.log(html.substring(0, 500));
