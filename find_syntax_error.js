const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
const scriptRegex = /<script\b[^>]*>([\s\S]*?)<\/script>/gm;
let match;
let count = 0;
while ((match = scriptRegex.exec(html)) !== null) {
    count++;
    let scriptBlock = match[1];
    let original = scriptBlock;
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
        console.log("Syntax Error in block " + count + "!");
        fs.writeFileSync("broken_ss_block_" + count + ".js", original);
    }
}
