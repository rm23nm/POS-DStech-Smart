const fs = require("fs");
let html = fs.readFileSync("BillingSelfService_fixed.blade.php", "utf8");

// Remove the first FNB loop, keep the second one.
let firstLoopStart = html.indexOf('@foreach($itemmaster as $item)');
let secondLoopStart = html.indexOf('@foreach($itemmaster as $item)', firstLoopStart + 1);

let endOfFirstLoop = html.indexOf('</div>', html.indexOf('@endforeach', firstLoopStart)) + 6;
// But wait, there is </div>\n</div> after it!
endOfFirstLoop = html.indexOf('@foreach($itemmaster as $item)', firstLoopStart + 1);

let fixedHtml = html.substring(0, firstLoopStart) + html.substring(secondLoopStart);
fs.writeFileSync("BillingSelfService_fixed_clean.blade.php", fixedHtml);
console.log("Cleaned fixed file!");
