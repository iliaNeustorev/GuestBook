<h1>Новое сообщение от пользователя</h1>
<table class="table">
    <thead>
        <tr>
            <th>Имя автора</th>
            <th>Сообщение</th>
            <th> Дата создания</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $data['user'] }}
            <td> {{ $data['message']}}</td>
            <td>{{ $data['date'] }}</td>
        </tr>
    </tbody>
</table>