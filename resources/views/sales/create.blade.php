@extends('layouts.admin')
@section('title', 'Point of Sale (POS)')
@section('content')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container .select2-selection--single{height:38px;border:1px solid #dee2e6;border-radius:8px;background:#f8f9fa;}
.select2-container--default .select2-selection--single .select2-selection__rendered{line-height:38px;color:#212529;}
.select2-container--default .select2-selection--single .select2-selection__arrow{height:36px;}
.spos-wrap{display:flex;gap:0;height:calc(100vh - 130px);min-height:600px;}
.spos-left{flex:1;display:flex;flex-direction:column;background:#f1f5f9;border-radius:16px 0 0 16px;overflow:hidden;}
.spos-right{width:360px;display:flex;flex-direction:column;background:#fff;border-radius:0 16px 16px 0;border-left:1px solid #e2e8f0;box-shadow:4px 0 20px rgba(0,0,0,0.06);}
.spos-scanbar{background:#1e293b;padding:10px 14px;display:flex;align-items:center;gap:10px;}
.spos-scanbar input{flex:1;background:#0f172a;border:2px solid #3b82f6;border-radius:8px;color:#fff;font-size:1rem;font-weight:600;padding:8px 14px;outline:none;}
.spos-scanbar input::placeholder{color:#64748b;font-weight:400;}
.spos-scanbar input:focus{border-color:#60a5fa;box-shadow:0 0 0 3px rgba(59,130,246,0.2);}
.scan-icon{color:#3b82f6;font-size:1.4rem;}
.spos-scan-hint{color:#475569;font-size:0.72rem;white-space:nowrap;}
.spos-tabs{background:#fff;border-bottom:1px solid #e2e8f0;padding:8px 12px;display:flex;gap:6px;overflow-x:auto;flex-shrink:0;}
.spos-tab{padding:5px 14px;border-radius:20px;font-size:0.8rem;font-weight:600;cursor:pointer;white-space:nowrap;border:1.5px solid #e2e8f0;color:#64748b;background:#fff;transition:all .15s;}
.spos-tab.active{background:#3b82f6;color:#fff;border-color:#3b82f6;}
.spos-tab:hover:not(.active){background:#eff6ff;border-color:#3b82f6;color:#3b82f6;}
.spos-grid{flex:1;overflow-y:auto;padding:12px;display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:10px;align-content:start;}
.spos-product-btn{border:1.5px solid #e2e8f0;background:#fff;border-radius:12px;padding:10px 8px;cursor:pointer;transition:all .15s;text-align:left;position:relative;overflow:hidden;}
.spos-product-btn:hover{border-color:#3b82f6;background:#eff6ff;transform:translateY(-1px);box-shadow:0 4px 12px rgba(59,130,246,0.12);}
.spos-product-btn .pname{font-size:0.8rem;font-weight:600;color:#1e293b;line-height:1.3;word-break:break-word;}
.spos-product-btn .pprice{font-size:0.85rem;font-weight:700;color:#16a34a;margin-top:4px;}
.spos-product-btn .pstock{font-size:0.65rem;color:#94a3b8;}
.spos-product-btn .pstock.low{color:#f59e0b;}
.spos-product-btn .pstock.out{color:#ef4444;}
.spos-product-btn.out-of-stock{opacity:0.5;cursor:not-allowed;}
.spos-product-btn .p-barcode{font-size:0.6rem;color:#94a3b8;margin-top:2px;}
.spos-cart-header{padding:12px 14px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;flex-shrink:0;}
.spos-cart-count{background:#3b82f6;color:#fff;border-radius:20px;font-size:0.72rem;font-weight:700;padding:2px 8px;}
.spos-cart-body{flex:1;overflow-y:auto;}
.spos-cart-item{padding:8px 12px;border-bottom:1px solid #f8fafc;display:flex;gap:8px;align-items:flex-start;}
.spos-cart-item .ci-name{font-size:0.82rem;font-weight:600;color:#1e293b;flex:1;}
.spos-cart-item .ci-price{font-size:0.75rem;color:#64748b;}
.spos-qty-control{display:flex;align-items:center;gap:4px;}
.spos-qty-btn{width:24px;height:24px;border-radius:6px;border:1px solid #e2e8f0;background:#f8fafc;font-weight:700;font-size:0.85rem;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .1s;}
.spos-qty-btn:hover{background:#3b82f6;color:#fff;border-color:#3b82f6;}
.spos-qty-num{font-size:0.85rem;font-weight:700;min-width:24px;text-align:center;color:#1e293b;}
.spos-cart-empty{text-align:center;padding:40px 20px;color:#94a3b8;}
.ci-disc-badge{font-size:0.65rem;background:#fef9c3;color:#92400e;border-radius:4px;padding:1px 5px;font-weight:600;}
.ci-remove{color:#ef4444;background:none;border:none;font-size:0.85rem;cursor:pointer;padding:0;}
.spos-footer{border-top:1px solid #e2e8f0;padding:12px 14px;flex-shrink:0;background:#fff;}
.spos-total-row{display:flex;justify-content:space-between;align-items:center;padding:3px 0;}
.spos-grand-total{background:#1e293b;border-radius:10px;padding:10px 14px;display:flex;justify-content:space-between;align-items:center;margin:8px 0;}
.spos-grand-total .label{color:#94a3b8;font-size:0.82rem;font-weight:600;}
.spos-grand-total .amount{color:#fff;font-size:1.4rem;font-weight:800;}
.spos-pay-methods{display:grid;grid-template-columns:1fr 1fr;gap:6px;margin:8px 0;}
.spos-pay-btn{border:2px solid #e2e8f0;background:#fff;border-radius:10px;padding:8px 6px;cursor:pointer;text-align:center;font-size:0.78rem;font-weight:600;color:#64748b;transition:all .15s;}
.spos-pay-btn.active{border-color:#3b82f6;background:#eff6ff;color:#1d4ed8;}
.spos-pay-btn i{display:block;font-size:1.2rem;margin-bottom:2px;}
.spos-numpad{display:grid;grid-template-columns:repeat(3,1fr);gap:5px;margin:8px 0;}
.spos-numpad-btn{border:1px solid #e2e8f0;background:#f8fafc;border-radius:8px;padding:8px 4px;cursor:pointer;font-size:1rem;font-weight:600;color:#1e293b;transition:all .1s;}
.spos-numpad-btn:hover{background:#3b82f6;color:#fff;border-color:#3b82f6;}
.spos-numpad-btn.clear{background:#fef2f2;color:#ef4444;border-color:#fecaca;}
.spos-numpad-btn.clear:hover{background:#ef4444;color:#fff;}
.spos-action-row{display:flex;gap:6px;margin-top:8px;}
.spos-hold-btn{flex:1;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:9px;font-size:0.78rem;font-weight:600;color:#64748b;cursor:pointer;}
.spos-hold-btn:hover{background:#fef3c7;border-color:#fbbf24;color:#92400e;}
.spos-complete-btn{flex:2;background:linear-gradient(135deg,#2563eb,#1d4ed8);border:none;border-radius:10px;padding:9px;font-size:0.88rem;font-weight:700;color:#fff;cursor:pointer;box-shadow:0 4px 12px rgba(37,99,235,0.3);}
.spos-complete-btn:disabled{background:#94a3b8;box-shadow:none;cursor:not-allowed;}
.held-badge{position:fixed;bottom:20px;left:270px;background:#f59e0b;color:#fff;border-radius:20px;padding:6px 14px;font-size:0.78rem;font-weight:700;cursor:pointer;z-index:999;display:none;}
@media(max-width:900px){.spos-wrap{flex-direction:column;height:auto;}.spos-right{width:100%;}.spos-left{height:450px;}}
</style>
@endpush

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-3"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show mb-3"><ul class="mb-0 mt-1">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

@php
    $shopCategory = Auth::user()->shop->business_type ?? '';
    $isSupermarket = $shopCategory === 'Supermarket / Grocery';
    $showExpiry = in_array($shopCategory, ['Pharmacy / Health','Supermarket / Grocery','Restaurant / Food']);
    $showImei = in_array($shopCategory, ['Electronics / IT','Hardware / Construction']);
    $isRestaurant = $shopCategory === 'Restaurant / Food';
    $groupedProducts = $products->groupBy(function($p){ return $p->category ? $p->category->name : 'Other'; });
@endphp

@if($isSupermarket)
{{-- SUPERMARKET POS --}}
<form action="{{ route('sales.store') }}" method="POST" id="posForm">
    @csrf
    <input type="hidden" name="sale_date" id="hiddenDate" value="{{ date('Y-m-d') }}">
    <input type="hidden" name="customer_id" id="hiddenCustomer" value="">
    <input type="hidden" name="paid_amount" id="hiddenPaid" value="0">
    <input type="hidden" name="payment_method" id="hiddenPayment" value="Cash">
    <input type="hidden" name="notes" id="hiddenNotes" value="">
    <input type="hidden" name="action" id="hiddenAction" value="sale">
    <div id="cartHiddenInputs"></div>
</form>

<div class="spos-wrap">
  <div class="spos-left">
    <div class="spos-scanbar">
      <i class="bi bi-upc-scan scan-icon"></i>
      <input type="text" id="barcodeInput" placeholder="Scan barcode or type product name..." autocomplete="off" autofocus>
      <span class="spos-scan-hint">F2=Pay &amp;nbsp; F3=Hold &amp;nbsp; F4=Clear</span>
    </div>
    <div class="spos-tabs" id="categoryTabs">
      <button class="spos-tab active" data-cat="ALL">{{ __('All') }}</button>
      @foreach($groupedProducts as $catName => $catProducts)
        <button class="spos-tab" data-cat="{{ $catName }}">{{ $catName }} ({{ $catProducts->count() }})</button>
      @endforeach
    </div>
    <div class="spos-grid" id="productGrid">
      @foreach($products as $p)
      @php
        $stockNum   = $p->track_stock ? $p->current_stock : 999999;
        $stockLabel = $p->track_stock ? ($stockNum<=0?'Out of stock':($stockNum<=5?'Low: '.$stockNum:'Stock: '.$stockNum)) : '';
        $stockClass = $p->track_stock ? ($stockNum<=0?'out':($stockNum<=5?'low':'')) : '';
        $isOut      = $p->track_stock && $stockNum<=0;
        $expiryStr  = ($showExpiry && $p->expiry_date) ? \Carbon\Carbon::parse($p->expiry_date)->format('d/m/Y') : '';
        $isExpP     = ($showExpiry && $p->expiry_date) ? \Carbon\Carbon::parse($p->expiry_date)->isPast() : false;
      @endphp
      <button type="button"
        class="spos-product-btn {{ $isOut?'out-of-stock':'' }}"
        data-cat="{{ $p->category?$p->category->name:'Other' }}"
        data-id="{{ $p->id }}"
        data-name="{{ $p->name }}"
        data-barcode="{{ $p->barcode??$p->sku??'' }}"
        data-price="{{ $p->selling_price }}"
        data-stock="{{ $stockNum }}"
        data-expiry="{{ $expiryStr }}"
        data-expired="{{ $isExpP?'1':'0' }}"
        {{ $isOut?'disabled':'' }}>
        <div class="pname">{{ $p->name }}</div>
        @if($p->barcode??$p->sku??null)<div class="p-barcode">{{ $p->barcode??$p->sku }}</div>@endif
        <div class="pprice">{{ number_format($p->selling_price) }} TSh</div>
        @if($p->track_stock)<div class="pstock {{ $stockClass }}">{{ $stockLabel }}</div>@endif
        @if($expiryStr)<div class="pstock {{ $isExpP?'out':'low' }}" style="font-size:0.6rem;">Exp: {{ $expiryStr }}</div>@endif
      </button>
      @endforeach
    </div>
  </div>

  <div class="spos-right">
    <div class="spos-cart-header">
      <div>
        <div style="font-weight:700;color:#1e293b;font-size:0.95rem;"><i class="bi bi-cart3 me-2 text-primary"></i>{{ __('Cart') }}</div>
        <div style="font-size:0.72rem;color:#94a3b8;">{{ date('D, d M Y') }}</div>
      </div>
      <div class="d-flex align-items-center gap-2">
        <span class="spos-cart-count" id="cartCountBadge">0</span>
        <select id="customerSelect" class="form-select form-select-sm" style="font-size:0.75rem;border-radius:8px;border-color:#e2e8f0;max-width:130px;" onchange="document.getElementById('hiddenCustomer').value=this.value">
          <option value="">Walk-in</option>
          @foreach($customers as $cu)<option value="{{ $cu->id }}">{{ $cu->name }}</option>@endforeach
        </select>
      </div>
    </div>
    <div class="spos-cart-body" id="cartBody">
      <div class="spos-cart-empty" id="emptyMsg">
        <i class="bi bi-cart-x" style="font-size:2.5rem;color:#cbd5e1;display:block;margin-bottom:8px;"></i>
        <div style="font-size:0.85rem;">{{ __('Cart is empty') }}</div>
        <div style="font-size:0.72rem;color:#cbd5e1;">{{ __('Scan or click a product') }}</div>
      </div>
    </div>
    <div class="spos-footer">
      <div class="spos-total-row"><span style="font-size:0.8rem;color:#64748b;">{{ __('Subtotal') }}</span><span id="dispSubtotal" style="font-size:0.85rem;font-weight:600;">0 TSh</span></div>
      <div class="spos-total-row"><span style="font-size:0.8rem;color:#64748b;">{{ __('Discount') }}</span><span id="dispDiscount" style="font-size:0.85rem;font-weight:600;color:#ef4444;">-0 TSh</span></div>
      <div class="spos-grand-total"><span class="label">TOTAL</span><span class="amount" id="dispTotal">0 TSh</span></div>
      <div class="spos-pay-methods">
        <button type="button" class="spos-pay-btn active" onclick="selectPayment('Cash',this)"><i class="bi bi-cash-stack"></i>Cash</button>
        <button type="button" class="spos-pay-btn" onclick="selectPayment('Mpesa',this)"><i class="bi bi-phone"></i>Mobile</button>
        <button type="button" class="spos-pay-btn" onclick="selectPayment('Bank',this)"><i class="bi bi-bank"></i>Bank</button>
        <button type="button" class="spos-pay-btn" onclick="selectPayment('Credit',this)"><i class="bi bi-person-lines-fill"></i>Credit</button>
      </div>
      <div style="background:#f8fafc;border-radius:10px;padding:8px 12px;margin:6px 0;border:1px solid #e2e8f0;">
        <div style="font-size:0.72rem;color:#94a3b8;font-weight:600;">{{ __('AMOUNT PAID (TSh)') }}</div>
        <div style="display:flex;align-items:center;gap:8px;">
          <input type="number" id="paidAmountDisplay" class="form-control form-control-sm border-0 bg-transparent fw-bold" style="font-size:1.1rem;color:#1e293b;padding:0;" value="0" min="0" oninput="document.getElementById('hiddenPaid').value=this.value;calcChange()">
          <span style="font-size:0.72rem;color:#94a3b8;">Change: <strong id="changeAmt" style="color:#16a34a;">0</strong></span>
        </div>
      </div>
      <div class="spos-numpad">
        <button type="button" class="spos-numpad-btn" onclick="numpadPress('7')">7</button>
        <button type="button" class="spos-numpad-btn" onclick="numpadPress('8')">8</button>
        <button type="button" class="spos-numpad-btn" onclick="numpadPress('9')">9</button>
        <button type="button" class="spos-numpad-btn" onclick="numpadPress('4')">4</button>
        <button type="button" class="spos-numpad-btn" onclick="numpadPress('5')">5</button>
        <button type="button" class="spos-numpad-btn" onclick="numpadPress('6')">6</button>
        <button type="button" class="spos-numpad-btn" onclick="numpadPress('1')">1</button>
        <button type="button" class="spos-numpad-btn" onclick="numpadPress('2')">2</button>
        <button type="button" class="spos-numpad-btn" onclick="numpadPress('3')">3</button>
        <button type="button" class="spos-numpad-btn clear" onclick="numpadPress('C')">C</button>
        <button type="button" class="spos-numpad-btn" onclick="numpadPress('0')">0</button>
        <button type="button" class="spos-numpad-btn" onclick="numpadPress('00')">00</button>
      </div>
      <div class="spos-action-row">
        <button type="button" class="spos-hold-btn" onclick="holdSale()" title="F3"><i class="bi bi-pause-circle me-1"></i>{{ __('Hold') }}</button>
        <button type="button" class="spos-complete-btn" id="completeBtn" onclick="completeSale()" disabled title="F2"><i class="bi bi-check2-circle me-1"></i>{{ __('Complete Sale') }}</button>
      </div>
      <a href="{{ route('sales.index') }}" class="btn btn-sm btn-light w-100 mt-2" style="font-size:0.75rem;"><i class="bi bi-clock-history me-1"></i>{{ __('Sales History') }}</a>
    </div>
  </div>
</div>

<div class="held-badge" id="heldBadge" onclick="restoreHeld()"><i class="bi bi-pause-circle me-1"></i><span id="heldCount">0</span> {{ __('Held Sale(s)') }}</div>

<div class="modal fade" id="discountModal" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0"><h6 class="modal-title fw-bold"><i class="bi bi-tag me-2 text-warning"></i>{{ __('Item Discount') }}</h6><button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div id="discItemName" class="fw-bold text-dark mb-3" style="font-size:0.9rem;"></div>
        <div class="mb-3"><label class="form-label small fw-semibold">{{ __('Discount Type') }}</label><div class="btn-group w-100"><button type="button" class="btn btn-outline-secondary btn-sm active" id="discTypeFixed" onclick="setDiscType('fixed')">TSh Fixed</button><button type="button" class="btn btn-outline-secondary btn-sm" id="discTypePercent" onclick="setDiscType('percent')">% Percent</button></div></div>
        <div class="mb-3"><label class="form-label small fw-semibold" id="discAmtLabel">{{ __('Amount (TSh)') }}</label><input type="number" id="discAmtInput" class="form-control fw-bold" min="0" value="0"></div>
      </div>
      <div class="modal-footer border-0 pt-0"><button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">{{ __('Cancel') }}</button><button type="button" class="btn btn-warning btn-sm fw-bold" onclick="applyDiscount()"><i class="bi bi-check2 me-1"></i>{{ __('Apply') }}</button></div>
    </div>
  </div>
</div>

@push('scripts')
<script>
let cart=[],itemIndex=0,heldSales=[],currentDiscItemId=null,discType='fixed';
const barcodeInput=document.getElementById('barcodeInput');
let scanTimer=null;

document.querySelectorAll('.spos-tab').forEach(tab=>{
  tab.addEventListener('click',function(){
    document.querySelectorAll('.spos-tab').forEach(t=>t.classList.remove('active'));
    this.classList.add('active');
    const cat=this.dataset.cat;
    document.querySelectorAll('.spos-product-btn').forEach(btn=>{
      btn.style.display=(cat==='ALL'||btn.dataset.cat===cat)?'':'none';
    });
  });
});

barcodeInput.addEventListener('input',function(){
  clearTimeout(scanTimer);
  const val=this.value.trim();
  if(!val)return;
  scanTimer=setTimeout(()=>{
    const btn=document.querySelector('.spos-product-btn[data-barcode="'+val+'"]');
    if(btn&&!btn.disabled){addToCartFromBtn(btn);barcodeInput.value='';}
    barcodeInput.focus();
  },val.length>=8?80:500);
});

barcodeInput.addEventListener('keydown',function(e){
  if(e.key!=='Enter')return;
  e.preventDefault();clearTimeout(scanTimer);
  const val=this.value.trim();if(!val)return;
  let btn=document.querySelector('.spos-product-btn[data-barcode="'+val+'"]');
  if(!btn){const all=Array.from(document.querySelectorAll('.spos-product-btn:not([disabled])'));btn=all.find(b=>b.dataset.name.toLowerCase().includes(val.toLowerCase()));}
  if(btn&&!btn.disabled)addToCartFromBtn(btn);
  this.value='';this.focus();
});

document.querySelectorAll('.spos-product-btn').forEach(btn=>{
  btn.addEventListener('click',function(){
    if(this.disabled)return;
    addToCartFromBtn(this);
    this.style.background='#dbeafe';
    setTimeout(()=>this.style.background='',180);
  });
});

function addToCartFromBtn(btn){addToCart(btn.dataset.id,btn.dataset.name,parseFloat(btn.dataset.price),parseInt(btn.dataset.stock),btn.dataset.expiry||'',btn.dataset.expired==='1');}

function addToCart(id,name,price,stock,expiry,isExpired){
  if(isExpired&&!confirm('Bidhaa hii imeisha muda wake ('+expiry+').\nUnataka kuendelea?'))return;
  const existing=cart.find(i=>i.id===id);
  if(existing){if(existing.qty>=stock){alert('Imefika kiwango cha juu! Stock: '+stock);return;}existing.qty++;renderCartItem(id);}
  else{cart.push({id,name,price,qty:1,stock,expiry,isExpired,discount:0,discType:'fixed',index:itemIndex++});renderCartItem(id);}
  updateTotals();barcodeInput.focus();
}

function renderCartItem(id){
  const item=cart.find(i=>i.id===id);if(!item)return;
  const gross=item.price*item.qty;
  const disc=item.discType==='percent'?gross*item.discount/100:item.discount*item.qty;
  const lineTotal=(gross-disc).toLocaleString();
  const dLabel=item.discount>0?'<span class="ci-disc-badge">-'+(item.discType==='percent'?item.discount+'%':Number(item.discount).toLocaleString()+' TSh')+'/item</span>':'';
  const html='<div class="spos-cart-item" id="ci_'+id+'"><div style="flex:1;min-width:0;"><div class="ci-name">'+item.name+' '+dLabel+'</div><div class="ci-price">'+item.price.toLocaleString()+' TSh/unit</div><div style="display:flex;align-items:center;gap:8px;margin-top:4px;"><div class="spos-qty-control"><button type="button" class="spos-qty-btn" onclick="changeQty(\"'+id+'\",-1)">&#8722;</button><span class="spos-qty-num">'+item.qty+'</span><button type="button" class="spos-qty-btn" onclick="changeQty(\"'+id+'\",1)">+</button></div><button type="button" style="font-size:0.7rem;padding:2px 6px;background:#fef9c3;color:#92400e;border-radius:5px;border:none;cursor:pointer;" onclick="openDiscount(\"'+id+'\")">% disc</button></div></div><div style="text-align:right;display:flex;flex-direction:column;align-items:flex-end;justify-content:space-between;"><button type="button" class="ci-remove" onclick="removeItem(\"'+id+'\")"><i class="bi bi-x-circle-fill"></i></button><div class="ci-total">'+lineTotal+'</div></div></div>';
  const ex=document.getElementById('ci_'+id);
  if(ex)ex.outerHTML=html;
  else{document.getElementById('emptyMsg').style.display='none';document.getElementById('cartBody').insertAdjacentHTML('beforeend',html);}
}

function changeQty(id,delta){
  const item=cart.find(i=>i.id===id);if(!item)return;
  const nq=item.qty+delta;
  if(nq<1){removeItem(id);return;}
  if(nq>item.stock){alert('Haitoshi stock! Max: '+item.stock);return;}
  item.qty=nq;renderCartItem(id);updateTotals();
}

function removeItem(id){
  cart=cart.filter(i=>i.id!==id);
  const el=document.getElementById('ci_'+id);if(el)el.remove();
  if(cart.length===0)document.getElementById('emptyMsg').style.display='';
  updateTotals();barcodeInput.focus();
}

function updateTotals(){
  let sub=0,dis=0;
  const hd=document.getElementById('cartHiddenInputs');hd.innerHTML='';
  cart.forEach((item,idx)=>{
    const g=item.price*item.qty;
    const d=item.discType==='percent'?g*item.discount/100:item.discount*item.qty;
    sub+=g;dis+=d;
    const np=((g-d)/item.qty).toFixed(2);
    hd.innerHTML+='<input type="hidden" name="items['+idx+'][product_id]" value="'+item.id+'"><input type="hidden" name="items['+idx+'][quantity]" value="'+item.qty+'"><input type="hidden" name="items['+idx+'][price]" value="'+np+'">';
  });
  const tot=sub-dis;
  document.getElementById('dispSubtotal').textContent=sub.toLocaleString()+' TSh';
  document.getElementById('dispDiscount').textContent='-'+dis.toLocaleString()+' TSh';
  document.getElementById('dispTotal').textContent=tot.toLocaleString()+' TSh';
  document.getElementById('paidAmountDisplay').value=tot;
  document.getElementById('hiddenPaid').value=tot;
  document.getElementById('cartCountBadge').textContent=cart.reduce((s,i)=>s+i.qty,0);
  document.getElementById('completeBtn').disabled=cart.length===0;
  calcChange();
}

function calcChange(){
  const tot=parseFloat(document.getElementById('dispTotal').textContent.replace(/[^0-9.]/g,''))||0;
  const paid=parseFloat(document.getElementById('paidAmountDisplay').value)||0;
  const ch=paid-tot;
  const el=document.getElementById('changeAmt');
  el.textContent=ch>=0?ch.toLocaleString():'--';
  el.style.color=ch>=0?'#16a34a':'#ef4444';
}

function selectPayment(m,btn){
  document.querySelectorAll('.spos-pay-btn').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('hiddenPayment').value=m;
}

function numpadPress(val){
  const inp=document.getElementById('paidAmountDisplay');
  if(val==='C')inp.value=0;
  else inp.value=(inp.value==='0'?'':inp.value)+val;
  document.getElementById('hiddenPaid').value=inp.value;calcChange();
}

function completeSale(){if(cart.length===0)return;document.getElementById('hiddenAction').value='sale';document.getElementById('posForm').submit();}

function holdSale(){
  if(cart.length===0)return;
  heldSales.push({cart:JSON.parse(JSON.stringify(cart)),itemIndex});
  cart=[];itemIndex=0;
  document.getElementById('cartBody').innerHTML='<div class="spos-cart-empty" id="emptyMsg"><i class="bi bi-cart-x" style="font-size:2.5rem;color:#cbd5e1;display:block;margin-bottom:8px;"></i><div>Cart is empty</div></div>';
  updateTotals();
  document.getElementById('heldBadge').style.display='block';
  document.getElementById('heldCount').textContent=heldSales.length;
  barcodeInput.focus();
}

function restoreHeld(){
  if(!heldSales.length)return;
  if(cart.length>0&&!confirm('Weka cart ya sasa na urejeshe iliyowekwa?'))return;
  if(cart.length>0)holdSale();
  const held=heldSales.pop();cart=held.cart;itemIndex=held.itemIndex;
  document.getElementById('cartBody').innerHTML='<div class="spos-cart-empty" id="emptyMsg" style="display:none"></div>';
  cart.forEach(item=>renderCartItem(item.id));updateTotals();
  if(!heldSales.length)document.getElementById('heldBadge').style.display='none';
  else document.getElementById('heldCount').textContent=heldSales.length;
  barcodeInput.focus();
}

function openDiscount(id){
  currentDiscItemId=id;const item=cart.find(i=>i.id===id);
  document.getElementById('discItemName').textContent=item.name;
  document.getElementById('discAmtInput').value=item.discount||0;
  setDiscType(item.discType||'fixed');
  new bootstrap.Modal(document.getElementById('discountModal')).show();
}

function setDiscType(type){
  discType=type;
  document.getElementById('discTypeFixed').classList.toggle('active',type==='fixed');
  document.getElementById('discTypePercent').classList.toggle('active',type==='percent');
  document.getElementById('discAmtLabel').textContent=type==='fixed'?'Amount (TSh)':'Percent (%)';
}

function applyDiscount(){
  const item=cart.find(i=>i.id===currentDiscItemId);if(!item)return;
  item.discount=parseFloat(document.getElementById('discAmtInput').value)||0;
  item.discType=discType;
  bootstrap.Modal.getInstance(document.getElementById('discountModal')).hide();
  renderCartItem(currentDiscItemId);updateTotals();
}

document.addEventListener('keydown',function(e){
  if(e.key==='F2'){e.preventDefault();completeSale();}
  if(e.key==='F3'){e.preventDefault();holdSale();}
  if(e.key==='F4'){e.preventDefault();if(cart.length>0&&confirm('Clear cart?')){cart=[];itemIndex=0;document.getElementById('cartBody').innerHTML='<div class="spos-cart-empty" id="emptyMsg"><i class="bi bi-cart-x" style="font-size:2.5rem;color:#cbd5e1;display:block;margin-bottom:8px;"></i><div>Cart is empty</div></div>';updateTotals();}}
  if(e.target.tagName!=='INPUT'&&e.target.tagName!=='TEXTAREA'&&e.target.tagName!=='SELECT'&&!document.querySelector('.modal.show')){barcodeInput.focus();}
});
</script>
@endpush

@else
{{-- STANDARD POS --}}
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
  <div><h4 class="fw-bold mb-0 text-dark">{{ __('Point of Sale (POS)') }}</h4><span class="text-muted small">{{ __('Record new sales') }}</span></div>
  <div><a href="{{ route('sales.index') }}" class="btn btn-light px-4 shadow-sm" style="border-radius:8px;"><i class="bi bi-clock-history me-2"></i>{{ __('Sales History') }}</a></div>
</div>
<form action="{{ route('sales.store') }}" method="POST" id="posForm">
  @csrf
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-body p-4">
          <h6 class="fw-bold mb-3">{{ __('Products List') }}</h6>
          @if($isRestaurant)
            <div class="mb-4" style="max-height:500px;overflow-y:auto;">
              @foreach($groupedProducts as $categoryName => $catProducts)
                <div class="mb-4"><h6 class="fw-bold text-uppercase text-muted border-bottom pb-2">{{ $categoryName }}</h6>
                  <div class="d-flex flex-wrap gap-2 mt-2">
                    @foreach($catProducts as $p)
                      <button type="button" class="btn btn-outline-primary p-3 text-start product-btn position-relative" style="width:140px;height:100px;border-radius:12px;" data-id="{{ $p->id }}" data-name="{{ $p->name }}" data-price="{{ $p->selling_price }}" data-stock="{{ $p->track_stock?$p->current_stock:999999 }}" data-requires-imei="false" data-expiry="" data-is-expired="false">
                        <div class="fw-bold text-truncate" style="font-size:0.9rem;">{{ $p->name }}</div>
                        <div class="small fw-bold text-success position-absolute bottom-0 start-0 m-2">{{ number_format($p->selling_price) }}</div>
                      </button>
                    @endforeach
                  </div></div>
              @endforeach
            </div>
          @else
            <div class="row mb-3">
              <div class="col-12 col-md-8 mb-2 mb-md-0">
                <select id="productSelect" class="form-select border-0 shadow-sm bg-light" style="border-radius:8px;">
                  <option value="">{{ __('-- Search &amp; Select Product --') }}</option>
                  @foreach($products as $product)
                    <option value="{{ $product->id }}" data-name="{{ $product->name }} {{ $product->model?'- '.$product->model:'' }}" data-price="{{ $product->selling_price }}" data-stock="{{ $product->track_stock?$product->current_stock:999999 }}" data-requires-imei="{{ ($showImei&&$product->requires_imei)?'true':'false' }}" data-expiry="{{ ($showExpiry&&$product->expiry_date)?\Carbon\Carbon::parse($product->expiry_date)->format('M d, Y'):'' }}" data-is-expired="{{ ($showExpiry&&$product->expiry_date&&\Carbon\Carbon::parse($product->expiry_date)->isPast())?'true':'false' }}">{{ $product->name }} {{ $product->model?'- '.$product->model:'' }} (Stock: {{ $product->track_stock?$product->current_stock:'N/A' }}) - {{ number_format($product->selling_price) }} TSh</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-md-4"><button type="button" id="addItemBtn" class="btn btn-primary w-100" style="border-radius:8px;"><i class="bi bi-plus-lg"></i> {{ __('Add to Cart') }}</button></div>
            </div>
          @endif
          <div class="table-responsive">
            <table class="table align-middle" id="cartTable">
              <thead class="bg-light text-muted"><tr><th class="ps-3 border-0 rounded-start" style="min-width:250px;">{{ __('Product') }}</th><th class="border-0 text-center" style="min-width:120px;">{{ __('Qty') }}</th><th class="border-0 text-end" style="min-width:120px;">{{ __('Price') }}</th><th class="border-0 text-end" style="min-width:120px;">{{ __('Total') }}</th><th class="border-0 rounded-end text-center" style="min-width:50px;"></th></tr></thead>
              <tbody id="cartBody"><tr id="emptyCartRow"><td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-cart-x fs-1 d-block mb-2"></i><p class="mb-0">{{ __('Cart is empty. Select products to sell.') }}</p></td></tr></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4"><div class="card border-0 shadow-sm mb-4" style="border-radius:16px;"><div class="card-body p-4">
      <h6 class="fw-bold mb-4">{{ __('Sale Details') }}</h6>
      <div class="mb-3"><label class="form-label text-muted small fw-medium">{{ __('Reference / Receipt No.') }}</label><input type="text" class="form-control bg-light border-0" value="{{ $reference_no }}" readonly></div>
      <div class="mb-3"><label class="form-label text-muted small fw-medium">{{ __('Date') }}</label><input type="date" name="sale_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
      <div class="mb-4"><label class="form-label text-muted small fw-medium">{{ __('Customer (Optional)') }}</label><select name="customer_id" class="form-select"><option value="">{{ __('Walk-in Customer (None)') }}</option>@foreach($customers as $customer)<option value="{{ $customer->id }}">{{ $customer->name }}</option>@endforeach</select></div>
      <hr><div class="d-flex justify-content-between mb-2"><span class="text-muted fw-medium">{{ __('Subtotal') }}</span><span class="fw-bold" id="cartSubtotal">0 TSh</span></div>
      <div class="d-flex justify-content-between mb-4"><h4 class="fw-bold mb-0">{{ __('Total') }}</h4><h4 class="fw-bold text-primary mb-0" id="cartTotal">0 TSh</h4></div><hr>
      <div class="mb-3"><label class="form-label fw-bold">{{ __('Amount Paid') }} (TSh) <span class="text-danger">*</span></label><input type="number" name="paid_amount" id="paidAmount" class="form-control form-control-lg fw-bold" value="0" min="0" required></div>
      <div class="mb-4"><label class="form-label text-muted small fw-medium">{{ __('Payment Method') }} <span class="text-danger">*</span></label><select name="payment_method" class="form-select"><option value="Cash">{{ __('Cash') }}</option><option value="Mpesa">Mpesa / Mobile Money</option><option value="Bank">{{ __('Bank Transfer') }}</option><option value="Credit">Credit (Deni)</option></select></div>
      <div class="mb-4"><label class="form-label text-muted small fw-medium">{{ __('Notes (Optional)') }}</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
      <button type="submit" name="action" value="sale" class="btn btn-primary btn-lg w-100 fw-bold" style="border-radius:10px;" id="submitBtn" disabled><i class="bi bi-check-circle me-2"></i>{{ __('Complete Sale') }}</button>
      <button type="submit" name="action" value="proforma" class="btn btn-outline-primary btn-lg w-100 fw-bold mt-2" style="border-radius:10px;" id="proformaBtn" disabled><i class="bi bi-file-earmark-text me-2"></i>{{ __('Save as Pro-forma') }}</button>
    </div></div></div>
  </div>
</form>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function(){$('#productSelect').select2({placeholder:'-- Search & Select Product --',allowClear:true,width:'100%'});$('select[name="customer_id"]').select2({placeholder:'Walk-in Customer (None)',allowClear:true,width:'100%'});});
document.addEventListener('DOMContentLoaded',function(){
  let cart=[],itemIndex=0;
  const pSel=document.getElementById('productSelect'),addBtn=document.getElementById('addItemBtn'),cBody=document.getElementById('cartBody'),empRow=document.getElementById('emptyCartRow'),subEl=document.getElementById('cartSubtotal'),totEl=document.getElementById('cartTotal'),paidInp=document.getElementById('paidAmount'),subBtn=document.getElementById('submitBtn');
  document.querySelectorAll('.product-btn').forEach(btn=>{btn.addEventListener('click',function(){this.classList.add('bg-primary','text-white');setTimeout(()=>this.classList.remove('bg-primary','text-white'),150);addToCart(this.dataset.id,this.dataset.name,parseFloat(this.dataset.price),parseInt(this.dataset.stock),false,'',false);});});
  if(addBtn){addBtn.addEventListener('click',function(){if(!pSel.value)return;const o=pSel.options[pSel.selectedIndex];addToCart(o.value,o.dataset.name,parseFloat(o.dataset.price),parseInt(o.dataset.stock),o.dataset.requiresImei==='true',o.dataset.expiry,o.dataset.isExpired==='true');$('#productSelect').val(null).trigger('change');});}
  function addToCart(id,name,price,stock,req,exp,isExp){if(isExp&&!confirm('WARNING: Expired ('+exp+'). Continue?'))return;let ex=cart.find(i=>i.id===id);if(ex){if(ex.qty>=stock){alert('Cannot exceed stock!');return;}ex.qty++;document.getElementById('qty_'+id).value=ex.qty;}else{cart.push({id,name,price,qty:1,stock,index:itemIndex,req,exp,isExp});renderRow(id,name,price,stock,itemIndex,req,exp,isExp);itemIndex++;}updT();}
  function renderRow(id,name,price,stock,idx,req,exp,isExp){if(empRow)empRow.style.display='none';let ex2='';if(exp)ex2+='<br><small class="'+(isExp?'text-danger fw-bold':'text-warning')+'">Exp: '+exp+'</small>';if(req)ex2+='<div class="mt-2"><input type="text" name="items['+idx+'][imei]" class="form-control border-primary" style="min-width:180px;" placeholder="Enter IMEI / Serial Number" required></div>';const tr=document.createElement('tr');tr.id='row_'+id;tr.innerHTML='<td class="ps-3 fw-bold text-dark">'+name+ex2+'<input type="hidden" name="items['+idx+'][product_id]" value="'+id+'"></td><td class="text-center"><input type="number" name="items['+idx+'][quantity]" id="qty_'+id+'" class="form-control form-control-sm text-center qty-input" value="1" min="1" max="'+stock+'" data-id="'+id+'"></td><td class="text-end"><input type="number" name="items['+idx+'][price]" class="form-control form-control-sm text-end price-input" value="'+price+'" min="0" data-id="'+id+'"></td><td class="text-end fw-bold" id="total_'+id+'">'+price.toLocaleString()+'</td><td class="text-center"><button type="button" class="btn btn-sm btn-light text-danger remove-btn" data-id="'+id+'"><i class="bi bi-x-lg"></i></button></td>';cBody.appendChild(tr);tr.querySelector('.qty-input').addEventListener('input',function(){let item=cart.find(i=>i.id===id),val=parseInt(this.value);if(val>stock){alert('Cannot exceed stock ('+stock+')');this.value=stock;val=stock;}if(val<1||isNaN(val))val=1;item.qty=val;updT();});tr.querySelector('.price-input').addEventListener('input',function(){let item=cart.find(i=>i.id===id);item.price=parseFloat(this.value)||0;updT();});tr.querySelector('.remove-btn').addEventListener('click',function(){cart=cart.filter(i=>i.id!==id);tr.remove();if(cart.length===0)empRow.style.display='table-row';updT();});}
  function updT(){let tot=0;cart.forEach(item=>{let t=item.price*item.qty;tot+=t;document.getElementById('total_'+item.id).innerText=t.toLocaleString();});subEl.innerText=tot.toLocaleString()+' TSh';totEl.innerText=tot.toLocaleString()+' TSh';paidInp.value=tot;subBtn.disabled=cart.length===0;document.getElementById('proformaBtn').disabled=cart.length===0;}
});
</script>
@endpush

@endif

@endsection
