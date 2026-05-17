@props([
    'url',
    'color' => 'primary',
    'align' => 'center',
])
<table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin: 24px 0;">
<tr>
<td align="{{ $align }}">
    <a href="{{ $url }}" target="_blank" rel="noopener"
       style="display: inline-block; background: #15803d; color: white; padding: 14px 32px;
              border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 15px;">
        {!! $slot !!}
    </a>
</td>
</tr>
</table>