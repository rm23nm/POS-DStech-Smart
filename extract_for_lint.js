const fs = require("fs");
const file = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");
const scriptRegex = /<script\b[^>]*>([\s\S]*?)<\/script>/gm;
let match;
let count = 0;
while ((match = scriptRegex.exec(file)) !== null) {
    count++;
    if (match[1].includes('selectTitikLampu')) {
        let js = match[1];
        // replace {!! json_encode(...) !!} with "[]" just so it parses
        js = js.replace(/\{!! json_encode\(.*?\) !!\}/g, '[]');
        // replace {{ route(...) }} with "''"
        js = js.replace(/\{\{ route\(.*?\) \}\}/g, "''");
        // replace {{ url(...) }} with "''"
        js = js.replace(/\{\{ url\(.*?\) \}\}/g, "''");
        // replace @if and @endif
        js = js.replace(/@if.*?@endif/gs, '');
        // replace @foreach
        js = js.replace(/@foreach.*?@endforeach/gs, '');
        js = js.replace(/\{\{.*?\}\}/g, "''");
        fs.writeFileSync("test_syntax.js", js);
        console.log("Saved test_syntax.js");
    }
}
