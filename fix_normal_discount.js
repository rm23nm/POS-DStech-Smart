const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/NormalPoS.blade.php", "utf8");

let target = `	            	if (response.data.length > 0) {
	            		_DiskonGrupCustomer = response.data[0]['DiskonPersen'];
	            		_TerminPelanggan = response.data[0]['DiskonPersen'];
	            		// console.log(response.data[0]);`;

let replacement = `	            	if (response.data.length > 0) {
	            		_DiskonGrupCustomer = response.data[0]['DiskonPersen'];
	            		_TerminPelanggan = response.data[0]['DiskonPersen'];

                        var _DiskonMemberPersen = parseFloat(response.data[0]['DiskonMemberPersen'] || 0);
                        _DiskonGrupCustomer = parseFloat(_DiskonGrupCustomer || 0) + _DiskonMemberPersen;
	            		// console.log(response.data[0]);`;

if (html.includes(target)) {
    html = html.replace(target, replacement);
    fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/NormalPoS.blade.php", html);
    console.log("Added member discount to NormalPoS!");
} else {
    console.log("Target not found!");
}
