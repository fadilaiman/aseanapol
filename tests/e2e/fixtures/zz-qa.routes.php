<?php
// TEMPORARY QA sandbox preview (deleted by tests/e2e/qa-core.mjs). Inside the authed admin group.
use Illuminate\Support\Facades\Route;

Route::get('zz-qa-preview', function () {
    $P = 'zz-qa.sandbox';
    if (request('mode') === 'json') {
        $out = [];
        foreach (\App\Support\Cms::page($P)['sections'] as $s => $sec) {
            foreach ($sec['fields'] as $f => $_) {
                $out["$s.$f"] = cms($P, "$s.$f");
            }
        }
        $out['rep.__on'] = cms_on($P, 'rep');
        return response()->json($out);
    }
    return \Illuminate\Support\Facades\Blade::render(<<<'BLADE'
<!doctype html><html><body>
<div id="text">{{ cms($P,'main.text') }}</div>
<div id="area">{{ cms($P,'main.area') }}</div>
<ul id="list">@foreach(cms($P,'main.list') as $i)<li>{{ $i }}</li>@endforeach</ul>
<div id="rich">{!! cms_html($P,'main.rich') !!}</div>
<div id="html">{{ cms_html($P,'main.html') }}</div>
<a id="url" href="{{ cms($P,'main.url') }}">u</a>
<img id="img" src="{{ cms_img($P,'main.img') }}">
<a id="file" href="{{ cms_img($P,'main.file') }}">f</a>
<span id="num">{{ cms($P,'main.num') }}</span><span id="date">{{ cms($P,'main.date') }}</span>
<span id="bool">{{ cms($P,'main.bool') ? 'yes':'no' }}</span><span id="sel">{{ cms($P,'main.sel') }}</span><span id="icon">{{ cms($P,'main.icon') }}</span>
@if(cms_on($P,'rep'))
<div id="rows">@foreach(cms($P,'rep.rows') as $r)<div class="row"><b>{{ $r['title'] }}</b>{!! $r['body'] ?? '' !!}<i>@foreach($r['kids'] ?? [] as $k)[{{ $k['name'] }}]@endforeach</i></div>@endforeach</div>
@endif
</body></html>
BLADE, ['P' => $P]);
});
