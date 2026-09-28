const fs = require("fs");
let lines = fs.readFileSync("C:\\Users\\DSTech  Smart\\.gemini\\antigravity\\brain\\88be7920-275e-4b3a-80b8-97ee59411ab7\\.system_generated\\logs\\transcript.jsonl", "utf8").split("\n");
for (let line of lines) {
    if (line.includes("btnSubmitPaket")) {
        console.log("Found btnSubmitPaket in transcript!");
        // extract the timestamp
        let obj = JSON.parse(line);
        console.log("Time: " + obj.created_at);
    }
}
