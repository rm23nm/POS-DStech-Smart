const fs = require("fs");
const html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");

const regex = /<script\b[^>]*>([\s\S]*?)<\/script>/gi;
let match;
let count = 0;
while ((match = regex.exec(html)) !== null) {
    count++;
    const code = match[1];
    try {
        new Function(code);
    } catch (e) {
        console.log("Syntax error in script block " + count + "!");
        console.log(e.toString());
        console.log("--- Snippet ---");
        console.log(code.substring(Math.max(0, code.length - 200)));
        console.log("---------------");
    }
}
console.log("Checked " + count + " script blocks.");
