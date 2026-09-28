const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/ItemMasterController.php", "utf8");

let before = html;
let regex = /(if \(\$Scan != ""\) \{[^}]+\})([\s\r\n]*\$data\['data'\] = \$itemmaster->get\(\);)/;
let match = html.match(regex);
if (match) {
    let replacement = match[1] + `
        $KategoriPOS = $request->input('KategoriPOS');
        if ($KategoriPOS != "") {
            $itemmaster->where('itemmaster.KategoriPOS', '=', $KategoriPOS);
        }
` + match[2];
    // We only want to replace in GetStockPerWhs, so let's be careful. Actually, this regex will match the first occurrence.
    // Let's replace ALL occurrences (both ViewJson and GetStockPerWhs) but ViewJson was already handled differently.
}
// Let's just find "function GetStockPerWhs(" and replace within it.
let startIdx = html.indexOf('function GetStockPerWhs');
if (startIdx !== -1) {
    let sub = html.substring(startIdx);
    let regex2 = /(\$data\['data'\] = \$itemmaster->get\(\);)/;
    let match2 = sub.match(regex2);
    if (match2) {
        let replacement2 = `
        $KategoriPOS = $request->input('KategoriPOS');
        if ($KategoriPOS != "") {
            $itemmaster->where('itemmaster.KategoriPOS', '=', $KategoriPOS);
        }
        $data['data'] = $itemmaster->get();`;
        sub = sub.replace(match2[1], replacement2);
        html = html.substring(0, startIdx) + sub;
        fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/ItemMasterController.php", html);
        console.log("Added KategoriPOS filter to GetStockPerWhs safely!");
    } else {
        console.log("Match not found in GetStockPerWhs!");
    }
} else {
    console.log("GetStockPerWhs not found!");
}
