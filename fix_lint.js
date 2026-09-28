const fs = require("fs");
let js = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/test_syntax.js", "utf8");
js = js.replace(/fetch\(''''\)/g, "fetch('')");
js = js.replace(/location.href = ''''/g, "location.href = ''");
fs.writeFileSync("test_syntax2.js", js);
