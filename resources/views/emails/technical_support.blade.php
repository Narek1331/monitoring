@component('mail::message')
# Новое сообщение в техническую поддержку

**Имя:** {{ $support->name }}
<br>
**Email:** {{ $support->email }}
<br>
**Тема:** {{ $support->subject }}

---

{{ $support->message }}

Спасибо,
{{ env('APP_NAME') }}
@endcomponent
