const fs = require("fs");
let ctrl = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/TableOrderController.php", "utf8");
let lines = ctrl.split("\n");
let inProcessCheckout = false;
let braceCount = 0;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('public function processCheckOut')) {
        inProcessCheckout = true;
    }
    if (inProcessCheckout) {
        console.log((i+1) + ": " + lines[i]);
        braceCount += (lines[i].match(/\{/g) || []).length;
        braceCount -= (lines[i].match(/\}/g) || []).length;
        if (braceCount === 0 && lines[i].includes('}')) {
            break;
        }
    }
}
