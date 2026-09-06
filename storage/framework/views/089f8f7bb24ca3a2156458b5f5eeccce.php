
<div class="mb-3">
    <label class="form-label fw-semibold">Jina Kamili</label>
    <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
        value="<?php echo e(old('name', $testimonial->name ?? '')); ?>"
        placeholder="Mfano: Amina M." style="border-radius:12px;">
    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Cheo / Mahali pa Biashara</label>
    <input type="text" name="position" class="form-control <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
        value="<?php echo e(old('position', $testimonial->position ?? '')); ?>"
        placeholder="Mfano: Hardware Store, Kariakoo" style="border-radius:12px;">
    <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<div class="row g-3 mb-3">
    <div class="col-6">
        <label class="form-label fw-semibold">Herufi za Avatar</label>
        <input type="text" name="avatar_initials" maxlength="3" class="form-control <?php $__errorArgs = ['avatar_initials'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            value="<?php echo e(old('avatar_initials', $testimonial->avatar_initials ?? '')); ?>"
            placeholder="Mfano: AM" style="border-radius:12px;">
        <small class="text-muted">Herufi 2-3 za jina (itakayoonekana kwenye duara)</small>
        <?php $__errorArgs = ['avatar_initials'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div class="col-6">
        <label class="form-label fw-semibold">Rangi ya Avatar</label>
        <select name="avatar_color" class="form-select <?php $__errorArgs = ['avatar_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="border-radius:12px;">
            <?php $__currentLoopData = ['primary' => 'Bluu', 'success' => 'Kijani', 'dark' => 'Nyeusi', 'warning' => 'Njano', 'danger' => 'Nyekundu', 'info' => 'Bluu Nyepesi']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($val); ?>" <?php echo e(old('avatar_color', $testimonial->avatar_color ?? 'primary') == $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <?php $__errorArgs = ['avatar_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Nukuu (Quote)</label>
    <textarea name="quote" rows="4" class="form-control <?php $__errorArgs = ['quote'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
        placeholder="Andika maneno ya mteja..." style="border-radius:12px; resize:vertical;"><?php echo e(old('quote', $testimonial->quote ?? '')); ?></textarea>
    <?php $__errorArgs = ['quote'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<div class="row g-3 mb-3">
    <div class="col-6">
        <label class="form-label fw-semibold">Rating (Nyota)</label>
        <select name="rating" class="form-select <?php $__errorArgs = ['rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="border-radius:12px;">
            <?php for($i=5;$i>=1;$i--): ?>
                <option value="<?php echo e($i); ?>" <?php echo e(old('rating', $testimonial->rating ?? 5) == $i ? 'selected' : ''); ?>>
                    <?php echo e($i); ?> Nyota <?php echo e(str_repeat('⭐', $i)); ?>

                </option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="col-6">
        <label class="form-label fw-semibold">Mpangilio (Order)</label>
        <input type="number" name="sort_order" class="form-control" min="0"
            value="<?php echo e(old('sort_order', $testimonial->sort_order ?? 0)); ?>" style="border-radius:12px;">
        <small class="text-muted">Nambari ndogo inaonekana kwanza</small>
    </div>
</div>

<div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
        <?php echo e(old('is_active', $testimonial->is_active ?? true) ? 'checked' : ''); ?>>
    <label class="form-check-label fw-semibold" for="is_active">Onyesha kwenye ukurasa wa mbele</label>
</div>
<?php /**PATH E:\Z-pos\resources\views\superadmin\testimonials\_form.blade.php ENDPATH**/ ?>