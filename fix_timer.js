const fs = require("fs");
let html = fs.readFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", "utf8");

let target = `setInterval(() => {
        $('.titik-box').each(function() {
            var s = parseInt(this.dataset.status); if (s === 0) return;
            var start = this.dataset.rawjammulai; var end = this.dataset.rawjamselesai;
            var _nowLocal = new Date(); var label = "--:--:--";
            if (end && end !== 'null' && end !== '') {
                var diff = new Date(end.replace(' ', 'T')) - _nowLocal;
                label = diff < 0 ? "WAKTU HABIS" : formatDur(diff);
            } else if (start) {
                label = formatDur(_nowLocal - new Date(start.replace(' ', 'T')));
            }
            $(this).find('.table-timer').text(label);
        });
    }, 1000);`;

let replacement = `setInterval(() => {
        $('.titik-box').each(function() {
            var s = parseInt(this.dataset.status); if (s === 0) return;
            var start = this.dataset.rawjammulai; var end = this.dataset.rawjamselesai;
            var _nowLocal = new Date(); var label = "--:--:--";
            
            var timerEl = $(this).find('.table-timer');
            
            if (end && end !== 'null' && end !== '') {
                var diff = new Date(end.replace(' ', 'T')) - _nowLocal;
                if (diff < 0) {
                    label = "WAKTU HABIS";
                    timerEl.css('color', '#d32f2f');
                    // Change to status checkout (n1)
                    $(this).removeClass('status-1 status-99').addClass('status-n1');
                } else {
                    label = formatDur(diff);
                    timerEl.css('color', 'inherit');
                    // Warning: 10 minutes or less
                    if (diff <= 10 * 60 * 1000) {
                        if (!$(this).hasClass('status-n1')) {
                            $(this).removeClass('status-1').addClass('status-99');
                        }
                    }
                }
            } else if (start) {
                label = formatDur(_nowLocal - new Date(start.replace(' ', 'T')));
                timerEl.css('color', 'inherit');
            }
            
            timerEl.text(label);
        });
    }, 1000);`;

let newHtml = html.replace(target, replacement);
fs.writeFileSync("D:/OneDrive/My Project Aplikasi/pos.dstechsmart.com/resources/views/Transaksi/Penjualan/PoS/BillingSelfService.blade.php", newHtml);
console.log("Timer logic updated!");
