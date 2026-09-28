const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/TableOrderController.php", "utf8");

let regex = /->where\('itemmaster\.RecordOwnerID', Auth::user\(\)->RecordOwnerID\)\s*->where\('itemmaster\.Active', 'Y'\)\s*->get\(\);/g;

let replacement = `->where('itemmaster.RecordOwnerID', Auth::user()->RecordOwnerID)
            ->where('itemmaster.Active', 'Y')
            ->where(function($q) {
                $q->where('itemmaster.KategoriPOS', 'FNB')
                  ->orWhere('itemmaster.KodeJenisItem', 'LIKE', '%FNB%');
            })
            ->get();`;

html = html.replace(regex, replacement);
fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/TableOrderController.php", html);
console.log("Locked other itemmaster queries!");
