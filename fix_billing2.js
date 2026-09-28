const fs = require("fs");
let c = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");

let startIdx = c.indexOf('</div>\n\n<!-- Row: Service Type');
if (startIdx === -1) {
    console.log("Still not found");
} else {
    let endIdx = c.indexOf('<div style="width:450px;', startIdx);
    
    let restored = fs.readFileSync("restored_chunk_ss.html", "utf8");
    let bn = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
    let loopStart = bn.indexOf('@foreach($itemmaster as $item)');
    let loopEnd = bn.indexOf('<!-- Row: Service Type', loopStart);
    let loopChunk = bn.substring(loopStart, loopEnd).trim();
    if (!loopChunk.endsWith('</div>')) loopChunk += '\n</div>';
    
    let footerStart = bn.indexOf('<!-- Row: Service Type');
    let footerEnd = bn.indexOf('<div style="width:450px;', footerStart);
    let footerChunk = bn.substring(footerStart, footerEnd);
    
    footerChunk = footerChunk.replace('id="btnConfirmTambahDurasi"', 'id="btnSubmitPaket"');
    footerChunk = footerChunk.replace('onclick="submitTambahDurasi()"', 'onclick="onKonfirmasiPaket()"');
    footerChunk = footerChunk.replace('SIMPAN / BAYAR', 'BAYAR & AKTIFKAN');

    let correctSequence = `</div>
            @endif
        @endforeach
    </div>
</div>

` + restored + "\n" + loopChunk + "\n\n" + footerChunk;

    let fixedFile = c.substring(0, startIdx) + correctSequence + c.substring(endIdx);
    fs.writeFileSync("BillingSelfService_fixed.blade.php", fixedFile);
    console.log("Fixed file created!");
}
