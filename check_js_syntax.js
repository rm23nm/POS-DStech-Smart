const fs = require("fs");
const file = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");

const scriptRegex = /<script\b[^>]*>([\s\S]*?)<\/script>/gm;
let match;
let count = 0;
while ((match = scriptRegex.exec(file)) !== null) {
    count++;
    let js = match[1];
    // Remove blade syntax before eval
    js = js.replace(/@if.*?@endif/gs, '');
    js = js.replace(/@foreach.*?@endforeach/gs, '');
    js = js.replace(/{{.*?}}/gs, '""');
    js = js.replace(/@php.*?@endphp/gs, '');
    
    try {
        new Function(js);
    } catch(e) {
        console.log("Syntax error in script block " + count + ":");
        console.log(e);
        console.log("Around:");
        let lines = js.split("\n");
        // find approximate line by checking some code
    }
}
console.log("Done checking scripts.");
