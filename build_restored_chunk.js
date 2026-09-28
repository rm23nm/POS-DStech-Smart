const fs = require("fs");
let rendered = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/rendered_ss.html", "utf8");

// Extract Right Panel from rendered
let rightPanelStart = rendered.indexOf('<!-- Right Panel : Detail Meja -->');
let rightPanelEnd = rendered.indexOf('<!-- ===== MODALS ===== -->');
let rightPanel = rendered.substring(rightPanelStart, rightPanelEnd);

// Extract modalPilihPaket from billing_new.blade.php
let bn = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let modalStart = bn.indexOf('<!-- ===== MODAL PILIH PAKET ===== -->');
let modalEnd = bn.indexOf('<div class="fnb-list-container"', modalStart);
let modalHeader = bn.substring(modalStart, modalEnd);

// Modify modalHeader to match Self Service IDs (ppFnbList instead of ppFnbMenuList)
modalHeader = modalHeader.replace('id="ppFnbCategoryFilter"', 'id="ssCategoryFilter1"');
modalHeader = modalHeader.replace('id="ppFnbSearchInput"', 'id="ssSearchInput1"');
modalHeader = modalHeader.replace(`$('#ppFnbSearchInput').val(), 'ppFnbMenuList'`, `$('#ssSearchInput1').val(), 'ppFnbList'`);
modalHeader = modalHeader.replace(`this.value, 'ppFnbMenuList', $('#ppFnbCategoryFilter').val()`, `this.value, 'ppFnbList', $('#ssCategoryFilter1').val()`);
modalHeader = modalHeader.replace('id="ppFnbTotalCount"', 'id="fnbTotalCount"');
modalHeader += `<div class="fnb-list" id="ppFnbList">\n`;

let restoredChunk = "\n" + rightPanel + "\n" + modalHeader;
fs.writeFileSync("restored_chunk.html", restoredChunk);
console.log("Restored chunk saved!");
