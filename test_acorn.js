const acorn = require('acorn');
const fs = require('fs');

let js = fs.readFileSync('D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/test_syntax.js', 'utf8');
// Fix manual replacements
js = js.replace(/fetch\(''''\)/g, "fetch('')");
js = js.replace(/location.href = ''''/g, "location.href = ''");

try {
    acorn.parse(js, {ecmaVersion: 2020});
    console.log("Syntax is perfectly valid!");
} catch (e) {
    console.log("SyntaxError at line " + e.loc.line + " col " + e.loc.column);
    console.log(e.message);
    let lines = js.split('\n');
    console.log(lines[e.loc.line - 1]);
    console.log('^'.padStart(e.loc.column + 1));
}
