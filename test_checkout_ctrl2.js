const fs = require("fs");
let ctrl = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/app/Http/Controllers/TableOrderController.php", "utf8");
let start = ctrl.indexOf('public function processCheckout');
let end = ctrl.indexOf('}', ctrl.indexOf('return response()->json', start) + 100);
console.log(ctrl.substring(start, end));
