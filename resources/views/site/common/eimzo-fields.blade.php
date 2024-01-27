@csrf

<div class="form-group mb-2">
    <label for="">Выберите ключ</label>
    <select name="key" class="form-control bordered" onchange="cbChanged(this)"></select>
</div>

<div hidden id="keyId" class="none"></div>

<input type="hidden" name="eri_fullname" id="eri_fullname">
<input type="hidden" name="eri_inn" id="eri_inn">
<input type="hidden" name="eri_pinfl" id="eri_pinfl">
<input type="hidden" name="eri_sn" id="eri_sn">
<textarea hidden class="none" name="eri_data" id="eri_data">{{ $data }}</textarea>
<textarea hidden class="none" name="eri_hash" id="eri_hash"></textarea>

<div class="text-center">
        <button class="btn btn-sm btn-primary" id="eri_sign" onclick="sign()" type="button">Подписание</button>
    <button class="btn btn-sm btn-info" id="eri_sign" onclick="uiLoadKeys()" type="button">Обновлять</button>
</div>
