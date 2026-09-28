const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/TableOrderController.php", "utf8");

let target = "                    ->where('itemmaster.RecordOwnerID', $roid)\r\n" +
             "                    ->where('itemmaster.Active', 'Y')\r\n" +
             "                    \r\n" +
             "                    ->orderBy('itemmaster.NamaItem', 'ASC')\r\n" +
             "                    ->get();";

let target2 = "                    ->where('itemmaster.RecordOwnerID', $roid)\n" +
             "                    ->where('itemmaster.Active', 'Y')\n" +
             "                    \n" +
             "                    ->orderBy('itemmaster.NamaItem', 'ASC')\n" +
             "                    ->get();";

let replacement = "                    ->where('itemmaster.RecordOwnerID', $roid)\n" +
                  "                    ->where('itemmaster.Active', 'Y')\n" +
                  "                    ->where(function($q) {\n" +
                  "                        $q->where('itemmaster.KategoriPOS', 'FNB')\n" +
                  "                          ->orWhere('itemmaster.KodeJenisItem', 'LIKE', '%FNB%');\n" +
                  "                    })\n" +
                  "                    ->orderBy('itemmaster.NamaItem', 'ASC')\n" +
                  "                    ->get();";

if (html.includes(target)) {
    html = html.replace(target, replacement);
    fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/TableOrderController.php", html);
    console.log("Locked TableOrderController FNB!");
} else if (html.includes(target2)) {
    html = html.replace(target2, replacement);
    fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/TableOrderController.php", html);
    console.log("Locked TableOrderController FNB! (target2)");
} else {
    // try regex
    let regex = /->where\('itemmaster\.RecordOwnerID', \$roid\)\s*->where\('itemmaster\.Active', 'Y'\)\s*->orderBy\('itemmaster\.NamaItem', 'ASC'\)\s*->get\(\);/s;
    if (regex.test(html)) {
        html = html.replace(regex, replacement);
        fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/TableOrderController.php", html);
        console.log("Locked using Regex!");
    } else {
        console.log("Not found at all!");
    }
}
