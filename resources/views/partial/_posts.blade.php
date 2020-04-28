<table class="table">
    <thead>
    <tr>
        <th>
            message
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($posts as $post)
        <tr>
            <td>
                <a href="{{route('postDetail', ['postId' => $post->id])}}">
                    {{$post->message}}
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
