const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");

let before = html;
// Regex to safely replace swal.close();
html = html.replace(/swal\.close\(\);/g, "try { swal.close(); } catch(e) {} try { swal.closeModal(); } catch(e) {}");

if (before === html) {
    console.log("No changes made.");
} else {
    fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", html);
    console.log("Replaced swal.close() everywhere safely!");
}
