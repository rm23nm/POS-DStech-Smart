const fs = require("fs");
const file = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");

const scriptRegex = /<script\b[^>]*>([\s\S]*?)<\/script>/gm;
let match;
while ((match = scriptRegex.exec(file)) !== null) {
    let js = match[1];
    
    // We can just try parsing with acorn if we have it, or write to file and run node -c
    // Let's write the raw JS to a file and we can manually inspect it
    fs.appendFileSync("all_scripts.js", "\n/* --- NEW SCRIPT BLOCK --- */\n" + js);
}
console.log("Scripts extracted to all_scripts.js");
