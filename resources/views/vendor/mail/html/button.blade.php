<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr width="100%">
<td width="100%">
@isset($download)
<a href="{{ $url }}" download class="button button-{{ $color ?? 'primary' }}" target="_blank" rel="noopener">{{ $slot }}</a>
@else
<a href="{{ $url }}" class="button button-{{ $color ?? 'primary' }}" target="_blank" rel="noopener">{{ $slot }}</a>
@endisset
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
