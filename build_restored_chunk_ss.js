const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/rendered_ss.html", "utf8");

let rightPanelStart = html.indexOf('<!-- Right Panel : Detail Meja -->');
let fnbListEnd = html.indexOf('id="ppFnbList"');
let chunk = html.substring(rightPanelStart, fnbListEnd + 15) + "\n";

// Replace Jenis Paket hardcoded options with a loop?
// Wait, looking at rendered_ss.html, the Jenis Paket has:
// <option value="MENIT">Paket Menit</option>
// We can just leave it hardcoded! (If it was hardcoded originally).
// In billing_new, it is: @foreach($jenis_paket as $jp) <option value="{{ $jp->KodeJenisPaket }}">{{ $jp->NamaJenisPaket }}</option> @endforeach
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

fs.writeFileSync("restored_chunk_ss.html", chunk);
console.log("Done");
