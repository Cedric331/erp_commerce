<tr>
<td class="header">
<table class="inner-header" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td width="50%" align="left">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ asset('/images/logo.png') }}" width="100px" height="100px" alt="Logo">
</a>
</td>
<td width="50%" align="right">
<div>{{ strtolower(\Carbon\Carbon::now()->translatedFormat('d M Y')) }}</div>
</td>
</tr>
</table>
</td>
</tr>
