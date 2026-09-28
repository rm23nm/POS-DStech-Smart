const fs = require("fs");
let html = fs.readFileSync("BillingSelfService_fixed.blade.php", "utf8");
let lines = html.split("\n");
let stack = [];
for (let i = 0; i < lines.length; i++) {
    if (lines[i].includes('@if')) stack.push({line: i+1, type: 'if'});
    if (lines[i].includes('@foreach')) stack.push({line: i+1, type: 'foreach'});
    if (lines[i].includes('@endif')) {
        let last = stack.reverse().find(x => x.type === 'if');
        stack.reverse();
        if (last) stack.splice(stack.indexOf(last), 1);
        else console.log("Unmatched @endif at line " + (i+1));
    }
    if (lines[i].includes('@endforeach')) {
        let last = stack.reverse().find(x => x.type === 'foreach');
        stack.reverse();
        if (last) stack.splice(stack.indexOf(last), 1);
        else console.log("Unmatched @endforeach at line " + (i+1));
    }
}
for (let s of stack) {
    console.log("Unmatched @" + s.type + " at line " + s.line);
}
