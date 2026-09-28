const fs = require("fs");
let html = fs.readFileSync("BillingSelfService_fixed.blade.php", "utf8");
let lines = html.split("\n");
let stack = [];
for (let i = 0; i < lines.length; i++) {
    let line = lines[i];
    let ifs = (line.match(/@if\b/g) || []).length;
    let endifs = (line.match(/@endif\b/g) || []).length;
    for (let j=0; j<ifs; j++) stack.push({line: i+1, type: 'if'});
    for (let j=0; j<endifs; j++) {
        let last = stack.reverse().find(x => x.type === 'if');
        stack.reverse();
        if (last) stack.splice(stack.indexOf(last), 1);
        else console.log("Unmatched @endif at line " + (i+1));
    }
}
for (let s of stack) {
    console.log("Unmatched @" + s.type + " at line " + s.line);
}
