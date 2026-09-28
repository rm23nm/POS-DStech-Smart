const fs = require("fs");
let js = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
const scriptRegex = /<script\b[^>]*>([\s\S]*?)<\/script>/gm;
let match;
let count = 0;
while ((match = scriptRegex.exec(js)) !== null) {
    count++;
    if (count !== 5 && count !== 6) continue;
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
    
    fs.writeFileSync(`broken_bn_clean_${count}.js`, scriptBlock);
}
