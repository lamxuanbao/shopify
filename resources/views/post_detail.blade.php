@extends('layout')
@section('content')
    <table class="table">
        <thead>
        <tr>
            <th>
                Comment
            </th>
            <th>
                Reply Message
            </th>
            <th>

            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($comments as $comment)
            <tr>
                <form action="{{ route('replyComment',['commentId' => $comment->provider_id]) }}"
                      method="post">
                    <td>
                        {{$comment->message}}
                    </td>
                    <td>
                        <textarea name="message" required></textarea>
                    </td>
                    <td>
                        <button type="submit">Reply</button>
                    </td>

                </form>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
