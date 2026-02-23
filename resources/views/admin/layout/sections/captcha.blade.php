<div class="captcha-sec">
    <div class="math-captcha">{{ $captcha[0] }} + {{ $captcha[1] }} = </div>
    <div class="icb-from-cb">
        <input type="hidden" name="captcha_correct" value="{{ $captcha[2] }}">
        <input placeholder="Captcha Answer" maxlength="70" type="text" id="" class="numericonly" name="captcha_ans"
            value="">
        <span class="text-danger errors" id="captcha_anserror"></span>
    </div>
</div>
