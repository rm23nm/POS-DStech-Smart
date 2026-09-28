const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/TableOrderController.php", "utf8");

let target = `                    ->where('itemmaster.RecordOwnerID', $roid)
                    ->where('itemmaster.Active', 'Y')
                    
                    ->orderBy('itemmaster.NamaItem', 'ASC')
                    ->get();`;

let replacement = `                    ->where('itemmaster.RecordOwnerID', $roid)
                    ->where('itemmaster.Active', 'Y')
                    ->where(function($q) {
                        $q->where('itemmaster.KategoriPOS', 'FNB')
                          ->orWhere('itemmaster.KodeJenisItem', 'LIKE', '%FNB%');
                    })
                    ->orderBy('itemmaster.NamaItem', 'ASC')
                    ->get();`;

if (html.includes(target)) {
    html = html.replace(target, replacement);
    fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/TableOrderController.php", html);
    console.log("Locked TableOrderController FNB!");
} else {
    console.log("Target not found!");
}
