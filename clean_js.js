const fs = require("fs");
let js = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/all_scripts.js", "utf8");
js = js.replace(/\{!!.*?!!\}/g, '""');
js = js.replace(/\{\{.*?\}\}/g, '""');
js = js.replace(/@if.*?@endif/g, '');
js = js.replace(/@foreach.*?@endforeach/g, '');
fs.writeFileSync("all_scripts_clean.js", js);
