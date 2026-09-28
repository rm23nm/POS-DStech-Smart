const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/rendered_ss.html", "utf8");

let startIdx = html.indexOf('<div class="right-panel">');
let endIdx = html.indexOf('id="ppFnbList"');
let chunk = html.substring(startIdx, endIdx + 15) + "\n";

let jenisPaketRegex = /<select class="pp-input" id="ppJenisPaket" onchange="onJenisPaketChange\(this.value\)">[\s\S]*?<\/select>/;
let jenisPaketBlade = `<select class="pp-input" id="ppJenisPaket" name="JenisPaket" onchange="onJenisPaketChange(this.value)">
                                <option value="">-- Pilih Jenis --</option>
                                @if(isset($jenis_paket))
                                    @foreach($jenis_paket as $jp)
                                        <option value="{{ $jp->KodeJenisPaket }}">{{ $jp->NamaJenisPaket }}</option>
                                    @endforeach
                                @endif
                            </select>`;
chunk = chunk.replace(jenisPaketRegex, jenisPaketBlade);

let pelangganRegex = /<select class="pp-input" id="ppKodePelanggan" onchange="calculateTotal\(\)" disabled style="pointer-events:none; background:#f0f0f0; color:#888;">[\s\S]*?<\/select>/;
let pelangganBlade = `<select class="pp-input mt-1" id="ppKodePelanggan" name="KodePelanggan" onchange="calculateTotal()" disabled style="pointer-events:none; background:#f0f0f0; color:#888;">
                                <option value="">-- Umum / Guest --</option>
                                @foreach($pelanggan as $plg)
                                    <option value="{{ $plg->KodePelanggan }}">{{ $plg->NamaPelanggan }}</option>
                                @endforeach
                            </select>`;
chunk = chunk.replace(pelangganRegex, pelangganBlade);

// Also missing the FNB Loop! Let's get it from billing_new.blade.php
let bn = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/billing_new.blade.php", "utf8");
let loopStart = bn.indexOf('@foreach($itemmaster as $item)');
let loopEnd = bn.indexOf('<!-- Row: Service Type', loopStart);
let loopChunk = bn.substring(loopStart, loopEnd).trim();
if (!loopChunk.endsWith('</div>')) loopChunk += '\n</div>';

chunk = chunk + "\n" + loopChunk + "\n";

fs.writeFileSync("restored_chunk_ss.html", chunk);
console.log("Length of restored_chunk_ss.html:", chunk.length);
