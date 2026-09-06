{{-- Shared form fields for Create & Edit --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Jina Kamili</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $testimonial->name ?? '') }}"
        placeholder="Mfano: Amina M." style="border-radius:12px;">
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Cheo / Mahali pa Biashara</label>
    <input type="text" name="position" class="form-control @error('position') is-invalid @enderror"
        value="{{ old('position', $testimonial->position ?? '') }}"
        placeholder="Mfano: Hardware Store, Kariakoo" style="border-radius:12px;">
    @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row g-3 mb-3">
    <div class="col-6">
        <label class="form-label fw-semibold">Herufi za Avatar</label>
        <input type="text" name="avatar_initials" maxlength="3" class="form-control @error('avatar_initials') is-invalid @enderror"
            value="{{ old('avatar_initials', $testimonial->avatar_initials ?? '') }}"
            placeholder="Mfano: AM" style="border-radius:12px;">
        <small class="text-muted">Herufi 2-3 za jina (itakayoonekana kwenye duara)</small>
        @error('avatar_initials')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-6">
        <label class="form-label fw-semibold">Rangi ya Avatar</label>
        <select name="avatar_color" class="form-select @error('avatar_color') is-invalid @enderror" style="border-radius:12px;">
            @foreach(['primary' => 'Bluu', 'success' => 'Kijani', 'dark' => 'Nyeusi', 'warning' => 'Njano', 'danger' => 'Nyekundu', 'info' => 'Bluu Nyepesi'] as $val => $label)
                <option value="{{ $val }}" {{ old('avatar_color', $testimonial->avatar_color ?? 'primary') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('avatar_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Nukuu (Quote)</label>
    <textarea name="quote" rows="4" class="form-control @error('quote') is-invalid @enderror"
        placeholder="Andika maneno ya mteja..." style="border-radius:12px; resize:vertical;">{{ old('quote', $testimonial->quote ?? '') }}</textarea>
    @error('quote')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row g-3 mb-3">
    <div class="col-6">
        <label class="form-label fw-semibold">Rating (Nyota)</label>
        <select name="rating" class="form-select @error('rating') is-invalid @enderror" style="border-radius:12px;">
            @for($i=5;$i>=1;$i--)
                <option value="{{ $i }}" {{ old('rating', $testimonial->rating ?? 5) == $i ? 'selected' : '' }}>
                    {{ $i }} Nyota {{ str_repeat('⭐', $i) }}
                </option>
            @endfor
        </select>
    </div>
    <div class="col-6">
        <label class="form-label fw-semibold">Mpangilio (Order)</label>
        <input type="number" name="sort_order" class="form-control" min="0"
            value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}" style="border-radius:12px;">
        <small class="text-muted">Nambari ndogo inaonekana kwanza</small>
    </div>
</div>

<div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
        {{ old('is_active', $testimonial->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label fw-semibold" for="is_active">Onyesha kwenye ukurasa wa mbele</label>
</div>
