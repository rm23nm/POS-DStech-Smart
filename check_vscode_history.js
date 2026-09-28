const fs = require("fs");
const path = require("path");

const historyDir = "C:\\Users\\DSTech  Smart\\AppData\\Roaming\\Code\\User\\History";
if (!fs.existsSync(historyDir)) {
    console.log("No VSCode history found.");
    process.exit(0);
}

const folders = fs.readdirSync(historyDir);
let latestFile = null;
let latestTime = 0;
let targetSize = 0;

for (const folder of folders) {
    const folderPath = path.join(historyDir, folder);
    if (fs.statSync(folderPath).isDirectory()) {
        const files = fs.readdirSync(folderPath);
        for (const file of files) {
            const filePath = path.join(folderPath, file);
            try {
                const content = fs.readFileSync(filePath, "utf8");
                if (content.includes('id="modalPilihPaket"') && content.includes('btnSubmitPaket')) {
                    const stats = fs.statSync(filePath);
                    if (stats.mtimeMs > latestTime) {
                        latestTime = stats.mtimeMs;
                        latestFile = filePath;
                        targetSize = stats.size;
                    }
                }
            } catch(e) {}
        }
    }
}

if (latestFile) {
    console.log("Found backup in VSCode history: " + latestFile);
    console.log("Size: " + targetSize);
    console.log("Time: " + new Date(latestTime).toISOString());
    fs.copyFileSync(latestFile, "D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php.vscode.bak");
    console.log("Saved as BillingSelfService.blade.php.vscode.bak");
} else {
    console.log("Not found in VSCode history.");
}
