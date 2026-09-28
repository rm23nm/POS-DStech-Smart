const fs = require("fs");
let js = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
const scriptRegex = /<script\b[^>]*>([\s\S]*?)<\/script>/gm;
let match;
while ((match = scriptRegex.exec(js)) !== null) {
    let scriptBlock = match[1];
    scriptBlock = scriptBlock.replace(/\{!! json_encode\(.*?\) !!\}/g, '[]');
    scriptBlock = scriptBlock.replace(/'\{\{ route\(.*?\) \}\}'/g, "''"); 
    scriptBlock = scriptBlock.replace(/\{\{ route\(.*?\) \}\}/g, "''"); 
    scriptBlock = scriptBlock.replace(/'\{\{ url\(.*?\) \}\}'/g, "''");
    scriptBlock = scriptBlock.replace(/\{\{ url\(.*?\) \}\}/g, "''");
    scriptBlock = scriptBlock.replace(/@if.*?@endif/gs, '');
    scriptBlock = scriptBlock.replace(/@foreach.*?@endforeach/gs, '');
    scriptBlock = scriptBlock.replace(/\{\{.*?\}\}/g, "''");
    
    scriptBlock = scriptBlock.replace(/fetch\(''''\)/g, "fetch('')");
    
    try {
        new Function(scriptBlock);
    } catch(e) {
        console.log("Syntax Error in billing_new!");
        console.log(e);
        process.exit(1);
    }
}
console.log("billing_new passed syntax check!");
