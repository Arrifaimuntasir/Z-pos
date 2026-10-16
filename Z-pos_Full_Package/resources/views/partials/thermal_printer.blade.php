{{--
    Thermal receipt printing for a sale.
    Include once on a page that has a $sale; call ZPrinter.print() from the Print button.
    Modes (chosen per device, kept in localStorage):
      a4        - normal browser print of the full page (old behaviour)
      thermal   - browser print dialog with a 58/80mm roll layout (USB / network printers on a PC)
      bluetooth - direct ESC/POS over Web Bluetooth (Chrome on Android / PC, BLE printers)
      rawbt     - ESC/POS handed to the RawBT Android app (works with classic Bluetooth printers)
--}}
@php
    $zpShop = $sale->shop;
    $zpPaper = (int) ($zpShop->receipt_paper_width ?? 80) === 58 ? 58 : 80;
    $zpQty = fn ($q) => rtrim(rtrim(number_format((float) $q, 3, '.', ''), '0'), '.');
    $zpItems = $sale->items->filter(fn ($i) => $i->net_quantity > 0)->values();
    $zpTotal = $sale->net_total_amount;
    $zpProforma = $sale->payment_status === 'proforma';
    $zpBalance = $zpTotal - $sale->paid_amount;
    $zpDate = \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') . ($sale->created_at ? ' ' . $sale->created_at->format('H:i') : '');

    $zpReceipt = [
        'paper' => $zpPaper,
        'shopName' => $zpShop->name ?? 'Z-POS',
        'address' => $zpShop->address ?? '',
        'phone' => $zpShop->phone ?? '',
        'tin' => $zpShop->tin_number ?? '',
        'title' => $zpProforma ? __('PRO-FORMA INVOICE') : __('RECEIPT'),
        'ref' => $sale->reference_no,
        'date' => $zpDate,
        'cashier' => optional($sale->user)->name,
        'customer' => $sale->customer->name ?? __('Walk-in Customer'),
        'method' => $sale->payment_method,
        'items' => $zpItems->map(fn ($i) => [
            'name' => $i->product ? $i->product->name : 'Unknown Product',
            'serial' => $i->imei_serial_number,
            'qty' => $zpQty($i->net_quantity),
            'price' => number_format($i->unit_price),
            'total' => number_format($i->net_total),
        ])->all(),
        'total' => number_format($zpTotal),
        'paid' => $zpProforma ? null : number_format($sale->paid_amount),
        'balance' => (!$zpProforma && $zpBalance > 0) ? number_format($zpBalance) : null,
        'notes' => $sale->notes,
        'footer' => $zpShop->receipt_message ?: 'Thank you for your business!',
        'labels' => [
            'receiptNo' => __('Receipt No'), 'date' => __('Date'), 'cashier' => __('Cashier'),
            'customer' => __('Customer'), 'method' => __('Method'), 'item' => __('Item'),
            'total' => __('TOTAL'), 'paid' => __('Paid'), 'balance' => __('Balance Due'),
            'tin' => 'TIN', 'tel' => 'Tel', 'poweredBy' => 'Powered by Z-POS',
        ],
    ];
    $zpStrings = [
        'noBluetooth' => __('Bluetooth printing is not supported in this browser. Use Chrome on Android or a computer, or choose the RawBT app option.'),
        'noBluetoothIos' => __('iPhone and iPad browsers cannot connect to Bluetooth printers. Use the Share or PDF button instead.'),
        'connectFailed' => __('Could not connect to the printer'),
        'noWritable' => __('This device does not look like a supported printer. Try the RawBT app option.'),
        'printFailed' => __('Printing failed'),
        'reconnect' => __('Tap Connect to choose your printer again.'),
        'connect' => __('Connect'),
        'cancel' => __('Cancel'),
        'notConnected' => __('Not connected'),
        'sent' => __('Sent to printer'),
    ];
@endphp

{{-- Roll-width receipt used by the "thermal" mode. Moved to <body> by the script, only shown while printing. --}}
<div id="thermalReceipt" class="zp-thermal zp-thermal-{{ $zpPaper }}" aria-hidden="true">
    <div class="zp-c zp-b zp-lg">{{ $zpReceipt['shopName'] }}</div>
    @if($zpReceipt['address'])<div class="zp-c">{{ $zpReceipt['address'] }}</div>@endif
    @if($zpReceipt['phone'])<div class="zp-c">Tel: {{ $zpReceipt['phone'] }}</div>@endif
    @if($zpReceipt['tin'])<div class="zp-c">TIN: {{ $zpReceipt['tin'] }}</div>@endif
    <div class="zp-hr"></div>
    <div class="zp-c zp-b">{{ $zpReceipt['title'] }}</div>
    <div class="zp-row"><span>{{ __('Receipt No') }}</span><span>{{ $zpReceipt['ref'] }}</span></div>
    <div class="zp-row"><span>{{ __('Date') }}</span><span>{{ $zpReceipt['date'] }}</span></div>
    @if($zpReceipt['cashier'])<div class="zp-row"><span>{{ __('Cashier') }}</span><span>{{ $zpReceipt['cashier'] }}</span></div>@endif
    <div class="zp-row"><span>{{ __('Customer') }}</span><span>{{ $zpReceipt['customer'] }}</span></div>
    @if($zpReceipt['method'])<div class="zp-row"><span>{{ __('Method') }}</span><span>{{ $zpReceipt['method'] }}</span></div>@endif
    <div class="zp-hr"></div>
    @foreach($zpReceipt['items'] as $it)
        <div class="zp-item">{{ $it['name'] }}</div>
        @if($it['serial'])<div class="zp-sub">IMEI/SN: {{ $it['serial'] }}</div>@endif
        <div class="zp-row zp-sub"><span>{{ $it['qty'] }} x {{ $it['price'] }}</span><span>{{ $it['total'] }}</span></div>
    @endforeach
    <div class="zp-hr"></div>
    <div class="zp-row zp-b zp-lg"><span>{{ __('TOTAL') }}</span><span>{{ $zpReceipt['total'] }} TSh</span></div>
    @if($zpReceipt['paid'] !== null)<div class="zp-row"><span>{{ __('Paid') }}</span><span>{{ $zpReceipt['paid'] }} TSh</span></div>@endif
    @if($zpReceipt['balance'] !== null)<div class="zp-row zp-b"><span>{{ __('Balance Due') }}</span><span>{{ $zpReceipt['balance'] }} TSh</span></div>@endif
    @if($zpReceipt['notes'])<div class="zp-hr"></div><div>{{ $zpReceipt['notes'] }}</div>@endif
    <div class="zp-hr"></div>
    <div class="zp-c">{{ $zpReceipt['footer'] }}</div>
    <div class="zp-c zp-sub">Powered by Z-POS</div>
</div>

{{-- Printer settings (per device) --}}
<div class="modal fade d-print-none" id="printerSettingsModal" tabindex="-1" aria-labelledby="printerSettingsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="printerSettingsLabel"><i class="bi bi-printer me-2"></i>{{ __('Printer Settings') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">{{ __('This choice is saved on this device only, so each till can use its own printer.') }}</p>

                <div class="list-group mb-3">
                    <label class="list-group-item d-flex gap-3 align-items-start" style="cursor:pointer;">
                        <input class="form-check-input flex-shrink-0 mt-1" type="radio" name="zpPrintMode" value="a4">
                        <span><strong>{{ __('Normal printer (A4)') }}</strong><br><small class="text-muted">{{ __('Prints the full page, like before.') }}</small></span>
                    </label>
                    <label class="list-group-item d-flex gap-3 align-items-start" style="cursor:pointer;">
                        <input class="form-check-input flex-shrink-0 mt-1" type="radio" name="zpPrintMode" value="thermal">
                        <span><strong>{{ __('Thermal printer (USB / network)') }}</strong><br><small class="text-muted">{{ __('For a receipt printer installed on this computer. Choose it in the print window.') }}</small></span>
                    </label>
                    <label class="list-group-item d-flex gap-3 align-items-start" style="cursor:pointer;" id="zpModeBluetoothRow">
                        <input class="form-check-input flex-shrink-0 mt-1" type="radio" name="zpPrintMode" value="bluetooth">
                        <span><strong>{{ __('Bluetooth thermal printer') }}</strong><br><small class="text-muted" id="zpBluetoothHint">{{ __('Prints directly with one tap. Works in Chrome on Android or a computer.') }}</small></span>
                    </label>
                    <label class="list-group-item d-flex gap-3 align-items-start" style="cursor:pointer;">
                        <input class="form-check-input flex-shrink-0 mt-1" type="radio" name="zpPrintMode" value="rawbt">
                        <span><strong>{{ __('RawBT app (Android)') }}</strong><br><small class="text-muted">{{ __('Use this if your Bluetooth printer does not show up above. Install "RawBT" from the Play Store and pair the printer there once.') }}</small></span>
                    </label>
                </div>

                <div id="zpBluetoothPanel" class="p-3 rounded-3 bg-light mb-3 d-none">
                    <div class="d-flex justify-content-between align-items-center gap-2">
                        <div class="small">
                            <span class="text-muted">{{ __('Printer') }}:</span>
                            <strong id="zpBtName">{{ __('Not connected') }}</strong>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" id="zpBtConnect"><i class="bi bi-bluetooth me-1"></i>{{ __('Connect') }}</button>
                    </div>
                    <small class="text-muted d-block mt-2">{{ __('Turn the printer on and keep it close. Turn on Bluetooth and Location on the phone.') }}</small>
                </div>

                <div class="small text-muted">
                    {{ __('Paper width') }}: <strong>{{ $zpPaper }}mm</strong>
                    @if(auth()->user()->hasAnyRole(['Administrator', 'Super Admin']))
                        &middot; <a href="{{ route('shop.settings') }}">{{ __('Change in Shop Settings') }}</a>
                    @endif
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-3" id="zpTestPrint"><i class="bi bi-receipt me-1"></i>{{ __('Print this receipt') }}</button>
                <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-dismiss="modal">{{ __('Done') }}</button>
            </div>
        </div>
    </div>
</div>

<style>
#thermalReceipt { display: none; }
.zp-thermal { font-family: "Courier New", Courier, monospace; color: #000; font-size: 12px; line-height: 1.3; padding: 2mm 3mm; }
.zp-thermal-58 { width: 58mm; font-size: 11px; }
.zp-thermal-80 { width: 80mm; }
.zp-thermal .zp-c { text-align: center; }
.zp-thermal .zp-b { font-weight: 700; }
.zp-thermal .zp-lg { font-size: 1.25em; }
.zp-thermal .zp-sub { font-size: .92em; }
.zp-thermal .zp-item { font-weight: 600; margin-top: 2px; word-break: break-word; }
.zp-thermal .zp-row { display: flex; justify-content: space-between; gap: 6px; }
.zp-thermal .zp-row span:last-child { text-align: right; word-break: break-word; }
.zp-thermal .zp-hr { border-top: 1px dashed #000; margin: 4px 0; }
@media print {
    body.zp-thermal-print { background: #fff !important; margin: 0 !important; padding: 0 !important; }
    body.zp-thermal-print > *:not(#thermalReceipt) { display: none !important; }
    body.zp-thermal-print #thermalReceipt { display: block !important; }
}
</style>

<script>
window.ZPrinter = (function () {
    const R = @json($zpReceipt);
    const T = @json($zpStrings);
    const STORE_KEY = 'zpos.printer';
    // Service UUIDs used by common BLE receipt printers (Xprinter, Goojprt, MTP, PeriPage, generic "BlueTooth Printer" etc.)
    const BLE_SERVICES = [
        '000018f0-0000-1000-8000-00805f9b34fb',
        'e7810a71-73ae-499d-8c15-faa9aef0c3f2',
        '49535343-fe7d-4ae5-8fa9-9fafd205e455',
        '0000ff00-0000-1000-8000-00805f9b34fb',
        '0000ffe0-0000-1000-8000-00805f9b34fb',
        '0000fee7-0000-1000-8000-00805f9b34fb',
        '0000ae30-0000-1000-8000-00805f9b34fb',
        '0000af30-0000-1000-8000-00805f9b34fb',
    ];

    let bt = { device: null, ch: null };

    function load() {
        try { return Object.assign({ mode: 'a4' }, JSON.parse(localStorage.getItem(STORE_KEY) || '{}')); }
        catch (e) { return { mode: 'a4' }; }
    }
    function save(patch) {
        const cfg = Object.assign(load(), patch);
        try { localStorage.setItem(STORE_KEY, JSON.stringify(cfg)); } catch (e) {}
        return cfg;
    }

    function alertBox(icon, title, text) {
        if (typeof Swal !== 'undefined') return Swal.fire({ icon, title, text, confirmButtonColor: '#16a34a' });
        alert(title + (text ? '\n' + text : ''));
    }
    function toast(text) {
        if (typeof Swal !== 'undefined') Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: text, showConfirmButton: false, timer: 1800 });
    }

    // ---------- ESC/POS ----------
    function ascii(s) {
        return String(s == null ? '' : s).normalize('NFD').replace(/[̀-ͯ]/g, '')
            .replace(/[‘’]/g, "'").replace(/[“”]/g, '"').replace(/[–—]/g, '-')
            .replace(/\s+/g, ' ').replace(/[^\x20-\x7E]/g, '?').trim();
    }
    function wrap(s, w) {
        const out = []; let line = '';
        ascii(s).split(' ').forEach(word => {
            while (word.length > w) { if (line) { out.push(line); line = ''; } out.push(word.slice(0, w)); word = word.slice(w); }
            if (!word) return;
            if (!line) line = word;
            else if ((line + ' ' + word).length <= w) line += ' ' + word;
            else { out.push(line); line = word; }
        });
        if (line) out.push(line);
        return out.length ? out : [''];
    }
    function lr(left, right, w) {
        left = ascii(left); right = ascii(right);
        if (left.length + right.length + 1 > w) {
            const l = wrap(left, w);
            const last = l.pop();
            if (last.length + right.length + 1 <= w) return l.concat([last + ' '.repeat(w - last.length - right.length) + right]);
            return l.concat([last, ' '.repeat(Math.max(0, w - right.length)) + right.slice(-w)]);
        }
        return [left + ' '.repeat(w - left.length - right.length) + right];
    }

    function buildEscPos(r) {
        const W = r.paper === 58 ? 32 : 48;
        const L = r.labels;
        const b = [];
        const cmd = (...x) => b.push(...x);
        const text = s => { for (let i = 0; i < s.length; i++) b.push(s.charCodeAt(i)); };
        const line = (s = '') => { text(s); b.push(0x0A); };
        const lines = arr => arr.forEach(line);
        const hr = () => line('-'.repeat(W));
        const align = n => cmd(0x1B, 0x61, n);           // ESC a n
        const style = n => cmd(0x1B, 0x21, n);           // ESC ! n (0x08 bold, 0x10 dbl-height, 0x20 dbl-width)

        cmd(0x1B, 0x40);                                 // ESC @ init
        cmd(0x1B, 0x74, 0x00);                           // ESC t 0 (PC437)
        align(1);
        style(0x38); lines(wrap(r.shopName, Math.floor(W / 2))); style(0);
        if (r.address) lines(wrap(r.address, W));
        if (r.phone) line(L.tel + ': ' + ascii(r.phone));
        if (r.tin) line(L.tin + ': ' + ascii(r.tin));
        align(0); hr();
        align(1); style(0x08); line(ascii(r.title)); style(0); align(0);
        lines(lr(L.receiptNo, r.ref, W));
        lines(lr(L.date, r.date, W));
        if (r.cashier) lines(lr(L.cashier, r.cashier, W));
        lines(lr(L.customer, r.customer, W));
        if (r.method) lines(lr(L.method, r.method, W));
        hr();
        r.items.forEach(it => {
            style(0x08); lines(wrap(it.name, W)); style(0);
            if (it.serial) lines(wrap('IMEI/SN: ' + it.serial, W));
            lines(lr(it.qty + ' x ' + it.price, it.total, W));
        });
        hr();
        style(0x18); lines(lr(L.total, r.total + ' TSh', W)); style(0);   // bold + double height keeps full width
        if (r.paid !== null) lines(lr(L.paid, r.paid + ' TSh', W));
        if (r.balance !== null) { style(0x08); lines(lr(L.balance, r.balance + ' TSh', W)); style(0); }
        if (r.notes) { hr(); lines(wrap(r.notes, W)); }
        hr();
        align(1);
        lines(wrap(r.footer, W));
        line(L.poweredBy);
        align(0);
        cmd(0x1B, 0x64, 0x04);                           // ESC d 4 feed
        cmd(0x1D, 0x56, 0x42, 0x00);                     // GS V B 0 partial cut (ignored by printers without a cutter)
        return new Uint8Array(b);
    }

    // ---------- Web Bluetooth ----------
    async function attach(device) {
        const server = device.gatt.connected ? device.gatt : await device.gatt.connect();
        let ch = null;
        const services = await server.getPrimaryServices();
        for (const s of services) {
            let chars = [];
            try { chars = await s.getCharacteristics(); } catch (e) { continue; }
            ch = chars.find(c => c.properties.writeWithoutResponse) || chars.find(c => c.properties.write);
            if (ch) break;
        }
        if (!ch) { try { device.gatt.disconnect(); } catch (e) {} throw new Error(T.noWritable); }
        if (bt.device !== device) device.addEventListener('gattserverdisconnected', () => { if (bt.device === device) bt.ch = null; });
        bt = { device, ch };
        save({ btId: device.id, btName: device.name || 'Printer' });
        renderPanel();
    }

    async function pickPrinter() {
        const device = await navigator.bluetooth.requestDevice({ acceptAllDevices: true, optionalServices: BLE_SERVICES });
        await attach(device);
    }

    // Reconnect without the chooser when possible (same page, or a printer this site was already allowed to use).
    async function reconnect() {
        if (bt.ch && bt.device && bt.device.gatt.connected) return true;
        if (bt.device) { try { await attach(bt.device); return true; } catch (e) {} }
        const cfg = load();
        if (cfg.btId && navigator.bluetooth.getDevices) {
            try {
                const d = (await navigator.bluetooth.getDevices()).find(x => x.id === cfg.btId);
                if (d) { await attach(d); return true; }
            } catch (e) {}
        }
        return false;
    }

    async function sendBle(bytes) {
        const ch = bt.ch;
        const size = 100;
        for (let i = 0; i < bytes.length; i += size) {
            const chunk = bytes.slice(i, i + size);
            if (ch.properties.writeWithoutResponse && ch.writeValueWithoutResponse) {
                await ch.writeValueWithoutResponse(chunk);
                await new Promise(r => setTimeout(r, 25));    // cheap printers drop data if flooded
            } else {
                await ch.writeValue(chunk);
            }
        }
    }

    function isIos() { return /iPad|iPhone|iPod/.test(navigator.userAgent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1); }

    async function printBluetooth() {
        if (!navigator.bluetooth) return alertBox('info', T.printFailed, isIos() ? T.noBluetoothIos : T.noBluetooth);
        try {
            if (!(await reconnect())) {
                // The chooser needs a fresh tap, which may have expired while we tried to reconnect.
                try { await pickPrinter(); }
                catch (e) {
                    if (e && e.name === 'NotFoundError') return;         // user closed the chooser
                    if (e && e.name !== 'SecurityError') throw e;
                    if (typeof Swal === 'undefined') throw e;
                    const res = await Swal.fire({ icon: 'info', title: T.reconnect, showCancelButton: true, confirmButtonText: T.connect, cancelButtonText: T.cancel, confirmButtonColor: '#16a34a' });
                    if (!res.isConfirmed) return;
                    await pickPrinter();
                }
            }
            await sendBle(buildEscPos(R));
            toast(T.sent);
        } catch (e) {
            if (e && e.name === 'NotFoundError') return;
            console.error('Bluetooth print error:', e);
            alertBox('error', T.printFailed, (e && e.message) || T.connectFailed);
        }
    }

    // ---------- RawBT (Android app) ----------
    function printRawBt() {
        const bytes = buildEscPos(R);
        let bin = '';
        bytes.forEach(x => { bin += String.fromCharCode(x); });
        window.location.href = 'intent:base64,' + btoa(bin) + '#Intent;scheme=rawbt;package=ru.a402d.rawbtprinter;end;';
    }

    // ---------- Browser print with roll layout ----------
    function printThermalDialog() {
        const el = document.getElementById('thermalReceipt');
        if (el.parentNode !== document.body) document.body.appendChild(el);
        const page = document.createElement('style');
        page.id = 'zpThermalPage';
        page.textContent = '@media print { @page { size: ' + R.paper + 'mm auto; margin: 0; } }';
        document.head.appendChild(page);
        document.body.classList.add('zp-thermal-print');
        const cleanup = () => {
            document.body.classList.remove('zp-thermal-print');
            page.remove();
            window.removeEventListener('afterprint', cleanup);
        };
        window.addEventListener('afterprint', cleanup);
        window.print();
    }

    function print() {
        const mode = load().mode;
        if (mode === 'bluetooth') return printBluetooth();
        if (mode === 'rawbt') return printRawBt();
        if (mode === 'thermal') return printThermalDialog();
        window.print();
    }

    // ---------- Settings modal ----------
    function renderPanel() {
        const cfg = load();
        document.querySelectorAll('input[name="zpPrintMode"]').forEach(i => { i.checked = i.value === cfg.mode; });
        const panel = document.getElementById('zpBluetoothPanel');
        if (panel) panel.classList.toggle('d-none', cfg.mode !== 'bluetooth');
        const name = document.getElementById('zpBtName');
        if (name) {
            const connected = bt.device && bt.device.gatt.connected;
            name.textContent = cfg.btName ? cfg.btName + (connected ? ' ✓' : '') : T.notConnected;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const el = document.getElementById('thermalReceipt');
        if (el && el.parentNode !== document.body) document.body.appendChild(el);

        if (!navigator.bluetooth) {
            const hint = document.getElementById('zpBluetoothHint');
            if (hint) hint.textContent = isIos() ? T.noBluetoothIos : T.noBluetooth;
        }
        document.querySelectorAll('input[name="zpPrintMode"]').forEach(i => i.addEventListener('change', () => { save({ mode: i.value }); renderPanel(); }));
        const connectBtn = document.getElementById('zpBtConnect');
        if (connectBtn) connectBtn.addEventListener('click', async () => {
            if (!navigator.bluetooth) return alertBox('info', T.connectFailed, isIos() ? T.noBluetoothIos : T.noBluetooth);
            try { await pickPrinter(); }
            catch (e) { if (!e || e.name !== 'NotFoundError') alertBox('error', T.connectFailed, (e && e.message) || ''); }
        });
        const testBtn = document.getElementById('zpTestPrint');
        if (testBtn) testBtn.addEventListener('click', print);
        renderPanel();
    });

    return { print, buildEscPos };
})();
</script>
