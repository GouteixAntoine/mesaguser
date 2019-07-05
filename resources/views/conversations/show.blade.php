@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('conversations.users',['users'=> $users, 'unread' => $unread])
        <div class="col-md-9">
            <div class="card" style="width: 80%;">
                <div class="card-header">{{ $user->name }}</div>
                <div class="card-body conversations"></div>
                    @if($messages->hasMorePages())
                        <div class="text-center">
                            <a href="{{ $messages->nextPageUrl() }}" class="btn btn-light">
                                Voir les messages précédents
                            </a>
                        </div>
                    @endif
                    @foreach(array_reverse($messages->items()) as $message)
                        <div class="row">
                            <div class="col-md-10 {{ $message->from->id !== $user->id ? 'offset-md-2 text-right' : '' }}" style="padding-right: 40px;padding-left: 40px;">
                                <p>
                                    <strong>{{ $message->from->id !== $user->id ? 'Moi' : $message->from->name }}</strong><br>
                                    {!! nl2br(e($message->content)) !!}
                                </p>
                            </div>
                        </div>
                        <hr style="width: 90%;margin-left: 25px;">
                    @endforeach
                    @if($messages->previousPageUrl())
                    <div class="text-center">
                        <a href="{{ $messages->previousPageUrl() }}" class="btn btn-light">Voir les messages suivants</a>
                    </div>
                    @endif
                    <form action="" method="post" style="padding: 20px 30px 0 30px; margin: -15px 0 15px 0;">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <textarea name="content" placeholder="Ecrivez votre message" class="form-control {{ $errors->has('content') ? 'is-invalid' : '' }}"></textarea>
                        </div>
                        @if($errors->has('content'))
                        <div class="invalid-feedback">{{ implode(',', $errors->get('content')) }}</div>
                        @endif
                        <button class="btn btn-primary" type="submit">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
