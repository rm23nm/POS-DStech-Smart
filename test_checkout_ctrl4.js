const fs = require("fs");
let ctrl = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/TableOrderController.php", "utf8");
let lines = ctrl.split("\n");
let inProcessCheckout = false;
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('public function processCheckout')) {
        inProcessCheckout = true;
    }
    if (inProcessCheckout) {
        console.log((i+1) + ": " + lines[i]);
        if (lines[i].includes('return response()->json')) {
            break;
        }
    }
}
